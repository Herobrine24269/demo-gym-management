<?php

namespace Tests\Feature;

use App\Mail\AdminPaymentSubmittedMail;
use App\Mail\MemberPaymentApprovedMail;
use App\Mail\MemberPaymentConfirmationMail;
use App\Mail\MemberPaymentRejectedMail;
use App\Models\GymSetting;
use App\Models\Member;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentSubmission;
use App\Models\User;
use App\Notifications\PaymentApprovedNotification;
use App\Notifications\PaymentRejectedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ManualPaymentReviewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Storage::fake('local');
    }

    public function test_admin_email_and_member_confirmation_are_queued_on_submission(): void
    {
        Mail::fake();

        $method = PaymentMethod::where('type', 'easypaisa')->first();
        $method->update(['is_enabled' => true, 'account_name' => 'Kinetic Apex', 'phone' => '03001234567']);
        $plan = MembershipPlan::first();

        $memberUser = User::where('role', 'member')->first();

        $file = UploadedFile::fake()->create('proof.jpg', 200, 'image/jpeg');

        $response = $this->actingAs($memberUser)->post(route('member.renewals.store'), [
            'membership_plan_id'    => $plan->id,
            'payment_method_id'     => $method->id,
            'amount'                => $plan->price,
            'transaction_reference' => 'EMAIL-TRX-101',
            'paid_at'               => now()->format('Y-m-d H:i:s'),
            'screenshot'            => $file,
        ]);

        $response->assertRedirect(route('member.payments.index'));

        // Assert admin notification queued
        $alertEmail = GymSetting::get('payment_alert_email') ?: User::where('role', 'admin')->value('email');
        Mail::assertQueued(AdminPaymentSubmittedMail::class, function ($mail) use ($alertEmail) {
            return $mail->hasTo($alertEmail) && $mail->submission->transaction_reference === 'EMAIL-TRX-101';
        });

        // Assert member confirmation queued
        Mail::assertQueued(MemberPaymentConfirmationMail::class, function ($mail) use ($memberUser) {
            return $mail->hasTo($memberUser->email);
        });
    }

    public function test_receptionist_access_controlled_by_gym_settings(): void
    {
        $receptionist = User::where('role', 'receptionist')->first();
        if (!$receptionist) {
            $receptionist = User::factory()->create(['role' => 'receptionist', 'email' => 'rec@demo.com']);
        }

        // By default, receptionist cannot view payment requests
        GymSetting::set('receptionist_can_view_payment_requests', '0');
        $response = $this->actingAs($receptionist)->get(route('admin.payment-requests.index'));
        $response->assertStatus(403);

        // When setting enabled, receptionist can view requests list
        GymSetting::set('receptionist_can_view_payment_requests', '1');
        $responseAllowed = $this->actingAs($receptionist)->get(route('admin.payment-requests.index'));
        $responseAllowed->assertStatus(200);

        // However, receptionist can NEVER approve or reject
        $method = PaymentMethod::first();
        $plan = MembershipPlan::first();
        $member = Member::first();

        $submission = PaymentSubmission::create([
            'member_id'             => $member->id,
            'membership_plan_id'    => $plan->id,
            'payment_method_id'     => $method->id,
            'amount'                => 5000,
            'transaction_reference' => 'REC-ATTEMPT-1',
            'paid_at'               => now(),
            'screenshot_path'       => 'payment_proofs/test.jpg',
            'status'                => 'pending',
        ]);

        $approveResponse = $this->actingAs($receptionist)->post(route('admin.payment-requests.approve', $submission));
        $approveResponse->assertStatus(403);

        $rejectResponse = $this->actingAs($receptionist)->post(route('admin.payment-requests.reject', $submission), [
            'rejection_reason' => 'Unauthorized attempt',
        ]);
        $rejectResponse->assertStatus(403);
    }

    public function test_approve_renews_membership_once_generates_receipt_and_notifies(): void
    {
        Mail::fake();

        $admin = User::where('role', 'admin')->first();
        $memberUser = User::where('role', 'member')->first();
        $member = $memberUser->member;
        $plan = MembershipPlan::first();
        $method = PaymentMethod::first();

        // Old membership
        $oldMembership = Membership::create([
            'member_id'      => $member->id,
            'plan_id'        => $plan->id,
            'start_date'     => now()->subDays(25)->toDateString(),
            'end_date'       => now()->addDays(5)->toDateString(),
            'status'         => 'active',
            'amount_paid'    => 5000,
            'payment_method' => 'cash',
            'created_by'     => $admin->id,
        ]);

        $submission = PaymentSubmission::create([
            'member_id'             => $member->id,
            'membership_plan_id'    => $plan->id,
            'payment_method_id'     => $method->id,
            'amount'                => 5000,
            'transaction_reference' => 'APPROVE-TRX-777',
            'paid_at'               => now(),
            'screenshot_path'       => 'payment_proofs/proof.jpg',
            'status'                => 'pending',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.payment-requests.approve', $submission));
        $response->assertSessionHas('success');

        $submission->refresh();

        // 1. Status approved & receipt generated
        $this->assertEquals('approved', $submission->status);
        $this->assertNotNull($submission->receipt_number);
        $this->assertStringStartsWith('RCP-', $submission->receipt_number);
        $this->assertEquals($admin->id, $submission->reviewed_by);

        // 2. Old membership expired
        $oldMembership->refresh();
        $this->assertEquals('expired', $oldMembership->status);

        // 3. New active membership created
        $newMembership = $submission->membership;
        $this->assertNotNull($newMembership);
        $this->assertEquals('active', $newMembership->status);
        $this->assertEquals($plan->id, $newMembership->plan_id);

        // 4. Payment record created
        $payment = $submission->payment;
        $this->assertNotNull($payment);
        $this->assertEquals('paid', $payment->status);
        $this->assertEquals(5000, (float) $payment->amount);

        // 5. In-website notification created
        $this->assertTrue(
            $memberUser->notifications()->where('type', PaymentApprovedNotification::class)->exists()
        );

        // 6. Approval email queued
        Mail::assertQueued(MemberPaymentApprovedMail::class, function ($mail) use ($memberUser) {
            return $mail->hasTo($memberUser->email);
        });
    }

    public function test_approve_called_twice_blocks_duplicate_processing(): void
    {
        $admin = User::where('role', 'admin')->first();
        $member = Member::first();
        $plan = MembershipPlan::first();
        $method = PaymentMethod::first();

        $submission = PaymentSubmission::create([
            'member_id'             => $member->id,
            'membership_plan_id'    => $plan->id,
            'payment_method_id'     => $method->id,
            'amount'                => 5000,
            'transaction_reference' => 'TRX-DOUBLE-1',
            'paid_at'               => now(),
            'screenshot_path'       => 'payment_proofs/proof.jpg',
            'status'                => 'pending',
        ]);

        // First approval succeeds
        $first = $this->actingAs($admin)->post(route('admin.payment-requests.approve', $submission));
        $first->assertSessionHas('success');

        $membershipCountBefore = Membership::where('member_id', $member->id)->count();

        // Second approval attempt immediately fails
        $second = $this->actingAs($admin)->post(route('admin.payment-requests.approve', $submission));
        $second->assertSessionHas('error');

        // Confirm no duplicate membership created
        $this->assertEquals($membershipCountBefore, Membership::where('member_id', $member->id)->count());
    }

    public function test_reject_requires_reason_and_does_not_renew(): void
    {
        Mail::fake();

        $admin = User::where('role', 'admin')->first();
        $memberUser = User::where('role', 'member')->first();
        $member = $memberUser->member;
        $plan = MembershipPlan::first();
        $method = PaymentMethod::first();

        $submission = PaymentSubmission::create([
            'member_id'             => $member->id,
            'membership_plan_id'    => $plan->id,
            'payment_method_id'     => $method->id,
            'amount'                => 5000,
            'transaction_reference' => 'TRX-REJECT-99',
            'paid_at'               => now(),
            'screenshot_path'       => 'payment_proofs/proof.jpg',
            'status'                => 'pending',
        ]);

        // Validation error if reason missing
        $failResponse = $this->actingAs($admin)->post(route('admin.payment-requests.reject', $submission), [
            'rejection_reason' => '',
        ]);
        $failResponse->assertSessionHasErrors('rejection_reason');

        // Valid rejection
        $response = $this->actingAs($admin)->post(route('admin.payment-requests.reject', $submission), [
            'rejection_reason' => 'Amount transferred does not match plan price.',
        ]);
        $response->assertSessionHas('success');

        $submission->refresh();
        $this->assertEquals('rejected', $submission->status);
        $this->assertEquals('Amount transferred does not match plan price.', $submission->rejection_reason);

        // In-website notification created
        $this->assertTrue(
            $memberUser->notifications()->where('type', PaymentRejectedNotification::class)->exists()
        );

        // Member rejection email queued
        Mail::assertQueued(MemberPaymentRejectedMail::class, function ($mail) use ($memberUser) {
            return $mail->hasTo($memberUser->email);
        });
    }

    public function test_member_cannot_open_another_members_receipt(): void
    {
        $admin = User::where('role', 'admin')->first();
        $plan = MembershipPlan::first();
        $method = PaymentMethod::first();

        $member1 = Member::first();
        $member2 = Member::skip(1)->first();

        $submission = PaymentSubmission::create([
            'member_id'             => $member1->id,
            'membership_plan_id'    => $plan->id,
            'payment_method_id'     => $method->id,
            'amount'                => 5000,
            'transaction_reference' => 'TRX-RECEIPT-PRIV',
            'paid_at'               => now(),
            'screenshot_path'       => 'payment_proofs/proof.jpg',
            'status'                => 'approved',
            'receipt_number'        => 'RCP-2026-999001',
            'reviewed_by'           => $admin->id,
            'reviewed_at'           => now(),
        ]);

        // Member 2 cannot view Member 1's receipt
        $responseForbidden = $this->actingAs($member2->user)->get(route('member.payments.receipt', $submission));
        $responseForbidden->assertStatus(403);

        // Member 2 cannot download Member 1's PDF
        $responsePdfForbidden = $this->actingAs($member2->user)->get(route('member.payments.receipt-pdf', $submission));
        $responsePdfForbidden->assertStatus(403);

        // Member 1 can view own receipt
        $responseOwner = $this->actingAs($member1->user)->get(route('member.payments.receipt', $submission));
        $responseOwner->assertStatus(200);

        // Member 1 can download own PDF
        $responseOwnerPdf = $this->actingAs($member1->user)->get(route('member.payments.receipt-pdf', $submission));
        $responseOwnerPdf->assertStatus(200);
        $this->assertEquals('application/pdf', $responseOwnerPdf->headers->get('content-type'));
    }
}
