@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')

@section('content')
{{-- ── Page header ──────────────────────────────────────────── --}}
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow">
            <span class="eyebrow-dot"></span>
            <span class="eyebrow-text">GYM DASHBOARD</span>
            <span style="color:var(--color-outline);font-size:12px;margin:0 4px;">/</span>
            <span class="eyebrow-text" style="color:var(--color-outline);">TODAY'S OPERATIONS</span>
        </div>
        <h1 class="page-title">Gym Overview</h1>
        <p class="page-title-sub">Real-time overview of check-ins, members, payments and retention.</p>
    </div>
    <div style="display:flex;align-items:center;gap:12px;">
        <div style="display:flex;align-items:center;gap:10px;padding:10px 16px;border-radius:8px;background:var(--color-surface-low);">
            <span class="material-symbols-outlined" style="font-size:18px;color:var(--color-primary-container);animation:pulseGreen 2s infinite;">radio_button_checked</span>
            <div>
                <div style="font-family:var(--font-heading);font-size:10px;letter-spacing:.1em;color:var(--color-outline);text-transform:uppercase;">LIVE</div>
                <div style="font-size:12px;font-weight:600;color:var(--color-on-surface);">{{ now()->format('d M Y, H:i') }}</div>
            </div>
        </div>
        <a href="{{ route('receptionist.attendance.index') }}" class="btn-primary">
            <span class="material-symbols-outlined" style="font-size:18px;">how_to_reg</span>
            Mark Attendance
        </a>
    </div>
</div>

