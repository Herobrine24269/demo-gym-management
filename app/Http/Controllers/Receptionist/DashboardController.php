<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Payment;
use App\Models\InactiveCase;

class DashboardController extends Controller
{
    public function index()
    {
        $today          = today();
        $todayCheckIns  = Attendance::whereDate('date', $today)->count();
        $expiringSoon   = Membership::expiringSoon()->count();
        $absentFlags    = InactiveCase::open()->count();
        $todayCollected = Payment::paid()->today()->sum(\DB::raw('amount - discount'));

        $recentCheckIns = Attendance::whereDate('date', $today)
            ->with('member.user', 'member.activeMembership.plan')
            ->latest()
            ->take(20)
            ->get();

        $expiringSoonList = Membership::expiringSoon()
            ->with('member.user', 'plan')
            ->orderBy('end_date')
            ->take(8)
            ->get();

        return view('receptionist.dashboard', compact(
            'todayCheckIns', 'expiringSoon', 'absentFlags', 'todayCollected',
            'recentCheckIns', 'expiringSoonList',
        ));
    }
}
