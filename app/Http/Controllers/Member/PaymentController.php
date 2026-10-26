<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentSubmission;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index()
    {
        $member = auth()->user()->member;

        if (!$member) {
            return redirect()->route('home')->with('error', 'Member profile not found.');
        }

        $submissions = PaymentSubmission::where('member_id', $member->id)
            ->with(['plan', 'paymentMethod'])
            ->latest()
            ->get();

        $payments  = Payment::where('member_id', $member->id)->latest('paid_at')->get();
        $totalPaid = Payment::where('member_id', $member->id)->where('status', 'paid')->sum('amount');

        return view('member.payments.index', compact('member', 'submissions', 'payments', 'totalPaid'));
    }

    public function viewScreenshot(PaymentSubmission $submission)
    {
        $user = auth()->user();

        // Ensure user is the submission owner or admin
        if ($submission->member_id !== $user->member?->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized access to payment screenshot.');
        }

        if (!Storage::disk('local')->exists($submission->screenshot_path)) {
            abort(404, 'Payment proof file not found on server.');
        }

        $fullPath = Storage::disk('local')->path($submission->screenshot_path);
        $mime = mime_content_type($fullPath) ?: 'application/octet-stream';

        return response()->file($fullPath, [
            'Content-Type'        => $mime,
            'Content-Disposition' => 'inline; filename="payment-proof-' . $submission->id . '"',
            'Cache-Control'       => 'no-store, private',
        ]);
    }
}
