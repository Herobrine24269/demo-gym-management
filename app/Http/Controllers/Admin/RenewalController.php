<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\Payment;
use App\Models\RenewalOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RenewalController extends Controller
{
    public function index()
    {
        $expiringSoon = Membership::expiringSoon()
            ->with('member.user', 'plan')
            ->orderBy('end_date')
            ->paginate(20);

        $expiredRecentlyMs = Membership::where('status', 'expired')
            ->where('end_date', '>=', now()->subDays(30))
            ->with('member.user', 'plan')
            ->orderByDesc('end_date')
            ->take(15)
            ->get();

        $plans   = MembershipPlan::where('is_active', true)->get();
        $members = Member::with('user', 'activeMembership.plan')->latest()->get();

        return view('admin.renewals.index', compact('expiringSoon', 'expiredRecentlyMs', 'plans', 'members'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'member_id'      => 'required|exists:members,id',
            'plan_id'        => 'required|exists:membership_plans,id',
            'amount_paid'    => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,bank_transfer,other',
        ]);

        DB::transaction(function () use ($data) {
            $member = Member::findOrFail($data['member_id']);
            $plan   = MembershipPlan::findOrFail($data['plan_id']);

            // Expire old active membership
            $oldMs = $member->activeMembership;
            if ($oldMs) {
                $oldMs->update(['status' => 'expired']);
            }

            // Create new membership
            $startDate = now()->toDateString();
            $endDate   = now()->addDays($plan->duration_days)->toDateString();

            $newMs = Membership::create([
                'member_id'      => $member->id,
                'plan_id'        => $plan->id,
                'start_date'     => $startDate,
                'end_date'       => $endDate,
                'status'         => 'active',
                'amount_paid'    => $data['amount_paid'],
                'payment_method' => $data['payment_method'],
                'created_by'     => auth()->id(),
            ]);

            // Log payment
            $pay = Payment::create([
                'member_id'      => $member->id,
                'type'           => 'renewal',
                'amount'         => $data['amount_paid'],
                'payment_method' => $data['payment_method'],
                'reference_id'   => $newMs->id,
                'reference_type' => 'Membership',
                'status'         => 'paid',
                'received_by'    => auth()->id(),
                'paid_at'        => now(),
            ]);

            // Renewal order record
            RenewalOrder::create([
                'member_id'        => $member->id,
                'old_membership_id'=> $oldMs?->id,
                'new_membership_id'=> $newMs->id,
                'processed_by'     => auth()->id(),
            ]);
        });

        return back()->with('success', 'Membership renewed successfully.');
    }
}
