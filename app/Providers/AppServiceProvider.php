<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('register', fn (Request $request) =>
            Limit::perHour(8)->by($request->ip()));

        RateLimiter::for('invitations', fn (Request $request) =>
            Limit::perMinute(10)->by(auth()->id() ?: $request->ip()));

        Password::defaults(function () {
            $rule = Password::min(10)->mixedCase()->numbers()->symbols();

            return $this->app->isProduction() ? $rule->uncompromised() : $rule;
        });

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Потврди ја твојата е-маил адреса — SafeNet')
                ->greeting('Здраво, ' . ($notifiable->name ?? '') . '!')
                ->line('Добредојде во SafeNet. За да го активираш твојот профил, ве молиме потврдете ја вашата е-маил адреса со кликнување на копчето подолу.')
                ->action('Потврди е-маил', $url)
                ->line('Ако не си направил профил, можеш да ја игнорираш оваа порака.')
                ->salutation('Поздрав, тимот на SafeNet');
        });

            ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Ресетирање на лозинка — SafeNet')
                ->greeting('Здраво, ' . ($notifiable->name ?? '') . '!')
                ->line('Добивте порака бидејќи побаравте ресетирање на лозинката за вашиот SafeNet профил.')
                ->action('Ресетирај лозинка', $url)
                ->line('Овој линк ќе истече за ' . config('auth.passwords.users.expire') . ' минути.')
                ->line('Ако не сте го побарале ова, можете да ја игнорирате пораката.')
                ->salutation('Поздрав, тимот на SafeNet');
        });
    }
}
