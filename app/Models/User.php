<?php


namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isStudent(): bool { return $this->role === 'student'; }
    public function isParent(): bool  { return $this->role === 'parent'; }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    public function assessmentsFor()
    {
        return $this->hasMany(Assessment::class, 'filled_for_user_id');
    }

    /**
     * Assessments about $child that *this* user (a parent) is allowed to see:
     * only the ones they submitted themselves, plus the child's own self-submission.
     * Other parents' submissions about the same child are hidden for privacy.
     */
    public function visibleAssessmentsAbout(User $child)
    {
        return $child->assessmentsFor()->where(function ($q) use ($child) {
            $q->where('user_id', $this->id)        // assessments I filled
              ->orWhere('user_id', $child->id);    // child's own self-submission
        });
    }

    public function sentInvitations()
    {
        return $this->hasMany(ParentChildInvitation::class, 'parent_id')->latest();
    }

    public function pendingInvitationsForMe()
    {
        return $this->hasMany(ParentChildInvitation::class, 'child_user_id')
            ->where('status', ParentChildInvitation::STATUS_PENDING)
            ->latest();
    }

    public function children()
    {
        return $this->belongsToMany(User::class, 'parent_student', 'parent_id', 'student_id');
    }

    public function parents()
    {
        return $this->belongsToMany(User::class, 'parent_student', 'student_id', 'parent_id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }
}
