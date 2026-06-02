<?php

namespace App\Http\Controllers;

use App\Models\ParentChildInvitation;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function show(string $token, Request $request)
    {
        $invitation = ParentChildInvitation::with('parent')
            ->where('token', $token)
            ->firstOrFail();

        if ($invitation->hasExpired() && $invitation->status === ParentChildInvitation::STATUS_PENDING) {
            $invitation->update(['status' => ParentChildInvitation::STATUS_EXPIRED]);
        }

        if (! auth()->check()) {
            $request->session()->put('url.intended', route('invitation.show', $invitation->token));
            return redirect()->route('login')
                ->with('status', 'Најави се за да ја прегледаш поканата.');
        }

        if (strtolower(auth()->user()->email) !== strtolower($invitation->child_email)) {
            abort(403, 'Оваа покана не е наменета за твојот профил.');
        }

        return view('invitations.show', compact('invitation'));
    }

    public function accept(string $token)
    {
        $invitation = $this->resolveActionable($token);

        $user = auth()->user();

        if (! $invitation->parent->children->contains($user->id)) {
            $invitation->parent->children()->attach($user->id);
        }

        $invitation->markAccepted($user);

        return redirect()->route('dashboard')
            ->with('success', 'Поканата е прифатена. Сега си поврзан/а со ' . $invitation->parent->name . '.');
    }

    public function decline(string $token)
    {
        $invitation = $this->resolveActionable($token);
        $invitation->markDeclined();

        return redirect()->route('dashboard')
            ->with('success', 'Поканата е одбиена.');
    }

    private function resolveActionable(string $token): ParentChildInvitation
    {
        $invitation = ParentChildInvitation::with('parent')
            ->where('token', $token)
            ->firstOrFail();

        abort_if(! $invitation->isPending(), 410, 'Поканата веќе не е активна.');
        abort_if(strtolower(auth()->user()->email) !== strtolower($invitation->child_email), 403);

        return $invitation;
    }
}