{{-- ── Quick actions ─────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin-bottom:32px;">
    <a href="{{ route('admin.members.create') }}" class="quick-action-btn primary-action" style="text-decoration:none;">
        <div style="display:flex;align-items:center;gap:10px;min-width:0;">
            <span class="material-symbols-outlined" style="color:var(--color-on-primary);font-size:20px;">person_add</span>
            <span style="font-family:var(--font-heading);font-size:11px;letter-spacing:.06em;font-weight:700;text-transform:uppercase;color:var(--color-on-primary);white-space:nowrap;">Add Member</span>
        </div>
        <span class="material-symbols-outlined" style="color:var(--color-on-primary);font-size:16px;">chevron_right</span>
    </a>
    <a href="{{ route('receptionist.attendance.index') }}" class="quick-action-btn" style="text-decoration:none;">
        <div style="display:flex;align-items:center;gap:10px;min-width:0;">
            <span class="pulse-green" style="width:8px;height:8px;flex-shrink:0;"></span>
            <span class="material-symbols-outlined" style="color:var(--color-primary-container);font-size:20px;">how_to_reg</span>
            <span style="font-family:var(--font-heading);font-size:11px;letter-spacing:.06em;font-weight:700;text-transform:uppercase;white-space:nowrap;">Mark Attendance</span>
        </div>
        <span class="material-symbols-outlined" style="color:var(--color-outline);font-size:16px;">arrow_forward</span>
    </a>
    <a href="{{ route('admin.payments.index') }}" class="quick-action-btn" style="text-decoration:none;">
        <div style="display:flex;align-items:center;gap:10px;">
            <span class="material-symbols-outlined" style="color:var(--color-secondary);font-size:20px;">payments</span>
            <span style="font-family:var(--font-heading);font-size:11px;letter-spacing:.06em;font-weight:700;text-transform:uppercase;">Take Payment</span>
        </div>
        <span class="material-symbols-outlined" style="color:var(--color-outline);font-size:16px;">arrow_forward</span>
    </a>
    <a href="{{ route('admin.renewals.index') }}" class="quick-action-btn" style="text-decoration:none;">
        <div style="display:flex;align-items:center;gap:10px;">
            <span class="material-symbols-outlined" style="color:var(--color-primary-fixed-dim, #2ae500);font-size:20px;">autorenew</span>
            <span style="font-family:var(--font-heading);font-size:11px;letter-spacing:.06em;font-weight:700;text-transform:uppercase;">Renew Plan</span>
        </div>
        <span class="material-symbols-outlined" style="color:var(--color-outline);font-size:16px;">arrow_forward</span>
    </a>
    <a href="{{ route('admin.trainers.index') }}" class="quick-action-btn" style="text-decoration:none;">
        <div style="display:flex;align-items:center;gap:10px;">
            <span class="material-symbols-outlined" style="color:var(--color-on-surface-variant);font-size:20px;">sports_gymnastics</span>
            <span style="font-family:var(--font-heading);font-size:11px;letter-spacing:.06em;font-weight:700;text-transform:uppercase;">Staff</span>
        </div>
        <span class="material-symbols-outlined" style="color:var(--color-outline);font-size:16px;">arrow_forward</span>
    </a>
    <a href="{{ route('admin.inactive.index') }}" class="quick-action-btn" style="text-decoration:none;">
        <div style="display:flex;align-items:center;gap:10px;">
            <span class="material-symbols-outlined" style="color:var(--color-error);font-size:20px;">crisis_alert</span>
            <span style="font-family:var(--font-heading);font-size:11px;letter-spacing:.06em;font-weight:700;text-transform:uppercase;white-space:nowrap;">Stopped Coming</span>
        </div>
        <span style="font-family:var(--font-heading);font-size:10px;padding:2px 8px;border-radius:999px;background:var(--color-error-container);color:var(--color-on-error-container);font-weight:700;">{{ $absentCount }}</span>
    </a>
</div>

{{-- ── Today summary strip ───────────────────────────────────── --}}
<div class="card-dark" style="margin-bottom:24px;position:relative;overflow:hidden;">
    <div style="position:absolute;right:-60px;bottom:-60px;width:200px;height:200px;border-radius:50%;background:rgba(57,255,20,.04);filter:blur(60px);pointer-events:none;"></div>
    <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:16px;position:relative;z-index:1;">
        <div style="display:flex;align-items:center;gap:16px;">
            <div style="width:48px;height:48px;border-radius:8px;background:var(--color-surface-container);display:flex;align-items:center;justify-content:center;color:var(--color-primary-container);flex-shrink:0;">
                <span class="material-symbols-outlined" style="font-size:24px;">calendar_today</span>
            </div>
            <div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <span class="text-title-md" style="color:var(--color-on-surface);">Today's Summary</span>
                    <span class="badge-green">LIVE</span>
                </div>
                <p style="font-size:12px;color:var(--color-outline);margin:0;">Real-time overview for {{ today()->format('l, d F Y') }}</p>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(100px,1fr));gap:8px;flex:1;min-width:0;">
            @foreach([
                ['Check-Ins',     $todayCheckIns,    'var(--color-primary-container)', 'Today'],
                ['New Members',   $todayNewMembers,   'var(--color-on-surface)',        '+vs yesterday'],
                ['Absent (>10d)', $todayAbsent,       'var(--color-error)',             'Need follow-up'],
                ['Renewals',      $todayRenewals,     'var(--color-secondary)',         'Today'],
                ['Collected',     'PKR '.number_format($todayCollected), 'var(--color-on-surface)', 'Cleared'],
                ['Pending',       'PKR '.number_format($todayPending),   'var(--color-error)',       'Unpaid'],
            ] as [$label, $value, $color, $sub])
            <div class="summary-strip">
                <span class="summary-strip-label">{{ $label }}</span>
                <span class="summary-strip-value" style="color:{{ $color }};">{{ $value }}</span>
                <span class="summary-strip-sub" style="color:var(--color-outline);">{{ $sub }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ── KEY STAT CARDS ────────────────────────────────────────── --}}
