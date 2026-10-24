<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\TrainerAssignment;
use App\Models\WorkoutPlan;
use App\Models\PersonalTrainingSession;
use App\Models\Attendance;

class DashboardController extends Controller
{
    public function index()
    {
        $trainer     = auth()->user()->trainer;
        $myMembers   = TrainerAssignment::where('trainer_id', $trainer->id)->whereNull('ended_at')->with('member.user', 'member.activeMembership.plan')->get();
        $activePlans = WorkoutPlan::where('trainer_id', $trainer->id)->where('status', 'active')->with('member.user')->get();
        $ptSessions  = PersonalTrainingSession::where('trainer_id', $trainer->id)->where('status', 'active')->with('member.user')->get();
        $totalCheckInsToday = Attendance::whereDate('date', today())
            ->whereIn('member_id', $myMembers->pluck('member_id'))->count();
        $myPostsCount = \App\Models\Post::where('author_id', auth()->id())->count();

        return view('trainer.dashboard', compact('trainer', 'myMembers', 'activePlans', 'ptSessions', 'totalCheckInsToday', 'myPostsCount'));
    }
}
