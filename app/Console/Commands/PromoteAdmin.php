<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PromoteAdmin extends Command
{
    protected $signature = 'safenet:promote-admin
                            {email : The email address of the user to promote}
                            {--demote : Demote an admin back to student instead}';

    protected $description = 'Promote (or demote) a user to the admin role. Use this for bootstrapping admin access — the first admin must be set this way before logging into Filament.';

    public function handle(): int
    {
        $email = strtolower(trim($this->argument('email')));
        $user  = User::where('email', $email)->first();

        if (! $user) {
            $this->error("Не постои корисник со е-маил: {$email}");

            return self::FAILURE;
        }

        if ($this->option('demote')) {
            if (! $user->isAdmin()) {
                $this->warn("{$user->name} не е администратор. Ништо не е променето.");

                return self::SUCCESS;
            }

            if (User::where('role', 'admin')->count() <= 1) {
                $this->error('Не можеш да го деградираш последниот администратор.');

                return self::FAILURE;
            }

            $user->update(['role' => 'student']);
            $this->info("{$user->name} ({$user->email}) е сега ученик.");

            return self::SUCCESS;
        }

        if ($user->isAdmin()) {
            $this->warn("{$user->name} веќе е администратор.");

            return self::SUCCESS;
        }

        $user->update([
            'role' => 'admin',
            // Admins must have a verified email so they can hit Filament immediately.
            'email_verified_at' => $user->email_verified_at ?? now(),
        ]);

        $this->info("{$user->name} ({$user->email}) е сега администратор.");

        return self::SUCCESS;
    }
}
