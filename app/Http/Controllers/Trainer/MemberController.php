<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\TrainerAssignment;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $trainer     = auth()->user()->trainer;
        $assignments = TrainerAssignment::where('trainer_id', $trainer->id)
            ->whereNull('ended_at')
            ->with('member.user', 'member.activeMembership.plan', 'member.streak')
            ->paginate(20);
        return view('trainer.members.index', compact('assignments'));
    }

    public function show(\App\Models\Member $member)
    {
        // Ensure trainer owns this member
        $trainer = auth()->user()->trainer;
        abort_unless(
            TrainerAssignment::where('trainer_id', $trainer->id)->where('member_id', $member->id)->whereNull('ended_at')->exists(),
            403
        );
        $member->load('user', 'activeMembership.plan', 'streak', 'workoutPlans.logs', 'dietAssignments.dietPlan');
        $attendanceThisMonth = $member->attendance()->whereMonth('date', now()->month)->count();
        return view('trainer.members.show', compact('member', 'attendanceThisMonth'));
    }
}
