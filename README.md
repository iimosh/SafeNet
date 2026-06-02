# SafeNet

SafeNet (MKSafeNet) is a Laravel 12 application for assessing the online-safety / digital habits of students. Students fill in a questionnaire about themselves; parents fill in a parallel one about their linked child. Answers are scored into per-category and global risk levels (low / medium / high), matched to stored recommendations, and exportable as a PDF report. Admins manage questionnaire content through a Filament panel.

The end-user UI is in **Macedonian (Cyrillic)**.

---

## Tech stack

- PHP 8.4, Laravel 12
- Filament 5 (admin panel)
- Blade + Tailwind 3 + Alpine.js, bundled with Vite
- SQLite (default) — single file at `database/database.sqlite`
- `barryvdh/laravel-dompdf` for PDF reports
- Postmark / Gmail SMTP for transactional email (configurable)

---

## Local setup (Windows)

> If you're on Mac/Linux the steps are the same minus the PATH gotchas — install PHP 8.4 from your package manager and skip ahead to step 3.

### Prerequisites

- **PHP 8.4** — the committed `composer.lock` pins Symfony 8, which requires ≥ 8.4. XAMPP's bundled PHP 8.2 will **not** work.
  - Install via winget: `winget install PHP.PHP.8.4`
  - Then create a `php.ini` from `php.ini-development` in the install dir and enable: `intl`, `zip`, `pdo_sqlite`, `mbstring`, `openssl`, `curl`, `fileinfo`.
- **Composer** — https://getcomposer.org/Composer-Setup.exe
- **Node 20+** and npm

### PATH note (Windows)

If you already have XAMPP installed, its `C:\xampp\php` directory will be on your system PATH and take precedence over your user PATH, so a bare `php` resolves to 8.2. To force PHP 8.4 for this project, the repo ships three batch shims:

- `.\php.bat` — call PHP 8.4 directly (`.\php artisan migrate`)
- `.\serve.bat` — `php artisan serve` with PHP 8.4 forced
- `.\dev.bat` — `composer dev` (server + queue + vite) with PHP 8.4 forced

Both `.bat` files contain a hard-coded path to PHP 8.4 — update them if your install path differs. Or, with admin PowerShell, you can permanently remove XAMPP from the system PATH:
```powershell
$p = [Environment]::GetEnvironmentVariable('Path','Machine')
$new = ($p -split ';' | Where-Object { $_ -and $_ -notmatch 'xampp\\php' }) -join ';'
[Environment]::SetEnvironmentVariable('Path', $new, 'Machine')
```

### First-time install

```cmd
.\php composer.phar install     :: or just `composer install` if Composer is on PATH
npm install
copy .env.example .env
.\php artisan key:generate
.\php artisan migrate --seed
npm run build
```

### Running

```cmd
.\dev.bat                       :: server + queue + logs + vite in one terminal
:: or, two terminals:
.\serve.bat                     :: http://127.0.0.1:8000
npm run dev                     :: Vite dev server (hot reload)
```

**Important**: emails are queued. If you only run `serve.bat`, mail jobs pile up in the `jobs` table and nothing actually sends. Use `.\dev.bat` (which starts the queue worker too) or run `.\php artisan queue:work` in a third terminal.

Open **http://127.0.0.1:8000**. Demo accounts (password is `password` for all):

| Email                  | Role    | Notes                                         |
|------------------------|---------|-----------------------------------------------|
| `admin@safenet.com`    | admin   | Lands at `/admin` — Filament panel            |
| `test@example.com`     | student | Pre-linked to `parent@example.com`            |
| `parent@example.com`   | parent  | Pre-linked to `test@example.com`              |

> The demo accounts are seeded with `email_verified_at` already set — real registrations require email verification (see "How email works" below).

---

## Project layout & domain

```
app/
├── Console/Commands/PromoteAdmin.php       safenet:promote-admin {email}
├── Filament/Resources/                     admin panel resources (Questionnaires, Questions, Options, Users)
├── Http/Controllers/
│   ├── AssessmentController.php            ← scoring lives here (submit())
│   ├── InvitationController.php            parent→child invite accept/decline
│   ├── ParentController.php                addChild, cancelInvitation, removeChild
│   ├── QuestionnaireController.php         lists questionnaires (role-scoped)
│   └── ReportController.php                PDF generation
├── Models/                                 Assessment, Category, Option, ParentChildInvitation,
│                                           Question, Questionnaire, Recommendation, User
├── Notifications/ParentChildInvitationNotification.php
└── Providers/AppServiceProvider.php        password policy, rate limiters, branded mail copy

database/
├── database.sqlite                         the DB file (you can open it in PhpStorm)
├── migrations/                             schema
└── seeders/                                demo users + questionnaire content
```

### Role system

`users.role` is a string enum: `admin`, `student`, `parent`. `User` exposes `isAdmin() / isStudent() / isParent()`.

