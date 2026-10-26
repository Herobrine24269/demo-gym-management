<?php

namespace App\Mail;

use App\Models\GymSetting;
use App\Models\PaymentSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminPaymentSubmittedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public PaymentSubmission $submission)
    {
    }

    public function envelope(): Envelope
    {
        $memberName = $this->submission->member?->user?->name ?? 'Member';

        return new Envelope(
            subject: "New payment submitted - {$memberName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-payment-submitted',
            with: [
                'submission' => $this->submission,
                'gymName'    => GymSetting::get('gym_name', 'Kinetic Apex'),
                'reviewUrl'  => route('admin.payment-requests.index'),
            ],
        );
    }
}
