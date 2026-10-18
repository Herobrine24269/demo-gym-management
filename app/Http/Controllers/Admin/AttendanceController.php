<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date  = $request->get('date', today()->toDateString());
        $query = Attendance::whereDate('date', $date)->with('member.user', 'marker', 'corrector');

        if ($q = $request->get('q')) {
            $query->whereHas('member.user', fn($sq) => $sq->where('name', 'like', "%{$q}%"));
        }

        $records = $query->orderBy('checked_in_at', 'desc')->paginate(30)->withQueryString();

        return view('admin.attendance.index', compact('records', 'date'));
    }

    public function correct(Request $request, Attendance $attendance)
    {
        $data = $request->validate([
            'checked_in_at'  => 'required',
            'checked_out_at' => 'nullable',
            'note'           => 'nullable|string|max:255',
        ]);

        $before = $attendance->toArray();
        $attendance->update([
            'checked_in_at'  => $data['checked_in_at'],
            'checked_out_at' => $data['checked_out_at'],
            'note'           => $data['note'],
            'is_corrected'   => true,
            'corrected_by'   => auth()->id(),
            'corrected_at'   => now(),
        ]);

        AuditLog::record('attendance_corrected', $attendance, $before, $attendance->fresh()->toArray());
        return back()->with('success', 'Attendance record corrected.');
    }

    public function destroy(Attendance $attendance)
    {
        $before = $attendance->toArray();
        $attendance->delete();
        AuditLog::record('attendance_deleted', $attendance, $before, null);
        return back()->with('success', 'Attendance record deleted.');
    }
}
