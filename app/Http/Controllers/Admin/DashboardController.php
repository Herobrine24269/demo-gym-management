<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Attendance;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Trainer;
use App\Models\InactiveCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();

        /* ── Key stats ───────────────────────────────────────── */
        $totalMembers   = Member::count();
        $activeMembers  = Membership::where('status', 'active')->distinct('member_id')->count('member_id');
        $todayAttendance= Attendance::whereDate('date', $today)->count();
        $monthlyIncome  = Payment::paid()->thisMonth()->sum(DB::raw('amount - discount'));
        $newMembersMonth= Member::whereMonth('joined_at', now()->month)->whereYear('joined_at', now()->year)->count();
        $activeLast7    = Attendance::where('date', '>=', now()->subDays(7))->distinct('member_id')->count('member_id');
        $absentCount    = InactiveCase::open()->count();
        $expiringSoon   = Membership::expiringSoon()->count();
        $pendingFees    = Payment::where('status', 'pending')->sum(DB::raw('amount - discount'));
        $renewalIncome  = Payment::paid()->thisMonth()->where('type', 'renewal')->sum(DB::raw('amount - discount'));
        $ptIncome       = Payment::paid()->thisMonth()->where('type', 'pt_session')->sum(DB::raw('amount - discount'));
        $addonIncome    = Payment::paid()->thisMonth()->where('type', 'addon')->sum(DB::raw('amount - discount'));

        /* ── Today summary ───────────────────────────────────── */
        $todayCheckIns  = $todayAttendance;
        $todayNewMembers= Member::whereDate('joined_at', $today)->count();
        $todayAbsent    = InactiveCase::open()->where('days_absent', '>=', 10)->count();
        $todayCollected = Payment::paid()->today()->sum(DB::raw('amount - discount'));
        $todayPending   = Payment::where('status', 'pending')->whereDate('paid_at', $today)->sum(DB::raw('amount - discount'));
        $todayRenewals  = Payment::paid()->today()->where('type', 'renewal')->count();

        /* ── Expiring soon list ───────────────────────────────── */
        $expiringSoonList = Membership::expiringSoon()
            ->with('member.user', 'plan')
            ->orderBy('end_date')
            ->take(8)
            ->get();

        /* ── Recent check-ins ────────────────────────────────── */
        $recentCheckIns = Attendance::whereDate('date', $today)
            ->with('member.user')
            ->latest()
            ->take(10)
            ->get();

        /* ── Daily attendance last 7 days (for chart) ────────── */
        $attendanceChart = collect(range(6, 0))->map(function ($d) {
            $date = today()->subDays($d);
            return [
                'label' => $date->format('D'),
                'count' => Attendance::whereDate('date', $date)->count(),
            ];
        });

        /* ── Income by type this month (for chart) ───────────── */
        $incomeByType = Payment::paid()->thisMonth()
            ->select('type', DB::raw('SUM(amount - discount) as total'))
            ->groupBy('type')
            ->pluck('total', 'type');

        return view('admin.dashboard', compact(
            'totalMembers', 'activeMembers', 'todayAttendance', 'monthlyIncome',
            'newMembersMonth', 'activeLast7', 'absentCount', 'expiringSoon',
            'pendingFees', 'renewalIncome', 'ptIncome', 'addonIncome',
            'todayCheckIns', 'todayNewMembers', 'todayAbsent', 'todayCollected',
            'todayPending', 'todayRenewals',
            'expiringSoonList', 'recentCheckIns',
            'attendanceChart', 'incomeByType',
        ));
    }
}
