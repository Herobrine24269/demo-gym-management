<?php

namespace App\Notifications;

use App\Models\PaymentSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(public PaymentSubmission $submission)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $planName = $this->submission->plan?->name ?? 'Membership Plan';
        $amount   = number_format($this->submission->amount, 0);

        return [
            'type'           => 'payment_approved',
            'title'          => 'Payment Approved & Membership Renewed',
            'submission_id'  => $this->submission->id,
            'receipt_number' => $this->submission->receipt_number,
            'amount'         => (float) $this->submission->amount,
            'plan_name'      => $planName,
            'message'        => "Your payment of Rs. {$amount} for {$planName} was verified and approved! Receipt: {$this->submission->receipt_number}",
            'link'           => route('member.payments.index'),
        ];
    }
}
