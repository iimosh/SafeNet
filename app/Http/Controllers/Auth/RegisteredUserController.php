<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ParentChildInvitation;
use App\Models\User;
use App\Notifications\ParentChildInvitationNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(Request $request): View
    {
        $invitation = $this->resolveInvitation($request->query('invite'));

        return view('auth.register', [
            'invitation' => $invitation,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $invitation = $this->resolveInvitation($request->input('invite_token'));

        // When invited as a child, role is forced to student and email is locked.
        if ($invitation) {
            $request->merge([
                'email' => $invitation->child_email,
                'role'  => 'student',
            ]);
        }

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'in:student,parent'],
        ]);

        if (! $invitation && $request->role === 'parent') {
            $request->validate([
                'child_email' => ['required', 'email:rfc', 'different:email'],
            ]);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        // Invitation flow: auto-link parent → child once the new student registers.
        if ($invitation && $user->role === 'student') {
            $invitation->parent->children()->attach($user->id);
            $invitation->markAccepted($user);
        }

        // Parent self-registration with child_email: send an invitation instead of
        // silently attaching. The child must explicitly accept (or register through
        // a signup-invite link if no account exists yet).
        $pendingChildEmail = null;
        if (! $invitation && $request->role === 'parent') {
            $childEmail = strtolower(trim($request->child_email));
            $existingChild = User::where('email', $childEmail)->where('role', 'student')->first();

            $pendingInvite = ParentChildInvitation::create([
                'parent_id'     => $user->id,
                'child_email'   => $childEmail,
                'child_user_id' => $existingChild?->id,
                'token'         => ParentChildInvitation::generateToken(),
                'status'        => ParentChildInvitation::STATUS_PENDING,
                'expires_at'    => now()->addDays(ParentChildInvitation::DEFAULT_TTL_DAYS),
            ]);

            if ($existingChild) {
                $existingChild->notify(new ParentChildInvitationNotification($pendingInvite));
            } else {
                Notification::route('mail', $childEmail)
                    ->notify(new ParentChildInvitationNotification($pendingInvite));
            }

            $pendingChildEmail = $childEmail;
        }

        event(new Registered($user));
        Auth::login($user);

        $redirect = match (auth()->user()->role) {
            'parent' => redirect()->route('parent.dashboard'),
            default  => redirect()->route('dashboard'),
        };

        if ($pendingChildEmail) {
            $redirect = $redirect->with('success',
                'Регистрацијата е успешна. Испративме покана на ' . $pendingChildEmail .
                ' — поврзувањето ќе се активира откако детето ќе ја прифати.');
        }

        return $redirect;
    }

    private function resolveInvitation(?string $token): ?ParentChildInvitation
    {
        if (! $token) {
            return null;
        }

        $invitation = ParentChildInvitation::with('parent')->where('token', $token)->first();

        if (! $invitation) {
            return null;
        }

        if ($invitation->hasExpired() && $invitation->status === ParentChildInvitation::STATUS_PENDING) {
            $invitation->update(['status' => ParentChildInvitation::STATUS_EXPIRED]);
        }

        return $invitation->isPending() ? $invitation : null;
    }
}
