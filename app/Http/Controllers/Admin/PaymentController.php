<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Member;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('member.user', 'receiver');

        if ($q = $request->get('q')) {
            $query->whereHas('member.user', fn($sq) => $sq->where('name', 'like', "%{$q}%"));
        }
        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($month = $request->get('month')) {
            $query->whereMonth('paid_at', $month);
        }

        $payments       = $query->latest('paid_at')->paginate(30)->withQueryString();
        $totalThisMonth = Payment::paid()->thisMonth()->sum(DB::raw('amount - discount'));
        $totalToday     = Payment::paid()->today()->sum(DB::raw('amount - discount'));

        return view('admin.payments.index', compact('payments', 'totalThisMonth', 'totalToday'));
    }

    public function show(Payment $payment)
    {
        $payment->load('member.user', 'receiver');
        return view('admin.payments.show', compact('payment'));
    }

    public function refund(Request $request, Payment $payment)
    {
        $before = $payment->toArray();
        $payment->update(['status' => 'refunded']);
        AuditLog::record('payment_refunded', $payment, $before, $payment->fresh()->toArray());
        return back()->with('success', 'Payment marked as refunded.');
    }
}
