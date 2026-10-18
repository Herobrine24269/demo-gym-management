@extends('layouts.dashboard')
@section('title', 'Receptionist Dashboard')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow">
            <span class="eyebrow-dot"></span>
            <span class="eyebrow-text">RECEPTION DESK</span>
            <span style="color:var(--color-outline);font-size:12px;margin:0 4px;">/</span>
            <span class="eyebrow-text" style="color:var(--color-outline);">{{ today()->format('l, d F Y') }}</span>
        </div>
        <h1 class="page-title">Today's Operations</h1>
    </div>
    <a href="{{ route('receptionist.attendance.index') }}" class="btn-primary">
        <span class="material-symbols-outlined" style="font-size:18px;">how_to_reg</span>
        Mark Attendance
    </a>
</div>

{{-- Summary strip --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:32px;">
    @foreach([
        ['Check-ins Today',   $todayCheckIns,                              'how_to_reg',    'var(--color-primary-container)'],
        ['Expiring Soon',     $expiringSoon,                               'timer',         'var(--color-secondary)'],
        ['Absent (>10d)',     $absentFlags,                                'crisis_alert',  'var(--color-error)'],
        ['Collected Today',   'PKR '.number_format($todayCollected),       'payments',      'var(--color-on-surface)'],
    ] as [$lbl,$val,$icon,$clr])
    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div class="stat-label">{{ $lbl }}</div>
            <span class="material-symbols-outlined" style="font-size:20px;color:{{ $clr }};">{{ $icon }}</span>
        </div>
        <div style="font-family:var(--font-heading);font-size:32px;font-weight:700;color:{{ $clr }};margin-top:8px;">{{ $val }}</div>
    </div>
    @endforeach
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
    {{-- Recent Check-ins --}}
    <div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
        <div style="padding:16px;background:var(--color-surface-container);display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:10px;">
                <span class="material-symbols-outlined" style="color:var(--color-primary-container);font-size:20px;">how_to_reg</span>
                <div>
                    <div class="section-title" style="font-size:15px;">Recent Check-ins</div>
                    <p style="font-size:11px;color:var(--color-outline);margin:0;">{{ $todayCheckIns }} total today</p>
                </div>
            </div>
            <a href="{{ route('receptionist.attendance.index') }}" class="btn-primary btn-sm">Go to Desk</a>
        </div>
        <div style="overflow-x:auto;">
            <table class="table-dark">
                <thead><tr><th>Member</th><th>Time</th><th>Plan</th></tr></thead>
                <tbody>
                @forelse($recentCheckIns as $att)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:32px;height:32px;border-radius:50%;background:var(--color-surface-highest);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:11px;font-weight:700;">{{ $att->member->initials() }}</div>
                            <div>
                                <div class="td-name">{{ $att->member->user->name }}</div>
                                <div class="td-sub">{{ $att->member->member_code }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-green">{{ \Carbon\Carbon::parse($att->checked_in_at)->format('H:i') }}</span></td>
                    <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $att->member->activeMembership?->plan->name ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="empty-state" style="padding:24px;text-align:center;font-size:12px;color:var(--color-outline);">No check-ins yet today.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Expiring Soon --}}
    <div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
        <div style="padding:16px;background:var(--color-surface-container);display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:10px;">
                <span class="material-symbols-outlined" style="color:var(--color-secondary);font-size:20px;">timer</span>
                <div>
                    <div class="section-title" style="font-size:15px;">Expiring This Week</div>
                    <p style="font-size:11px;color:var(--color-outline);margin:0;">{{ $expiringSoon }} memberships need renewal</p>
                </div>
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="table-dark">
                <thead><tr><th>Member</th><th>Plan</th><th>Expires</th><th style="text-align:right;">Action</th></tr></thead>
                <tbody>
                @forelse($expiringSoonList as $ms)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:32px;height:32px;border-radius:50%;background:var(--color-surface-highest);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:11px;font-weight:700;">{{ $ms->member->initials() }}</div>
                            <div>
                                <div class="td-name">{{ $ms->member->user->name }}</div>
                                <div class="td-sub">{{ $ms->member->user->phone }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-paused">{{ $ms->plan->name }}</span></td>
                    <td><span style="font-size:12px;font-weight:600;color:var(--color-error);">{{ $ms->end_date->format('d M') }}</span><div style="font-size:10px;color:var(--color-outline);">{{ $ms->daysRemaining() }}d left</div></td>
                    <td style="text-align:right;"><a href="{{ route('admin.renewals.index') }}" class="btn-primary btn-sm">Renew</a></td>
                </tr>
                @empty
                <tr><td colspan="4" style="padding:24px;text-align:center;font-size:12px;color:var(--color-outline);">No memberships expiring soon.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<style>@media(max-width:900px){div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important;}}</style>
@endpush
@endsection
