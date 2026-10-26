<?php

namespace App\Notifications;

use App\Models\PaymentSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentRejectedNotification extends Notification
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
        $reason   = $this->submission->rejection_reason ?: 'Invalid payment receipt details.';

        return [
            'type'           => 'payment_rejected',
            'title'          => 'Payment Could Not Be Verified',
            'submission_id'  => $this->submission->id,
            'amount'         => (float) $this->submission->amount,
            'plan_name'      => $planName,
            'reason'         => $reason,
            'message'        => "Your payment submission for {$planName} could not be verified. Reason: {$reason}. You may submit again with correct proof.",
            'link'           => route('member.renewals.index'),
        ];
    }
}
