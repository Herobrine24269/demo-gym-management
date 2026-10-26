<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSubmission extends Model
{
    protected $fillable = [
        'member_id', 'membership_plan_id', 'renewal_order_id',
        'payment_method_id', 'payment_id', 'membership_id',
        'amount', 'transaction_reference', 'paid_at',
        'screenshot_path', 'note', 'status',
        'reviewed_by', 'reviewed_at', 'rejection_reason', 'receipt_number',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'paid_at'     => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function plan()
    {
        return $this->belongsTo(MembershipPlan::class, 'membership_plan_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function renewalOrder()
    {
        return $this->belongsTo(RenewalOrder::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
