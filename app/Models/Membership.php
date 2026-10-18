<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membership extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'member_id', 'plan_id', 'start_date', 'end_date',
        'status', 'amount_paid', 'payment_method', 'notes', 'created_by',
    ];

    protected $casts = [
        'start_date'   => 'date',
        'end_date'     => 'date',
        'amount_paid'  => 'decimal:2',
    ];

    public function member()  { return $this->belongsTo(Member::class); }
    public function plan()    { return $this->belongsTo(MembershipPlan::class, 'plan_id'); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }

    public function daysRemaining(): int
    {
        return max(0, now()->diffInDays($this->end_date, false));
    }

    public function isExpired(): bool  { return $this->status === 'expired' || now()->gt($this->end_date); }
    public function isActive(): bool   { return $this->status === 'active' && now()->lte($this->end_date); }
    public function isExpiringSoon(): bool { return $this->isActive() && $this->daysRemaining() <= 7; }

    /* ── Scopes ──────────────────────────────── */
    public function scopeActive($q)        { return $q->where('status', 'active'); }
    public function scopeExpiringSoon($q)  {
        return $q->where('status', 'active')
                 ->where('end_date', '<=', now()->addDays(7))
                 ->where('end_date', '>=', now());
    }
}
