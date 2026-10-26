<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\MemberPaymentApprovedMail;
use App\Mail\MemberPaymentRejectedMail;
use App\Models\AuditLog;
use App\Models\GymSetting;
use App\Models\Membership;
use App\Models\Payment;
use App\Models\PaymentSubmission;
use App\Models\RenewalOrder;
use App\Notifications\PaymentApprovedNotification;
use App\Notifications\PaymentRejectedNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentRequestController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', PaymentSubmission::class);

        $query = PaymentSubmission::with(['member.user', 'plan', 'paymentMethod', 'reviewer'])->latest();

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Method filter
        if ($request->filled('method') && $request->method !== 'all') {
            $query->whereHas('paymentMethod', function ($q) use ($request) {
                $q->where('type', $request->method);
            });
        }

        // Date filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $submissions = $query->paginate(15)->withQueryString();

        // Counts for tabs/badges
        $counts = [
            'all'      => PaymentSubmission::count(),
            'pending'  => PaymentSubmission::pending()->count(),
            'approved' => PaymentSubmission::approved()->count(),
            'rejected' => PaymentSubmission::rejected()->count(),
        ];

        return view('admin.payment-requests.index', compact('submissions', 'counts'));
    }

    public function show(PaymentSubmission $submission)
    {
        Gate::authorize('view', $submission);

        $submission->load(['member.user', 'plan', 'paymentMethod', 'reviewer', 'membership']);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success'    => true,
                'submission' => $submission,
                'html'       => view('admin.payment-requests.partials.details', compact('submission'))->render(),
            ]);
        }

        return redirect()->route('admin.payment-requests.index');
    }

    public function approve(Request $request, PaymentSubmission $submission)
    {
        Gate::authorize('approve', $submission);

        $approvedSubmission = null;

        try {
            DB::transaction(function () use ($submission, &$approvedSubmission) {
                // 1. Lock the submission row for update and check status is still 'pending'
                $locked = PaymentSubmission::where('id', $submission->id)->lockForUpdate()->firstOrFail();

                if ($locked->status !== 'pending') {
                    throw new \RuntimeException('This payment request has already been processed and cannot be approved again.');
                }

                // 2. Generate unique receipt number: RCP-YYYY-XXXXXX
                $year = now()->format('Y');
                $countThisYear = PaymentSubmission::whereNotNull('receipt_number')
                    ->whereYear('reviewed_at', $year)
                    ->count() + 1;
                $receiptNumber = sprintf('RCP-%s-%06d', $year, $countThisYear);

                // Ensure strict uniqueness if parallel approvals occurred
                while (PaymentSubmission::where('receipt_number', $receiptNumber)->exists()) {
                    $countThisYear++;
                    $receiptNumber = sprintf('RCP-%s-%06d', $year, $countThisYear);
                }

                $member = $locked->member;
                $plan   = $locked->plan;

                if (!$member || !$plan) {
                    throw new \RuntimeException('Member or Membership Plan could not be found for this submission.');
                }

                // 3. Renew or extend membership according to gym rules
                $oldMs = $member->activeMembership;
                $startDate = now()->toDateString();
                
                // If existing membership is still active in the future, extend from that end date
                if ($oldMs && Carbon::parse($oldMs->end_date)->isFuture()) {
                    $endDate = Carbon::parse($oldMs->end_date)->addDays($plan->duration_days)->toDateString();
                } else {
                    $endDate = now()->addDays($plan->duration_days)->toDateString();
                }

                // Mark old active membership as expired
                if ($oldMs) {
                    $oldMs->update(['status' => 'expired']);
                }

                $newMs = Membership::create([
                    'member_id'      => $member->id,
                    'plan_id'        => $plan->id,
                    'start_date'     => $startDate,
                    'end_date'       => $endDate,
                    'status'         => 'active',
                    'amount_paid'    => $locked->amount,
                    'payment_method' => $locked->paymentMethod?->type === 'bank_transfer' ? 'bank_transfer' : 'other',
                    'notes'          => "Manual Payment Verified ({$locked->paymentMethod?->display_name}) - Trx: {$locked->transaction_reference}",
                    'created_by'     => auth()->id(),
                ]);

                // 4. Create Payment record (status: paid)
                $payment = Payment::create([
                    'member_id'      => $member->id,
                    'type'           => 'renewal',
                    'amount'         => $locked->amount,
                    'discount'       => 0,
                    'payment_method' => $locked->paymentMethod?->type === 'bank_transfer' ? 'bank_transfer' : 'other',
                    'reference_id'   => $newMs->id,
                    'reference_type' => 'Membership',
                    'status'         => 'paid',
                    'received_by'    => auth()->id(),
                    'notes'          => "Manual verification approved - Receipt: {$receiptNumber} - Trx: {$locked->transaction_reference}",
                    'paid_at'        => $locked->paid_at ?? now(),
                ]);

                // 5. Create RenewalOrder record
                $renewalOrder = RenewalOrder::create([
                    'member_id'         => $member->id,
                    'old_membership_id' => $oldMs?->id,
                    'new_membership_id' => $newMs->id,
                    'processed_by'      => auth()->id(),
                ]);

                // 6. Mark submission approved
                $locked->update([
                    'status'           => 'approved',
                    'reviewed_by'      => auth()->id(),
                    'reviewed_at'      => now(),
                    'receipt_number'   => $receiptNumber,
                    'payment_id'       => $payment->id,
                    'membership_id'    => $newMs->id,
                    'renewal_order_id' => $renewalOrder->id,
                ]);

                // 7. Stop pending expiry reminders & create in-website notification
                if ($member->user) {
                    $member->user->unreadNotifications()
                        ->where('type', 'like', '%Expiry%')
                        ->update(['read_at' => now()]);

                    $member->user->notify(new PaymentApprovedNotification($locked));
                }

                // 8. Audit log entry
                AuditLog::record('approved_payment_request', $locked, ['status' => 'pending'], [
                    'status'         => 'approved',
                    'receipt_number' => $receiptNumber,
                    'amount'         => $locked->amount,
                    'reviewed_by'    => auth()->id(),
                ]);

                $approvedSubmission = $locked;
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            Log::error('Approval failed: ' . $e->getMessage(), ['exception' => $e]);
            return back()->with('error', 'Failed to approve payment: ' . $e->getMessage());
        }

        // Queue email after database transaction has committed
        if ($approvedSubmission && $approvedSubmission->member?->user?->email) {
            try {
                Mail::to($approvedSubmission->member->user->email)
                    ->queue(new MemberPaymentApprovedMail($approvedSubmission));
            } catch (\Throwable $e) {
                Log::error('Failed to queue member approval email: ' . $e->getMessage());
            }
        }

        return back()->with('success', "Payment approved successfully! Receipt #{$approvedSubmission->receipt_number} generated.");
    }

    public function reject(Request $request, PaymentSubmission $submission)
    {
        Gate::authorize('reject', $submission);

        $request->validate([
            'rejection_reason' => 'required|string|min:3|max:500',
        ]);

        $rejectedSubmission = null;

        try {
            DB::transaction(function () use ($request, $submission, &$rejectedSubmission) {
                $locked = PaymentSubmission::where('id', $submission->id)->lockForUpdate()->firstOrFail();

                if ($locked->status !== 'pending') {
                    throw new \RuntimeException('This payment request has already been processed.');
                }

                $reason = trim($request->input('rejection_reason'));

                $locked->update([
                    'status'           => 'rejected',
                    'rejection_reason' => $reason,
                    'reviewed_by'      => auth()->id(),
                    'reviewed_at'      => now(),
                ]);

                // In-website notification
                if ($locked->member?->user) {
                    $locked->member->user->notify(new PaymentRejectedNotification($locked));
                }

                // Audit log
                AuditLog::record('rejected_payment_request', $locked, ['status' => 'pending'], [
                    'status'           => 'rejected',
                    'rejection_reason' => $reason,
                    'reviewed_by'      => auth()->id(),
                ]);

                $rejectedSubmission = $locked;
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            Log::error('Rejection failed: ' . $e->getMessage(), ['exception' => $e]);
            return back()->with('error', 'Failed to reject payment: ' . $e->getMessage());
        }

        // Queue rejection email after transaction commit
        if ($rejectedSubmission && $rejectedSubmission->member?->user?->email) {
            try {
                Mail::to($rejectedSubmission->member->user->email)
                    ->queue(new MemberPaymentRejectedMail($rejectedSubmission));
            } catch (\Throwable $e) {
                Log::error('Failed to queue member rejection email: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Payment request was rejected. The member has been notified.');
    }

    public function receipt(PaymentSubmission $submission)
    {
        Gate::authorize('downloadReceipt', $submission);

        $submission->load(['member.user', 'plan', 'paymentMethod', 'reviewer', 'membership']);
        $gymSettings = GymSetting::pluck('value', 'key');

        return view('receipts.show', compact('submission', 'gymSettings'));
    }

    public function receiptPdf(PaymentSubmission $submission)
    {
        Gate::authorize('downloadReceipt', $submission);

        $submission->load(['member.user', 'plan', 'paymentMethod', 'reviewer', 'membership']);
        $gymSettings = GymSetting::pluck('value', 'key');

        $pdf = Pdf::loadView('receipts.pdf', compact('submission', 'gymSettings'));

        return $pdf->download("Receipt-{$submission->receipt_number}.pdf");
    }
}
