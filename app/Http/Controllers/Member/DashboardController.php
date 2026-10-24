<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $user   = auth()->user();
        $member = $user->member;

        if (!$member) {
            return redirect()->route('home')->with('error', 'Member profile not found.');
        }

        $member->load('activeMembership.plan', 'streak', 'workoutPlans.trainer.user', 'dietAssignments.dietPlan.meals.foodItems', 'ptSessions.trainer.user');

        $attendanceThisMonth = $member->attendance()->whereMonth('date', now()->month)->whereYear('date', now()->year)->count();
        $recentAttendance    = $member->attendance()->latest('date')->take(14)->get();
        $recentPayments      = Payment::where('member_id', $member->id)->latest('paid_at')->take(10)->get();
        $totalPaid           = Payment::where('member_id', $member->id)->where('status', 'paid')->sum('amount');

        // Build a 14-day attendance map for the visual calendar
        $attendanceMap = $recentAttendance->pluck('date')->map(fn($d) => $d->format('Y-m-d'))->flip();

        return view('member.dashboard', compact(
            'member', 'attendanceThisMonth', 'recentAttendance',
            'recentPayments', 'totalPaid', 'attendanceMap',
        ));
    }
}
