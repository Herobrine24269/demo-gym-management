<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Member;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {
        $todayAttendance = Attendance::whereDate('date', today())
            ->with('member.user', 'marker')
            ->orderByDesc('checked_in_at')
            ->get();

        return view('receptionist.attendance', compact('todayAttendance'));
    }

    /**
     * AJAX: search members by name / phone / member_code
     */
    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) return response()->json([]);

        $members = Member::with('user', 'activeMembership.plan')
            ->whereHas('user', function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('phone', 'like', "%{$q}%");
            })
            ->orWhere('member_code', 'like', "%{$q}%")
            ->limit(10)
            ->get();

        return response()->json($members->map(function (Member $m) {
            $ms = $m->activeMembership;
            return [
                'id'          => $m->id,
                'name'        => $m->user->name,
                'member_code' => $m->member_code,
                'phone'       => $m->user->phone,
                'initials'    => $m->initials(),
                'plan'        => $ms?->plan->name ?? 'No active plan',
                'expires'     => $ms?->end_date?->format('d M Y') ?? '—',
                'status'      => $ms?->status ?? 'expired',
                'already_in'  => Attendance::where('member_id', $m->id)->whereDate('date', today())->exists(),
            ];
        }));
    }

    public function store(Request $request)
    {
        $request->validate(['member_id' => 'required|exists:members,id']);

        $memberId = $request->member_id;

        // Block duplicate same-day attendance at DB level (unique constraint) and application level
        $existing = Attendance::where('member_id', $memberId)->whereDate('date', today())->first();
        if ($existing) {
            return back()->with('error', 'This member has already been checked in today.');
        }

        DB::transaction(function () use ($memberId, $request) {
            Attendance::create([
                'member_id'      => $memberId,
                'date'           => today(),
                'checked_in_at'  => now()->toTimeString(),
                'marked_by'      => auth()->id(),
                'note'           => $request->note,
            ]);

            // Update streak
            $streak = \App\Models\Streak::firstOrCreate(['member_id' => $memberId]);
            $yesterday = today()->subDay();
            if ($streak->last_seen_date && $streak->last_seen_date->eq($yesterday)) {
                $streak->current_streak++;
            } else {
                $streak->current_streak = 1;
            }
            $streak->longest_streak = max($streak->longest_streak, $streak->current_streak);
            $streak->last_seen_date = today();
            $streak->save();

            // Resolve inactive case if open
            \App\Models\InactiveCase::where('member_id', $memberId)->where('status', 'open')
                ->update(['status' => 'resolved', 'resolved_at' => now()]);
        });

        return back()->with('success', 'Attendance marked successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        $this->authorize('delete', $attendance);
        $before = $attendance->toArray();
        $attendance->delete();
        AuditLog::record('attendance_deleted', $attendance, $before, null);
        return back()->with('success', 'Attendance record removed.');
    }
}
