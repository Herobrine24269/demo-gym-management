@extends('layouts.dashboard')
@section('title', 'My Attendance')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">MEMBER PORTAL</span></div>
        <h1 class="page-title">My Attendance</h1>
    </div>
</div>
<div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
    <div style="padding:16px;background:var(--color-surface-container);">
        <div class="section-title" style="font-size:15px;">Full Attendance History</div>
    </div>
    <div style="overflow-x:auto;">
        <table class="table-dark">
            <thead><tr><th>#</th><th>Date</th><th>Day</th><th>Check-in Time</th><th>Check-out</th></tr></thead>
            <tbody>
                @forelse($attendance as $att)
                <tr>
                    <td style="font-family:var(--font-heading);font-size:11px;color:var(--color-outline);">{{ $loop->iteration }}</td>
                    <td style="font-size:13px;font-weight:600;">{{ $att->date->format('d M Y') }}</td>
                    <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $att->date->format('l') }}</td>
                    <td><span class="badge-green">{{ \Carbon\Carbon::parse($att->checked_in_at)->format('H:i') }}</span></td>
                    <td style="font-size:12px;color:var(--color-outline);">{{ $att->checked_out_at ? \Carbon\Carbon::parse($att->checked_out_at)->format('H:i') : '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state"><span class="material-symbols-outlined">calendar_month</span><div class="empty-state-title">No Attendance Yet</div><div class="empty-state-body">Your attendance will appear here once a staff member checks you in.</div></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($attendance->hasPages())
    <div style="padding:16px;">{{ $attendance->links() }}</div>
    @endif
</div>
@endsection
