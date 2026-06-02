<?php

namespace App\Notifications;

use App\Models\ParentChildInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParentChildInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ParentChildInvitation $invitation)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $invitation = $this->invitation->loadMissing('parent');
        $parentName = $invitation->parent?->name ?? 'Родител';
        $childHasAccount = $invitation->child_user_id !== null;

        // Existing child: takes them straight to the in-app accept/decline page (auth required).
        // No account yet: sends them to a signup link that pre-fills their email + token.
        $url = $childHasAccount
            ? route('invitation.show', $invitation->token)
            : route('register', ['invite' => $invitation->token]);

        $message = (new MailMessage)
            ->subject('Покана за поврзување на SafeNet профил')
            ->greeting('Здраво!')
            ->line($parentName . ' те поканува да го поврзе твојот SafeNet профил како родител-дете.');

        if ($childHasAccount) {
            $message
                ->line('Со прифаќање, родителот ќе може да пополнува прашалници за тебе и да ги гледа резултатите.')
                ->action('Прегледај ја поканата', $url)
                ->line('Ако не сакаш да прифатиш, едноставно игнорирај ја пораката или одбиј преку линкот погоре.');
        } else {
            $message
                ->line('За да прифатиш, направи SafeNet профил преку линкот подолу — твојата е-маил адреса и поканата ќе се пополнат автоматски.')
                ->action('Регистрирај се и прифати', $url)
                ->line('Ако не очекуваш ваква покана, можеш слободно да ја игнорираш.');
        }

        if ($this->invitation->expires_at) {
            $message->line('Поканата важи до ' . $this->invitation->expires_at->format('d.m.Y H:i') . '.');
        }

        return $message->salutation('Поздрав, тимот на SafeNet');
    }
}
