<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'member_id', 'type', 'amount', 'discount', 'payment_method',
        'reference_id', 'reference_type', 'status', 'received_by', 'notes', 'paid_at',
    ];

    protected $casts = [
        'amount'   => 'decimal:2',
        'discount' => 'decimal:2',
        'paid_at'  => 'datetime',
    ];

    public function member()   { return $this->belongsTo(Member::class); }
    public function receiver() { return $this->belongsTo(User::class, 'received_by'); }

    public function netAmount(): float { return (float)$this->amount - (float)$this->discount; }

    public function scopePaid($q)     { return $q->where('status', 'paid'); }
    public function scopeThisMonth($q){ return $q->whereMonth('paid_at', now()->month)->whereYear('paid_at', now()->year); }
    public function scopeToday($q)    { return $q->whereDate('paid_at', today()); }
}