- Routing and authorization are role-driven. The `/` route redirects by role; controllers use `match($user->role)` blocks for `$canView` checks.
- Filament panel is gated by `User::canAccessPanel()` → admins only.
- Self-registration only allows `student` or `parent`. Admins are created via `safenet:promote-admin` or by an existing admin in the Filament User resource.

### Assessment domain (the core)

```
Questionnaire (target_role: student|parent)
  └─ Category (ordered by sort_order)
       └─ Question
            └─ Option (carries risk_points)

Assessment (user_id = submitter, filled_for_user_id = subject)
  ├─ category_breakdown (JSON: per-category score, max, risk, recommendation)
  └─ AssessmentAnswer × N
```

- **Scoring lives in `AssessmentController::submit()`** (not the models). The whole create→loop→update block is wrapped in `DB::transaction(...)` so a failure mid-scoring can't leave an orphan.
- Risk level is **percentage of max** (`resolveRiskLevel()`): ≤30% low, ≤60% medium, else high.
- Recommendations are **data, not code**: the `recommendations` table is queried by questionnaire + category + risk_level. Seed/edit content, don't hardcode strings.
- `filled_for_user_id` is who the assessment is *about* (the student); `user_id` is who submitted it. The student-vs-parent comparison feature in the result/report views uses `Assessment::findPaired()` to find the opposite-role assessment for the same child.

### Parent ↔ student linking (invitation flow)

Parents cannot directly attach children. The flow:

1. Parent enters child's email on their dashboard → `ParentController::addChild` creates a `parent_student_invitations` row with a random 48-char token and 7-day expiry.
2. Email is sent (queued):
   - **If the child has an account**: notification to that user, link goes to `/invitation/{token}` (in-app accept/decline page).
   - **If no account yet**: on-demand notification to the email, link goes to `/register?invite={token}` which pre-fills the email and locks role to student. Parent is auto-attached on registration.
3. Child accepts → `parent->children()->attach(child)` + invitation marked `accepted`. Child declines → marked `declined`. Parent can cancel before either happens.

Invitations have status `pending` → `accepted` | `declined` | `cancelled` | `expired`.

---

## How email works

### Dev (default)

`.env` has `MAIL_MAILER=log` — every "sent" email is appended to `storage/logs/laravel.log`. Tail it with:
```cmd
powershell -Command "Get-Content -Wait -Tail 50 storage/logs/laravel.log"
```

