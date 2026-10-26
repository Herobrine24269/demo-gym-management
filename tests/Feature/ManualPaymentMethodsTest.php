<?php

namespace Tests\Feature;

use App\Models\GymSetting;
use App\Models\Member;
use App\Models\MembershipPlan;
use App\Models\PaymentMethod;
use App\Models\PaymentSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ManualPaymentMethodsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Storage::fake('local');
        Storage::fake('public');
    }

    public function test_admin_can_access_payment_methods_page(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get(route('admin.payment-methods.index'));

        $response->assertStatus(200);
        $response->assertSee('Manual Payment Methods');
        $response->assertSee('Easypaisa');
        $response->assertSee('JazzCash');
        $response->assertSee('Bank Transfer');
    }

    public function test_member_cannot_access_admin_payment_methods_page(): void
    {
        $memberUser = User::where('role', 'member')->first();
        $this->assertNotNull($memberUser);

        $response = $this->actingAs($memberUser)->get(route('admin.payment-methods.index'));

        // Role middleware redirects or aborts 403
        $this->assertTrue(in_array($response->getStatusCode(), [302, 403]));
    }

    public function test_admin_can_save_payment_methods_with_valid_data(): void
    {
        $admin = User::where('role', 'admin')->first();

        $qrFile = UploadedFile::fake()->create('easypaisa-qr.jpg', 200, 'image/jpeg');

        $response = $this->actingAs($admin)->put(route('admin.payment-methods.update'), [
            'payment_alert_email' => 'alerts@kineticapex.com',
            'methods' => [
                'easypaisa' => [
                    'is_enabled'   => '1',
                    'account_name' => 'Kinetic Apex Admin',
                    'phone'        => '03001234567',
                    'qr_image'     => $qrFile,
                    'instructions' => 'Send exact amount and keep slip.',
                ],
                'jazzcash' => [
                    'is_enabled'   => '0',
                    'account_name' => '',
                    'phone'        => '',
                    'instructions' => '',
                ],
                'bank_transfer' => [
                    'is_enabled'     => '0',
                    'bank_name'      => '',
                    'account_name'   => '',
                    'account_number' => '',
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.payment-methods.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('payment_methods', [
            'type'         => 'easypaisa',
            'is_enabled'   => true,
            'account_name' => 'Kinetic Apex Admin',
            'phone'        => '03001234567',
        ]);

        $this->assertEquals('alerts@kineticapex.com', GymSetting::get('payment_alert_email'));
    }

    public function test_admin_validation_fails_when_enabled_method_has_invalid_phone(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->put(route('admin.payment-methods.update'), [
            'payment_alert_email' => 'alerts@kineticapex.com',
            'methods' => [
                'easypaisa' => [
                    'is_enabled'   => '1',
                    'account_name' => 'Kinetic Apex',
                    'phone'        => '12345', // invalid phone
                ],
            ],
        ]);

        $response->assertSessionHasErrors('methods.easypaisa.phone');
    }

    public function test_only_enabled_methods_show_to_members(): void
    {
        PaymentMethod::where('type', 'easypaisa')->update([
            'is_enabled'   => true,
            'account_name' => 'Apex Easypaisa',
            'phone'        => '03001112233',
        ]);
        PaymentMethod::where('type', 'jazzcash')->update(['is_enabled' => false]);
        PaymentMethod::where('type', 'bank_transfer')->update(['is_enabled' => false]);

        $memberUser = User::where('role', 'member')->first();

        $response = $this->actingAs($memberUser)->get(route('member.renewals.index'));

        $response->assertStatus(200);
        $response->assertSee('Apex Easypaisa');
        $response->assertSee('03001112233');
        $response->assertDontSee('Blue Area Branch'); // Bank transfer is disabled
    }

    public function test_member_can_submit_payment_with_valid_data_and_screenshot(): void
    {
        $method = PaymentMethod::where('type', 'easypaisa')->first();
        $method->update([
            'is_enabled'   => true,
            'account_name' => 'Kinetic Apex',
            'phone'        => '03001234567',
        ]);

        $plan = MembershipPlan::first();
        $memberUser = User::where('role', 'member')->first();
        $member = $memberUser->member;

        $screenshot = UploadedFile::fake()->create('payment_receipt.jpg', 300, 'image/jpeg');

        $response = $this->actingAs($memberUser)->post(route('member.renewals.store'), [
            'membership_plan_id'    => $plan->id,
            'payment_method_id'     => $method->id,
            'amount'                => $plan->price,
            'transaction_reference' => 'TRX-100293847',
            'paid_at'               => now()->format('Y-m-d H:i:s'),
            'screenshot'            => $screenshot,
            'note'                  => 'Paid via Easypaisa mobile app.',
        ]);

        $response->assertRedirect(route('member.payments.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('payment_submissions', [
            'member_id'             => $member->id,
            'membership_plan_id'    => $plan->id,
            'payment_method_id'     => $method->id,
            'transaction_reference' => 'TRX-100293847',
            'status'                => 'pending',
        ]);

        $submission = PaymentSubmission::where('transaction_reference', 'TRX-100293847')->first();
        $this->assertNotNull($submission);
        Storage::disk('local')->assertExists($submission->screenshot_path);
    }

    public function test_member_cannot_submit_duplicate_transaction_id(): void
    {
        $method = PaymentMethod::where('type', 'easypaisa')->first();
        $method->update(['is_enabled' => true, 'account_name' => 'Kinetic Apex', 'phone' => '03001234567']);
        $plan = MembershipPlan::first();

        $member1 = Member::first();
        $member2 = Member::skip(1)->first();

        PaymentSubmission::create([
            'member_id'             => $member1->id,
            'membership_plan_id'    => $plan->id,
            'payment_method_id'     => $method->id,
            'amount'                => 5000,
            'transaction_reference' => 'DUP-TRX-999',
            'paid_at'               => now(),
            'screenshot_path'       => 'payment_proofs/test.jpg',
            'status'                => 'pending',
        ]);

        $response = $this->actingAs($member2->user)->post(route('member.renewals.store'), [
            'membership_plan_id'    => $plan->id,
            'payment_method_id'     => $method->id,
            'amount'                => 5000,
            'transaction_reference' => 'DUP-TRX-999',
            'paid_at'               => now()->format('Y-m-d H:i:s'),
            'screenshot'            => UploadedFile::fake()->create('receipt.jpg', 300, 'image/jpeg'),
        ]);

        $response->assertSessionHasErrors('transaction_reference');
    }

    public function test_member_cannot_submit_second_pending_request_for_renewal(): void
    {
        $method = PaymentMethod::where('type', 'easypaisa')->first();
        $method->update(['is_enabled' => true, 'account_name' => 'Kinetic Apex', 'phone' => '03001234567']);
        $plan = MembershipPlan::first();

        $memberUser = User::where('role', 'member')->first();
        $member = $memberUser->member;

        // Existing pending submission
        PaymentSubmission::create([
            'member_id'             => $member->id,
            'membership_plan_id'    => $plan->id,
            'payment_method_id'     => $method->id,
            'amount'                => 5000,
            'transaction_reference' => 'FIRST-TRX-123',
            'paid_at'               => now(),
            'screenshot_path'       => 'payment_proofs/first.jpg',
            'status'                => 'pending',
        ]);

        $response = $this->actingAs($memberUser)->post(route('member.renewals.store'), [
            'membership_plan_id'    => $plan->id,
            'payment_method_id'     => $method->id,
            'amount'                => 5000,
            'transaction_reference' => 'SECOND-TRX-456',
            'paid_at'               => now()->format('Y-m-d H:i:s'),
            'screenshot'            => UploadedFile::fake()->create('second.jpg', 300, 'image/jpeg'),
        ]);

        $response->assertRedirect(route('member.payments.index'));
        $response->assertSessionHas('error');
    }

    public function test_member_cannot_view_another_members_screenshot(): void
    {
        $method = PaymentMethod::where('type', 'easypaisa')->first();
        $plan = MembershipPlan::first();

        $member1 = Member::first();
        $member2 = Member::skip(1)->first();

        $fakeFile = UploadedFile::fake()->create('proof.jpg', 300, 'image/jpeg');
        $path = $fakeFile->store('payment_proofs', 'local');

        $submission = PaymentSubmission::create([
            'member_id'             => $member1->id,
            'membership_plan_id'    => $plan->id,
            'payment_method_id'     => $method->id,
            'amount'                => 5000,
            'transaction_reference' => 'TRX-PRIVATE-1',
            'paid_at'               => now(),
            'screenshot_path'       => $path,
            'status'                => 'pending',
        ]);

        // Member 2 attempts to view Member 1's screenshot
        $response = $this->actingAs($member2->user)->get(route('member.payment-submissions.screenshot', $submission));
        $response->assertStatus(403);

        // Member 1 can view own screenshot
        $responseOwner = $this->actingAs($member1->user)->get(route('member.payment-submissions.screenshot', $submission));
        $responseOwner->assertStatus(200);

        // Admin can view screenshot
        $admin = User::where('role', 'admin')->first();
        $responseAdmin = $this->actingAs($admin)->get(route('member.payment-submissions.screenshot', $submission));
        $responseAdmin->assertStatus(200);
    }
}
