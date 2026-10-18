<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InactiveCase;
use App\Models\Member;
use App\Models\FollowUp;
use Illuminate\Http\Request;

class InactiveController extends Controller
{
    public function index()
    {
        $cases = InactiveCase::open()
            ->with('member.user', 'member.activeMembership.plan', 'member.followUps')
            ->orderByRaw("FIELD(priority,'critical','urgent','watch')")
            ->orderByDesc('days_absent')
            ->paginate(25);

        $counts = [
            'total'    => InactiveCase::open()->count(),
            'critical' => InactiveCase::open()->where('priority', 'critical')->count(),
            'urgent'   => InactiveCase::open()->where('priority', 'urgent')->count(),
            'watch'    => InactiveCase::open()->where('priority', 'watch')->count(),
        ];

        return view('admin.inactive', compact('cases', 'counts'));
    }

    public function followUp(Request $request, Member $member)
    {
        $request->validate([
            'outcome' => 'required|in:answered,no_answer,will_return,cancelled',
            'notes'   => 'nullable|string|max:500',
        ]);

        FollowUp::create([
            'member_id'      => $member->id,
            'called_by'      => auth()->id(),
            'outcome'        => $request->outcome,
            'notes'          => $request->notes,
            'followed_up_at' => now(),
        ]);

        return back()->with('success', 'Follow-up logged for ' . $member->user->name . '.');
    }

    public function resolve(Request $request, InactiveCase $inactiveCase)
    {
        $inactiveCase->update(['status' => 'resolved', 'resolved_at' => now()]);
        return back()->with('success', 'Case marked as resolved.');
    }
}
