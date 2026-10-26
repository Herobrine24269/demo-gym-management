<?php

namespace App\Mail;

use App\Models\GymSetting;
use App\Models\PaymentSubmission;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MemberPaymentApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public PaymentSubmission $submission)
    {
    }

    public function envelope(): Envelope
    {
        $gymName = GymSetting::get('gym_name', 'Kinetic Apex');

        return new Envelope(
            subject: "Payment received, membership renewed - {$gymName}",
        );
    }

    public function content(): Content
    {
        $gymSettings = GymSetting::pluck('value', 'key');

        return new Content(
            view: 'emails.member-payment-approved',
            with: [
                'submission'  => $this->submission,
                'gymSettings' => $gymSettings,
            ],
        );
    }

    public function attachments(): array
    {
        $gymSettings = GymSetting::pluck('value', 'key');
        $pdf = Pdf::loadView('receipts.pdf', [
            'submission'  => $this->submission,
            'gymSettings' => $gymSettings,
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), "Receipt-{$this->submission->receipt_number}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