<div style="margin-bottom:8px;" class="section-header">
    <div style="display:flex;align-items:center;gap:8px;">
        <span class="section-label">SUMMARY</span>
        <span style="color:var(--color-outline);font-size:12px;">//</span>
        <span class="section-title">Key Numbers</span>
    </div>
    <span style="font-family:var(--font-heading);font-size:11px;color:var(--color-outline);text-transform:uppercase;letter-spacing:.06em;">Updated just now</span>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-bottom:32px;">
    {{-- Total Members --}}
    <div class="stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;">
            <div>
                <div class="stat-label">Total Members</div>
                <div class="stat-value" style="color:var(--color-on-surface);">{{ number_format($totalMembers) }}</div>
            </div>
            <span class="badge-green">+{{ $newMembersMonth }} this month</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:16px;padding-top:12px;border-top:1px solid var(--color-surface-highest);">
            <span class="stat-sub">Registered in gym</span>
            <span class="material-symbols-outlined" style="font-size:16px;color:var(--color-primary-container);">group</span>
        </div>
    </div>

    {{-- Active Members --}}
    <div class="stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;">
            <div>
                <div class="stat-label">Active Members</div>
                <div class="stat-value" style="color:var(--color-primary-container);">{{ number_format($activeMembers) }}</div>
            </div>
            <span class="badge-green">{{ $totalMembers > 0 ? round($activeMembers/$totalMembers*100,1) : 0 }}% ACTIVE</span>
        </div>
        <div style="margin-top:16px;padding-top:12px;border-top:1px solid var(--color-surface-highest);">
            <div style="background:var(--color-surface-highest);height:6px;border-radius:999px;overflow:hidden;">
                <div style="background:var(--color-primary-container);height:100%;border-radius:999px;width:{{ $totalMembers > 0 ? round($activeMembers/$totalMembers*100) : 0 }}%;"></div>
            </div>
            <span class="stat-sub" style="margin-top:4px;display:block;">Valid gym passes</span>
        </div>
    </div>

    {{-- Today's Attendance --}}
    <div class="stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;">
            <div>
                <div class="stat-label">Today's Attendance</div>
                <div class="stat-value" style="color:var(--color-on-surface);">{{ number_format($todayAttendance) }}</div>
            </div>
            <span class="badge-watch">TODAY</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:16px;padding-top:12px;border-top:1px solid var(--color-surface-highest);">
            <span class="stat-sub">Check-ins so far</span>
            <span class="material-symbols-outlined" style="font-size:16px;color:var(--color-primary-container);">verified_user</span>
        </div>
    </div>

    {{-- Monthly Income --}}
    <div class="stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;">
            <div>
                <div class="stat-label">Monthly Income</div>
                <div class="stat-value" style="color:var(--color-primary-container);font-size:36px;">PKR {{ number_format($monthlyIncome) }}</div>
            </div>
            <span class="badge-green">On Track</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:16px;padding-top:12px;border-top:1px solid var(--color-surface-highest);">
            <span class="stat-sub">{{ now()->format('F Y') }}</span>
            <span class="material-symbols-outlined" style="font-size:16px;color:var(--color-primary-container);">trending_up</span>
        </div>
    </div>

    {{-- Absent >10 days --}}
    <div class="stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;">
            <div>
                <div class="stat-label">Absent (>10 Days)</div>
                <div class="stat-value" style="color:var(--color-error);">{{ number_format($absentCount) }}</div>
            </div>
            <span class="badge-at-risk">AT RISK</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:16px;padding-top:12px;border-top:1px solid var(--color-surface-highest);">
            <span class="stat-sub">Needs follow-up call</span>
            <a href="{{ route('admin.inactive.index') }}" style="font-family:var(--font-heading);font-size:10px;letter-spacing:.06em;color:var(--color-error);text-transform:uppercase;text-decoration:none;">View →</a>
        </div>
    </div>

    {{-- Expiring Soon --}}
    <div class="stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;">
            <div>
                <div class="stat-label">Ending Soon</div>
                <div class="stat-value" style="color:var(--color-on-surface);">{{ number_format($expiringSoon) }}</div>
            </div>
            <span class="badge-paused">&lt; 7 DAYS</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:16px;padding-top:12px;border-top:1px solid var(--color-surface-highest);">
            <span class="stat-sub">Needs renewal</span>
            <a href="{{ route('admin.renewals.index') }}" style="font-family:var(--font-heading);font-size:10px;letter-spacing:.06em;color:var(--color-secondary);text-transform:uppercase;text-decoration:none;">View →</a>
        </div>
    </div>

    {{-- Pending Fees --}}
    <div class="stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;">
            <div>
                <div class="stat-label">Pending Fees</div>
                <div class="stat-value" style="color:var(--color-error);font-size:32px;">PKR {{ number_format($pendingFees) }}</div>
            </div>
            <span class="badge-at-risk">OVERDUE</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:16px;padding-top:12px;border-top:1px solid var(--color-surface-highest);">
            <span class="stat-sub">Unpaid invoices</span>
            <span class="material-symbols-outlined" style="font-size:16px;color:var(--color-error);">warning</span>
        </div>
    </div>

    {{-- Renewal Income --}}
    <div class="stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;">
            <div>
                <div class="stat-label">Renewal Income</div>
                <div class="stat-value" style="color:var(--color-on-surface);font-size:32px;">PKR {{ number_format($renewalIncome) }}</div>
            </div>
            <span class="badge-green">THIS MONTH</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:16px;padding-top:12px;border-top:1px solid var(--color-surface-highest);">
            <span class="stat-sub">Membership renewals</span>
            <span class="material-symbols-outlined" style="font-size:16px;color:var(--color-primary-container);">autorenew</span>
        </div>
    </div>
</div>

{{-- ── CHARTS ────────────────────────────────────────────────── --}}
<div style="margin-bottom:8px;" class="section-header">
    <div style="display:flex;align-items:center;gap:8px;">
        <span class="section-label" style="color:var(--color-secondary);">CHARTS</span>
        <span style="color:var(--color-outline);font-size:12px;">//</span>
        <span class="section-title">Gym Activity</span>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:32px;">
    {{-- Daily Attendance Chart --}}
    <div class="chart-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;">
            <div>
                <span class="stat-label">VISITORS</span>
                <div class="section-title" style="font-size:16px;">Daily Attendance</div>
                <span class="stat-sub">Last 7 days</span>
            </div>
            <span class="badge-green">7 DAYS</span>
        </div>
        <div class="chart-canvas-wrap">
            <canvas id="attendanceChart"></canvas>
        </div>
    </div>

    {{-- Income Breakdown Chart --}}
    <div class="chart-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;">
            <div>
                <span class="stat-label">FINANCIAL</span>
                <div class="section-title" style="font-size:16px;">Income Breakdown</div>
                <span class="stat-sub">{{ now()->format('F Y') }} — PKR {{ number_format($monthlyIncome) }} total</span>
            </div>
        </div>
        <div class="chart-canvas-wrap">
            <canvas id="incomeChart"></canvas>
        </div>
    </div>
</div>

{{-- ── Expiring soon + Recent check-ins ─────────────────────── --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:32px;">

    {{-- Expiring Soon Table --}}
    <div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
        <div style="padding:16px;background:var(--color-surface-container);display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:10px;">
                <span class="material-symbols-outlined" style="color:var(--color-secondary);font-size:20px;">timer</span>
                <div>
                    <div class="section-title" style="font-size:15px;">Ending Soon (7 Days)</div>
                    <p style="font-size:11px;color:var(--color-outline);margin:0;">{{ $expiringSoon }} memberships up for renewal</p>
                </div>
            </div>
            <a href="{{ route('admin.renewals.index') }}" class="btn-ghost btn-sm">View All</a>
        </div>
        <div style="overflow-x:auto;">
            <table class="table-dark">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Plan</th>
                        <th>Expires</th>
                        <th style="text-align:right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expiringSoonList as $ms)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:32px;height:32px;border-radius:50%;background:var(--color-surface-highest);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:11px;font-weight:700;color:var(--color-on-surface);">{{ $ms->member->initials() }}</div>
                                <div>
                                    <div class="td-name">{{ $ms->member->user->name }}</div>
                                    <div class="td-sub">{{ $ms->member->user->phone }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge-paused">{{ $ms->plan->name }}</span></td>
                        <td>
                            <div style="font-size:12px;font-weight:600;color:var(--color-error);">{{ $ms->end_date->format('d M') }}</div>
                            <div style="font-size:11px;color:var(--color-outline);">{{ $ms->daysRemaining() }}d left</div>
                        </td>
                        <td style="text-align:right;">
                            <a href="{{ route('admin.renewals.index') }}" class="btn-primary btn-sm">Renew</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;padding:24px;color:var(--color-outline);font-size:12px;">No memberships expiring in the next 7 days.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Check-ins --}}
    <div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
        <div style="padding:16px;background:var(--color-surface-container);display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:10px;">
                <span class="material-symbols-outlined" style="color:var(--color-primary-container);font-size:20px;">how_to_reg</span>
                <div>
                    <div class="section-title" style="font-size:15px;">Recent Check-ins</div>
                    <p style="font-size:11px;color:var(--color-outline);margin:0;">Today — {{ $todayAttendance }} total</p>
                </div>
            </div>
            <a href="{{ route('receptionist.attendance.index') }}" class="btn-ghost btn-sm">Go to Desk</a>
        </div>
        <div style="overflow-x:auto;">
            <table class="table-dark">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Time</th>
                        <th>Marked by</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentCheckIns as $att)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:32px;height:32px;border-radius:50%;background:var(--color-surface-highest);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:11px;font-weight:700;color:var(--color-on-surface);">{{ $att->member->initials() }}</div>
                                <div>
                                    <div class="td-name">{{ $att->member->user->name }}</div>
                                    <div class="td-sub">{{ $att->member->member_code }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-green">{{ \Carbon\Carbon::parse($att->checked_in_at)->format('H:i') }}</span>
                        </td>
                        <td style="font-size:12px;color:var(--color-outline);">{{ $att->marker->name ?? 'System' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;padding:24px;color:var(--color-outline);font-size:12px;">No check-ins today yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    Chart.defaults.color = '#85967c';
    Chart.defaults.borderColor = '#353534';
    Chart.defaults.font.family = 'Inter';

    // Attendance chart
    new Chart(document.getElementById('attendanceChart'), {
        type: 'bar',
        data: {
            labels: @json($attendanceChart->pluck('label')),
            datasets: [{
                label: 'Check-ins',
                data: @json($attendanceChart->pluck('count')),
                backgroundColor: 'rgba(57,255,20,0.25)',
                borderColor: '#39ff14',
                borderWidth: 2,
                borderRadius: 4,
            }]
        },
        options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{x:{grid:{color:'rgba(53,53,52,.5)'}},y:{grid:{color:'rgba(53,53,52,.5)'},beginAtZero:true}} }
    });

    // Income breakdown chart
    const incomeData = @json($incomeByType);
    const incomeLabels = { membership:'Memberships', pt_session:'Personal Training', addon:'Store', renewal:'Renewals', other:'Other' };
    new Chart(document.getElementById('incomeChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(incomeData).map(k => incomeLabels[k] || k),
            datasets: [{
                data: Object.values(incomeData),
                backgroundColor: ['#39ff14','#81cfff','#2ae500','#baccb0','#353534'],
                borderWidth: 2,
                borderColor: '#201f1f',
            }]
        },
        options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{position:'bottom',labels:{padding:16,color:'#baccb0'}}} }
    });
</script>
<style>
@media(max-width:900px){
    div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important;}
}
</style>
@endpush
@endsection
