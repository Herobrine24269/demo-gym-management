@extends('layouts.dashboard')
@section('title', 'Inactive Red List')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow">
            <span class="eyebrow-dot" style="background:var(--color-error);"></span>
            <span class="eyebrow-text" style="color:var(--color-error);">RETENTION ALERT</span>
            <span style="color:var(--color-outline);font-size:12px;margin:0 4px;">/</span>
            <span class="eyebrow-text" style="color:var(--color-outline);">INACTIVE RED LIST</span>
        </div>
        <h1 class="page-title" style="color:var(--color-on-surface);">Members Who Stopped Coming</h1>
        <p class="page-title-sub">Members absent for 10+ days. Log a follow-up call to track retention efforts.</p>
    </div>
    <div style="display:flex;align-items:center;gap:8px;padding:8px 14px;border-radius:6px;background:rgba(147,0,10,.15);border:1px solid rgba(255,180,171,.3);">
        <span style="width:8px;height:8px;border-radius:50%;background:var(--color-error);animation:pulseGreen 2s infinite;"></span>
        <span style="font-family:var(--font-heading);font-size:11px;letter-spacing:.06em;color:var(--color-error);text-transform:uppercase;">{{ $counts['total'] }} Open Cases</span>
    </div>
</div>

{{-- Priority counts --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin-bottom:24px;">
    @foreach([
        ['Total Open',   $counts['total'],    'crisis_alert', 'var(--color-on-surface)', ''],
        ['Critical (21d+)', $counts['critical'],'error',       'var(--color-error)',       'badge-critical'],
        ['Urgent (15-20d)', $counts['urgent'],  'warning',     'var(--color-error)',       'badge-urgent'],
        ['Watch (10-14d)',  $counts['watch'],   'visibility',  'var(--color-secondary)',   'badge-watch'],
    ] as [$lbl,$val,$icon,$clr,$badge])
    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div class="stat-label">{{ $lbl }}</div>
            <span class="material-symbols-outlined" style="font-size:18px;color:{{ $clr }};">{{ $icon }}</span>
        </div>
        <div style="font-family:var(--font-heading);font-size:40px;font-weight:700;color:{{ $clr }};margin-top:6px;">{{ $val }}</div>
    </div>
    @endforeach
</div>

{{-- Inactive table --}}
<div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
    <div style="padding:16px;background:var(--color-surface-container);display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:10px;">
            <span class="material-symbols-outlined" style="color:var(--color-error);font-size:22px;">crisis_alert</span>
            <div>
                <div class="section-title" style="font-size:15px;">At-Risk Member List</div>
                <p style="font-size:11px;color:var(--color-outline);margin:0;">Sorted by priority — most critical first</p>
            </div>
        </div>
    </div>
    <div style="overflow-x:auto;">
        <table class="table-dark">
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Days Absent</th>
                    <th>Priority</th>
                    <th>Last Visit</th>
                    <th>Membership</th>
                    <th>Follow-ups</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cases as $case)
                <tr class="red-list-row {{ $case->priority }}" id="case-{{ $case->id }}">
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:38px;height:38px;border-radius:50%;background:var(--color-surface-highest);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:12px;font-weight:700;color:var(--color-on-surface);flex-shrink:0;">{{ $case->member->initials() }}</div>
                            <div>
                                <div class="td-name">{{ $case->member->user->name }}</div>
                                <div class="td-sub">{{ $case->member->member_code }} · {{ $case->member->user->phone }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-family:var(--font-heading);font-size:20px;font-weight:700;color:var(--color-error);">{{ $case->days_absent }}</span>
                        <span style="font-size:11px;color:var(--color-outline);margin-left:2px;">days</span>
                    </td>
                    <td>
                        @if($case->priority === 'critical')
                            <span class="badge-critical">Critical</span>
                        @elseif($case->priority === 'urgent')
                            <span class="badge-urgent">Urgent</span>
                        @else
                            <span class="badge-watch">Watch</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:var(--color-on-surface-variant);">
                        {{ $case->last_attendance_date?->format('d M Y') ?? 'Never' }}
                    </td>
                    <td>
                        @php $ms = $case->member->activeMembership; @endphp
                        @if($ms)
                            <span class="badge-active">{{ $ms->plan->name }}</span>
                            <div style="font-size:11px;color:var(--color-outline);margin-top:2px;">Exp: {{ $ms->end_date->format('d M Y') }}</div>
                        @else
                            <span class="badge-expired">No Active Plan</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:var(--color-on-surface-variant);">
                        {{ $case->member->followUps->count() }} calls logged
                    </td>
                    <td style="text-align:right;">
                        <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px;">
                            <button class="btn-ghost btn-sm" onclick="openFollowUp({{ $case->member->id }}, '{{ addslashes($case->member->user->name) }}')" title="Log follow-up call">
                                <span class="material-symbols-outlined" style="font-size:14px;">phone</span>
                                Call Log
                            </button>
                            <form method="POST" action="{{ route('admin.inactive.resolve', $case) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-primary btn-sm" title="Mark as resolved">
                                    <span class="material-symbols-outlined" style="font-size:14px;">check_circle</span>
                                    Resolve
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state" style="padding:48px;">
                            <span class="material-symbols-outlined" style="color:var(--color-primary-container);">sentiment_very_satisfied</span>
                            <div class="empty-state-title">All Clear!</div>
                            <div class="empty-state-body">No members have been absent for 10+ days. Keep up the great retention work!</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($cases->hasPages())
    <div style="padding:16px;">
        {{ $cases->links('pagination::simple-tailwind') }}
    </div>
    @endif
</div>

{{-- Follow-up Modal --}}
<div id="followup-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.6);backdrop-filter:blur(4px);place-items:center;" onclick="if(event.target===this)closeFollowUp()">
    <div style="background:var(--color-surface-low);border:1px solid var(--color-surface-highest);border-radius:12px;padding:24px;width:100%;max-width:480px;margin:auto;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <div>
                <div class="section-title" style="font-size:16px;">Log Follow-up Call</div>
                <p style="font-size:12px;color:var(--color-outline);margin:0;" id="followup-name"></p>
            </div>
            <button onclick="closeFollowUp()" style="background:none;border:none;color:var(--color-outline);cursor:pointer;font-size:20px;">×</button>
        </div>
        <form method="POST" id="followup-form">
            @csrf
            <div style="margin-bottom:16px;">
                <label class="form-label-dark">Call Outcome</label>
                <select name="outcome" class="form-control-dark form-select-dark" required>
                    <option value="">Select outcome...</option>
                    <option value="answered">Answered — will return</option>
                    <option value="no_answer">No answer</option>
                    <option value="will_return">Confirmed will return</option>
                    <option value="cancelled">Cancelled membership</option>
                </select>
            </div>
            <div style="margin-bottom:20px;">
                <label class="form-label-dark">Notes (optional)</label>
                <textarea name="notes" class="form-control-dark form-textarea-dark" placeholder="Add any notes about this call..."></textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="closeFollowUp()" class="btn-ghost">Cancel</button>
                <button type="submit" class="btn-primary">
                    <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                    Save Call Log
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openFollowUp(memberId, memberName) {
    document.getElementById('followup-name').textContent = memberName;
    document.getElementById('followup-form').action = `/admin/inactive/${memberId}/follow-up`;
    document.getElementById('followup-modal').style.display = 'grid';
}
function closeFollowUp() {
    document.getElementById('followup-modal').style.display = 'none';
}
</script>
@endpush
@endsection
