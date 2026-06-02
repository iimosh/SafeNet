<?php

namespace App\Http\Controllers;

use App\Models\ParentChildInvitation;
use App\Models\User;
use App\Notifications\ParentChildInvitationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ParentController extends Controller
{
    public function addChild(Request $request)
    {
        $request->validate([
            'child_email' => ['required', 'email:rfc'],
        ]);

        $parent = auth()->user();
        $email  = strtolower(trim($request->child_email));

        if ($email === strtolower($parent->email)) {
            return back()->withErrors(['child_email' => 'Не можеш да испратиш покана сам на себе.']);
        }

        // Look up existing student account (if any).
        $existing = User::where('email', $email)->where('role', 'student')->first();

        // Already linked? Don't create a duplicate invite.
        if ($existing && $parent->children->contains($existing->id)) {
            return back()->withErrors(['child_email' => 'Ова дете е веќе поврзано со твојот профил.']);
        }

        // A pending invitation already exists?
        $existingInvite = ParentChildInvitation::where('parent_id', $parent->id)
            ->where('child_email', $email)
            ->where('status', ParentChildInvitation::STATUS_PENDING)
            ->first();

        if ($existingInvite && $existingInvite->isPending()) {
            return back()->withErrors(['child_email' => 'Веќе има активна покана за оваа е-маил адреса. Откажи ја пред да испратиш нова.']);
        }

        $invitation = ParentChildInvitation::create([
            'parent_id'     => $parent->id,
            'child_email'   => $email,
            'child_user_id' => $existing?->id,
            'token'         => ParentChildInvitation::generateToken(),
            'status'        => ParentChildInvitation::STATUS_PENDING,
            'expires_at'    => now()->addDays(ParentChildInvitation::DEFAULT_TTL_DAYS),
        ]);

        if ($existing) {
            $existing->notify(new ParentChildInvitationNotification($invitation));
        } else {
            Notification::route('mail', $email)
                ->notify(new ParentChildInvitationNotification($invitation));
        }

        return back()->with('success', $existing
            ? 'Поканата е испратена. Детето мора да ја прифати од својот профил.'
            : 'Поканата е испратена. Откако ќе се регистрира, детето ќе биде поврзано со твојот профил.');
    }

    public function cancelInvitation(ParentChildInvitation $invitation)
    {
        abort_if($invitation->parent_id !== auth()->id(), 403);

        $invitation->markCancelled();

        return back()->with('success', 'Поканата е откажана.');
    }

    public function removeChild(User $child)
    {
        $parent = auth()->user();

        abort_if(!$parent->children->contains($child->id), 403);

        if ($parent->children->count() <= 1) {
            return back()->withErrors(['error' => 'Мора да имаш барем едно поврзано дете.']);
        }

        $parent->children()->detach($child->id);

        return back()->with('success', 'Детето е успешно отстрането.');
    }
}
