<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\TrainerAssignment;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $trainer = auth()->user()->trainer;
        $query = TrainerAssignment::whereNull('ended_at')
            ->with('member.user', 'member.activeMembership.plan', 'member.streak', 'member.followUps.caller');

        if ($trainer) {
            $query->where('trainer_id', $trainer->id);
        }

        $assignments = $query->paginate(20);
        return view('trainer.members.index', compact('assignments'));
    }

    public function show(\App\Models\Member $member)
    {
        // Ensure trainer owns this member (or user is admin)
        $trainer = auth()->user()->trainer;
        if ($trainer && !auth()->user()->isAdmin()) {
            abort_unless(
                TrainerAssignment::where('trainer_id', $trainer->id)->where('member_id', $member->id)->whereNull('ended_at')->exists(),
                403
            );
        }
        $member->load('user', 'activeMembership.plan', 'streak', 'workoutPlans.logs', 'dietAssignments.dietPlan', 'followUps.caller');
        $attendanceThisMonth = $member->attendance()->whereMonth('date', now()->month)->count();
        return view('trainer.members.show', compact('member', 'attendanceThisMonth'));
    }

    public function callLog(Request $request, \App\Models\Member $member)
    {
        $request->validate([
            'outcome' => 'required|in:answered,no_answer,will_return,cancelled',
            'notes'   => 'nullable|string|max:500',
        ]);

        $trainer = auth()->user()->trainer;
        if ($trainer && !auth()->user()->isAdmin()) {
            abort_unless(
                TrainerAssignment::where('trainer_id', $trainer->id)->where('member_id', $member->id)->whereNull('ended_at')->exists(),
                403,
                'You are not assigned to this member.'
            );
        }

        \App\Models\FollowUp::create([
            'member_id'      => $member->id,
            'called_by'      => auth()->id(),
            'outcome'        => $request->outcome,
            'notes'          => $request->notes,
            'followed_up_at' => now(),
        ]);

        return back()->with('success', 'Call log recorded for ' . $member->user->name . '.');
    }
}
