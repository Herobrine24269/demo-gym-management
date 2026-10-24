<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'avatar',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /* ── Role helpers ─────────────────────────── */
    public function isAdmin(): bool        { return $this->role === 'admin'; }
    public function isReceptionist(): bool { return $this->role === 'receptionist'; }
    public function isTrainer(): bool      { return $this->role === 'trainer'; }
    public function isMember(): bool       { return $this->role === 'member'; }
    public function isStaff(): bool        { return in_array($this->role, ['admin', 'receptionist']); }

    /* ── Relationships ────────────────────────── */
    public function member()  { return $this->hasOne(Member::class); }
    public function trainer() { return $this->hasOne(Trainer::class); }

    public function markedAttendance() {
        return $this->hasMany(Attendance::class, 'marked_by');
    }
    public function receivedPayments() {
        return $this->hasMany(Payment::class, 'received_by');
    }
    public function auditLogs() {
        return $this->hasMany(AuditLog::class);
    }
    public function posts() {
        return $this->hasMany(Post::class, 'author_id');
    }

    /* ── Dashboard redirect ───────────────────── */
    public function dashboardRoute(): string
    {
        return match($this->role) {
            'admin'        => route('admin.dashboard'),
            'receptionist' => route('receptionist.dashboard'),
            'trainer'      => route('trainer.dashboard'),
            default        => route('member.dashboard'),
        };
    }

    /* ── Initials for avatar fallback ─────────── */
    public function initials(): string
    {
        $words = explode(' ', $this->name);
        return strtoupper(
            collect($words)->take(2)->map(fn($w) => $w[0] ?? '')->implode('')
        );
    }

    /* ── Profile Picture URL ──────────────────── */
    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar) {
            return null;
        }
        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }
        return asset('storage/' . $this->avatar);
    }
}