Better dev option: install **Mailpit** (https://github.com/axllent/mailpit). It's a tiny standalone app that pretends to be SMTP on `127.0.0.1:1025` and shows received emails (HTML/plain/headers) in a browser UI at `http://127.0.0.1:8025`. Then:
```
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
```

### Production

The app is mailer-agnostic — pick a transport, set the env vars, restart the queue. **No code change is ever required** to switch providers.

**Gmail SMTP** (free, our current choice — capped at ~500/day personal or 2000/day Workspace):
1. Enable 2FA on the Google account.
2. Generate an App Password at https://myaccount.google.com/apppasswords.
3. In production `.env`:
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=youraccount@gmail.com
   MAIL_PASSWORD=your-16-char-app-password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="youraccount@gmail.com"
   MAIL_FROM_NAME="SafeNet"
   ```

**If you outgrow Gmail's daily cap**, switch to a dedicated transactional provider (Postmark, Resend, Mailgun, SES). Steps:
1. `composer require symfony/postmark-mailer symfony/http-client` (or the equivalent for your chosen provider).
2. Add the provider's API key env var (`POSTMARK_API_KEY=...`).
3. Set `MAIL_MAILER=postmark`.
4. Restart the queue worker.

After any `.env` change in production: `php artisan config:cache && php artisan queue:restart`.

### The queue

All app-sent mail (verification, password reset, invitations) is **queued** via `ShouldQueue`. This keeps requests fast — the controller persists a job row in the `jobs` table and returns immediately; the actual SMTP/API call happens in the background.

You **must** run a queue worker for any mail to be delivered:
```cmd
.\php artisan queue:work
```
`.\dev.bat` starts one for you in dev. In production, run it under a process supervisor (Supervisor on Linux, NSSM on Windows, etc.) so it restarts on crash.

Failed jobs land in `failed_jobs` — inspect with `php artisan queue:failed`, retry with `php artisan queue:retry all`.

---

## Admin management

Admins **cannot self-register**. Three ways to make an admin:

1. **CLI bootstrap** (first admin, or recovery):
   ```cmd
   .\php artisan safenet:promote-admin user@example.com
   ```
   Reverse with `--demote`. Refuses to demote the last remaining admin.
2. **Filament User resource** at `/admin/users` — list/edit/delete users, change role via dropdown. Guards prevent deleting yourself or demoting the last admin.
3. **Direct DB edit** (last resort): set `role = 'admin'` on the users row.

Admin accounts auto-set `email_verified_at` when promoted so they go straight into the panel without bouncing to the verification screen.

---

## Security features

- **Email verification** enforced on all app routes — unverified users get bounced to a verification notice screen.
- **Strong passwords** — min 10 chars, mixed case, numbers, symbols, and (in prod/local) a haveibeenpwned breach check via k-anonymity (no password ever leaves your server).
- **Rate limiting** — registration is 8/hour/IP, invitations are 10/min/user, password reset emails are 6/min, login uses Laravel's default throttle.
- **CSRF** on all POST/PUT/DELETE routes (Laravel default).
- **DB transaction** around assessment scoring so a failure mid-scoring can't leave an orphan.
- **Role-scoped queries** — students see only their own data, parents see only their linked children, admins see everything.
- Parent↔child link requires explicit child consent (invitation flow).

---

## Common commands

```cmd
:: dev
.\dev.bat                            full dev stack (server + queue + vite)
.\serve.bat                          just the web server
.\php artisan queue:work             just the queue worker
npm run dev                          Vite hot reload
npm run build                        production assets

:: database
.\php artisan migrate                run new migrations
.\php artisan migrate:fresh --seed   wipe + reseed (destroys data)
.\php artisan db                     interactive SQLite shell
.\php artisan tinker                 interactive PHP REPL with app booted

:: tests
.\php artisan test                   full PHPUnit suite
.\php artisan test --filter=Registration   single class
./vendor/bin/pint                    code formatter (Laravel Pint)
./vendor/bin/pint --test             check only, don't write

:: admin
.\php artisan safenet:promote-admin user@example.com
.\php artisan safenet:promote-admin user@example.com --demote

:: queue
.\php artisan queue:failed           list failed jobs
.\php artisan queue:retry all        retry all failed jobs
.\php artisan queue:flush            wipe failed jobs

:: caches (after .env or route changes in prod)
.\php artisan config:cache
.\php artisan route:cache
.\php artisan view:cache
.\php artisan optimize:clear         nuke all caches
```

---

## Inspecting the database

The `database/database.sqlite` file holds everything. Three ways to look inside:

1. **PhpStorm** — right-click the file → "Open in" → "Database". Install the SQLite driver when prompted. Full GUI: browse tables, edit rows (press **Ctrl+Enter** to commit — edits are staged until then), write SQL.
2. **CLI** — `.\php artisan db` drops you in an interactive SQLite prompt: `.tables`, `.schema users`, `SELECT * FROM assessments;`, `.quit`.
3. **Tinker** — `.\php artisan tinker` for Eloquent queries: `\App\Models\User::all(['id','name','role'])` etc.

---

## Troubleshooting

| Symptom | Likely cause / fix |
|---|---|
| `composer install` fails with "platform requires PHP >= 8.4" | XAMPP's PHP 8.2 is winning on PATH. Use `.\php composer.phar install` or fix PATH (see setup). |
| `php artisan serve` runs old code | You're using XAMPP's PHP. Use `.\serve.bat`. |
| Registered a user but no email in the log | Queue worker isn't running. Start `.\php artisan queue:work` or use `.\dev.bat`. |
| "verified" middleware loops you | The user has no `email_verified_at`. Either click the link in the log, or in tinker: `User::find(N)->update(['email_verified_at' => now()])`. |
| Filament panel says "Forbidden" | Your user isn't an admin. Run `.\php artisan safenet:promote-admin youremail`. |
| PhpStorm DB edit "doesn't save" | Press **Ctrl+Enter** to submit. Edits are staged by default. |
| `composer dev` / `.\dev.bat` crashes with "pcntl extension required" | Stock Laravel includes `pail` (log tailer) in `composer dev`, which needs Unix-only `pcntl`. Already removed from the dev script in this repo; tail logs manually with `Get-Content -Wait -Tail 0 storage/logs/laravel.log`. |
| `npm run dev` shows nothing in browser | Make sure `serve.bat` is running too — Vite only serves assets, the app server is separate. |

---

## Roadmap / not yet built

Tier 2+ items I haven't tackled but make sense for commercial deployment:

- 2FA on admin accounts (Filament plugin or Fortify)
- Audit log (`spatie/laravel-activitylog`) — who promoted whom, who linked whom
- Soft deletes on `Assessment` and `User` for GDPR-compliant recovery
- Translation files (lang/mk + lang/en) — strings are currently inline
- Privacy / ToS / cookie-consent pages
- WCAG 2.1 AA accessibility pass
- Sentry / Bugsnag for production error tracking
- `spatie/laravel-backup` for scheduled DB dumps
- Multi-tenancy / school scoping
- Tests for the scoring engine and invitation flow

---

## License

This codebase is built on the Laravel framework, which is MIT-licensed. SafeNet itself is proprietary unless you say otherwise.
