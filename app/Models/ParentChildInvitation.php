<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ParentChildInvitation extends Model
{
    protected $table = 'parent_student_invitations';

    protected $fillable = [
        'parent_id',
        'child_email',
        'child_user_id',
        'token',
        'status',
        'expires_at',
        'accepted_at',
        'declined_at',
    ];

    protected $casts = [
        'expires_at'   => 'datetime',
        'accepted_at'  => 'datetime',
        'declined_at'  => 'datetime',
    ];

    public const STATUS_PENDING   = 'pending';
    public const STATUS_ACCEPTED  = 'accepted';
    public const STATUS_DECLINED  = 'declined';
    public const STATUS_EXPIRED   = 'expired';
    public const STATUS_CANCELLED = 'cancelled';

    public const DEFAULT_TTL_DAYS = 7;

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function child()
    {
        return $this->belongsTo(User::class, 'child_user_id');
    }

    public static function generateToken(): string
    {
        do {
            $token = Str::random(48);
        } while (static::where('token', $token)->exists());

        return $token;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING && ! $this->hasExpired();
    }

    public function hasExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function markAccepted(?User $child = null): void
    {
        $this->update([
            'status'        => self::STATUS_ACCEPTED,
            'accepted_at'   => now(),
            'child_user_id' => $child?->id ?? $this->child_user_id,
        ]);
    }

    public function markDeclined(): void
    {
        $this->update([
            'status'       => self::STATUS_DECLINED,
            'declined_at'  => now(),
        ]);
    }

    public function markCancelled(): void
    {
        $this->update(['status' => self::STATUS_CANCELLED]);
    }
}
