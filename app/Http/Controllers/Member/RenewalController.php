<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitPaymentRequest;
use App\Models\AuditLog;
use App\Models\MembershipPlan;
use App\Models\PaymentMethod;
use App\Models\PaymentSubmission;
use Illuminate\Http\Request;

class RenewalController extends Controller
{
    public function index(Request $request)
    {
        $member = auth()->user()->member;

        if (!$member) {
            return redirect()->route('home')->with('error', 'Member profile not found.');
        }

        $activeMembership  = $member->activeMembership;
        $plans             = MembershipPlan::where('is_active', true)->orderBy('price')->get();
        $selectedPlanId    = (int) $request->query('plan_id', $plans->first()?->id);
        $selectedPlan      = $plans->firstWhere('id', $selectedPlanId) ?? $plans->first();
        $enabledMethods    = PaymentMethod::enabled()->get();
        $pendingSubmission = PaymentSubmission::where('member_id', $member->id)
            ->pending()
            ->with('plan', 'paymentMethod')
            ->latest()
            ->first();

        return view('member.renewals.index', compact(
            'member',
            'activeMembership',
            'plans',
            'selectedPlan',
            'enabledMethods',
            'pendingSubmission'
        ));
    }

    public function store(SubmitPaymentRequest $request)
    {
        $member = auth()->user()->member;

        if (!$member) {
            return redirect()->route('home')->with('error', 'Member profile not found.');
        }

        // Block duplicate pending submissions
        if (PaymentSubmission::where('member_id', $member->id)->pending()->exists()) {
            return redirect()->route('member.payments.index')
                ->with('error', 'You already have a pending payment under review. Please wait for the admin to check it.');
        }

        // Store screenshot in PRIVATE local storage (not public)
        $path = $request->file('screenshot')->store('payment_proofs', 'local');

        $submission = PaymentSubmission::create([
            'member_id'             => $member->id,
            'membership_plan_id'    => $request->input('membership_plan_id'),
            'payment_method_id'     => $request->input('payment_method_id'),
            'amount'                => $request->input('amount'),
            'transaction_reference' => trim($request->input('transaction_reference')),
            'paid_at'               => $request->input('paid_at'),
            'screenshot_path'       => $path,
            'note'                  => $request->input('note'),
            'status'                => 'pending',
        ]);

        AuditLog::record('submitted_payment_proof', $submission);

        // Queue notifications
        try {
            $alertEmail = \App\Models\GymSetting::get('payment_alert_email') 
                ?: \App\Models\User::where('role', 'admin')->value('email');
            
            if ($alertEmail) {
                \Illuminate\Support\Facades\Mail::to($alertEmail)->queue(new \App\Mail\AdminPaymentSubmittedMail($submission));
            }

            if ($member->user?->email) {
                \Illuminate\Support\Facades\Mail::to($member->user->email)->queue(new \App\Mail\MemberPaymentConfirmationMail($submission));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to queue payment submission email: ' . $e->getMessage());
        }

        return redirect()->route('member.payments.index')
            ->with('success', 'Payment submitted. We will check it and renew your membership soon.');
    }
}
