@extends('layouts.dashboard')
@section('title', 'Members')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">MEMBERS</span></div>
        <h1 class="page-title">Member Directory</h1>
        <p class="page-title-sub">{{ $totalMembers }} total · {{ $activeMembers }} active</p>
    </div>
    <a href="{{ route('admin.members.create') }}" class="btn-primary">
        <span class="material-symbols-outlined" style="font-size:18px;">person_add</span> Add Member
    </a>
</div>

{{-- Filters --}}
<form method="GET" style="display:flex;gap:12px;margin-bottom:24px;flex-wrap:wrap;">
    <div style="position:relative;flex:1;min-width:220px;">
        <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--color-outline);font-size:18px;">search</span>
        <input type="text" name="q" value="{{ request('q') }}" class="form-control-dark" placeholder="Search name, phone, code..." style="padding-left:40px;">
    </div>
    <select name="status" class="form-control-dark form-select-dark" style="width:auto;min-width:140px;">
        <option value="">All Status</option>
        <option value="active"  {{ request('status')=='active'  ? 'selected' : '' }}>Active</option>
        <option value="expired" {{ request('status')=='expired' ? 'selected' : '' }}>Expired</option>
    </select>
    <button type="submit" class="btn-primary">Filter</button>
    @if(request('q') || request('status'))
    <a href="{{ route('admin.members.index') }}" class="btn-ghost">Clear</a>
    @endif
</form>

<div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="table-dark">
            <thead>
                <tr>
                    <th>Member</th><th>Code</th><th>Plan</th><th>Status</th>
                    <th>Streak</th><th>Joined</th>
                    <th style="text-align:right;">Contact & Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:38px;height:38px;border-radius:50%;background:var(--color-surface-highest);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:12px;font-weight:700;flex-shrink:0;color:var(--color-primary-container);">{{ $member->initials() }}</div>
                            <div>
                                <div class="td-name">{{ $member->user->name }}</div>
                                <div class="td-sub">{{ $member->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span style="font-family:monospace;font-size:12px;color:var(--color-primary-container);">{{ $member->member_code }}</span></td>
                    <td>
                        @if($member->activeMembership)
                            <div style="font-size:12px;font-weight:600;">{{ $member->activeMembership->plan->name }}</div>
                            <div style="font-size:10px;color:var(--color-outline);">Exp: {{ $member->activeMembership->end_date->format('d M Y') }}</div>
                        @else
                            <span class="badge-expired">No Plan</span>
                        @endif
                    </td>
                    <td>
                        @if($member->activeMembership && $member->activeMembership->isActive())
                            @if($member->activeMembership->isExpiringSoon())
                                <span class="badge-paused">Expiring</span>
                            @else
                                <span class="badge-active">Active</span>
                            @endif
                        @else
                            <span class="badge-expired">Expired</span>
                        @endif
                    </td>
                    <td>
                        @if($member->streak)
                        <span style="font-family:var(--font-heading);font-size:18px;font-weight:700;color:var(--color-primary-container);">{{ $member->streak->current_streak }}</span>
                        <span style="font-size:10px;color:var(--color-outline);">d</span>
                        @else <span style="color:var(--color-outline);">—</span> @endif
                    </td>
                    <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $member->joined_at->format('d M Y') }}</td>
                    <td style="text-align:right;">
                        <div style="display:flex;gap:4px;justify-content:flex-end;align-items:center;">

                            {{-- ── CALL button ── --}}
                            @if($member->user->phone)
                            <button
                                onclick="openCallLog({{ $member->id }}, '{{ addslashes($member->user->name) }}', '{{ $member->user->phone }}')"
                                class="btn-ghost btn-sm btn-icon"
                                title="Call {{ $member->user->name }} ({{ $member->user->phone }})">
                                <span class="material-symbols-outlined" style="font-size:16px;color:var(--color-primary-container);">call</span>
                            </button>
                            @else
                            <button class="btn-ghost btn-sm btn-icon" disabled title="No phone on file" style="opacity:.3;cursor:not-allowed;">
                                <span class="material-symbols-outlined" style="font-size:16px;">phone_disabled</span>
                            </button>
                            @endif

                            {{-- ── EMAIL button ── --}}
                            <a href="mailto:{{ $member->user->email }}?subject={{ urlencode('Kinetic Apex — Message for You') }}"
                               class="btn-ghost btn-sm btn-icon"
                               title="Email {{ $member->user->email }}">
                                <span class="material-symbols-outlined" style="font-size:16px;color:var(--color-secondary);">mail</span>
                            </a>

                            <div style="width:1px;height:18px;background:var(--color-surface-highest);margin:0 2px;"></div>

                            <a href="{{ route('admin.members.show', $member) }}" class="btn-ghost btn-sm btn-icon" title="View"><span class="material-symbols-outlined" style="font-size:16px;">visibility</span></a>
                            <a href="{{ route('admin.members.edit', $member) }}" class="btn-ghost btn-sm btn-icon" title="Edit"><span class="material-symbols-outlined" style="font-size:16px;">edit</span></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7"><div class="empty-state"><span class="material-symbols-outlined">group</span><div class="empty-state-title">No Members Found</div><div class="empty-state-body">No members match your search.</div></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($members->hasPages())
    <div style="padding:16px;">{{ $members->links() }}</div>
    @endif
</div>

{{-- ══════════════════════════════════════════════════════════════════
     Call Log Modal
     Logs the call outcome to follow_ups (same table used by inactive cases)
     ══════════════════════════════════════════════════════════════════ --}}
<div id="call-modal"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.7);backdrop-filter:blur(6px);align-items:center;justify-content:center;padding:16px;"
     onclick="if(event.target===this)closeCallModal()">

    <div style="background:var(--color-surface-low);border:1px solid var(--color-surface-highest);border-radius:14px;padding:28px;width:100%;max-width:440px;box-shadow:0 32px 80px rgba(0,0,0,.6);animation:fadeInUp .15s ease;">

        {{-- Header --}}
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                    <div style="width:32px;height:32px;border-radius:6px;background:rgba(57,255,20,.15);display:flex;align-items:center;justify-content:center;">
                        <span class="material-symbols-outlined" style="font-size:18px;color:var(--color-primary-container);">call</span>
                    </div>
                    <span style="font-family:var(--font-heading);font-size:15px;font-weight:700;text-transform:uppercase;color:var(--color-on-surface);">Log Call</span>
                </div>
                <div id="call-member-name" style="font-size:12px;color:var(--color-outline);padding-left:40px;"></div>
            </div>
            <button onclick="closeCallModal()" style="background:none;border:none;color:var(--color-outline);cursor:pointer;font-size:24px;line-height:1;padding:0;margin-top:-4px;" title="Close">×</button>
        </div>

        {{-- Phone strip with direct-call button --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-radius:8px;background:var(--color-surface-container);border:1px solid var(--color-surface-highest);margin-bottom:20px;">
            <div>
                <div style="font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:var(--color-outline);margin-bottom:4px;">Phone Number</div>
                <div id="call-phone-display" style="font-family:monospace;font-size:16px;color:var(--color-on-surface);font-weight:600;letter-spacing:.05em;"></div>
            </div>
            <a id="call-tel-link" href="#" class="btn-primary" style="padding:8px 16px;font-size:12px;" target="_blank">
                <span class="material-symbols-outlined" style="font-size:14px;">call</span> Dial
            </a>
        </div>

        {{-- Log form --}}
        <form method="POST" id="call-log-form" action="">
            @csrf
            <div style="margin-bottom:16px;">
                <label class="form-label-dark" style="margin-bottom:8px;display:block;">Call Outcome *</label>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                    @foreach([
                        ['answered',   'check_circle', 'Answered',   'var(--color-primary-container)'],
                        ['no_answer',  'call_end',     'No Answer',  'var(--color-outline)'],
                        ['will_return','schedule',     'Will Return','var(--color-secondary)'],
                        ['cancelled',  'block',        'Cancelled',  'var(--color-error)'],
                    ] as [$val, $icon, $label, $color])
                    <label class="outcome-label" style="display:flex;align-items:center;gap:8px;padding:10px 12px;border-radius:8px;background:var(--color-surface-container);border:2px solid transparent;cursor:pointer;transition:border-color .12s,background .12s;">
                        <input type="radio" name="outcome" value="{{ $val }}" required
                               style="display:none;"
                               onchange="selectOutcome(this, '{{ $color }}')">
                        <span class="material-symbols-outlined" style="font-size:16px;color:{{ $color }};flex-shrink:0;">{{ $icon }}</span>
                        <span style="font-size:12px;font-weight:600;color:var(--color-on-surface);">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <label class="form-label-dark">Notes <span style="color:var(--color-outline);font-weight:400;font-size:11px;">(optional)</span></label>
                <textarea name="notes" class="form-control-dark" style="min-height:72px;margin-top:6px;resize:vertical;" placeholder="What did the member say?"></textarea>
            </div>

            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn-primary" style="flex:1;justify-content:center;">
                    <span class="material-symbols-outlined" style="font-size:16px;">save</span> Save Call Log
                </button>
                <button type="button" onclick="closeCallModal()" class="btn-ghost">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<style>
@keyframes fadeInUp {
    from { opacity:0; transform:translateY(16px); }
    to   { opacity:1; transform:translateY(0); }
}
</style>
<script>
function openCallLog(memberId, name, phone) {
    document.getElementById('call-member-name').textContent = name + '  ·  ' + phone;
    document.getElementById('call-phone-display').textContent = phone;
    document.getElementById('call-tel-link').href = 'tel:' + phone;
    // POST to the follow-up route
    document.getElementById('call-log-form').action = '/admin/inactive/' + memberId + '/follow-up';
    // Reset outcome
    document.querySelectorAll('.outcome-label').forEach(l => {
        l.style.borderColor = 'transparent';
        l.style.background  = 'var(--color-surface-container)';
    });
    document.querySelectorAll('input[name="outcome"]').forEach(r => r.checked = false);
    document.querySelector('textarea[name="notes"]').value = '';
    const modal = document.getElementById('call-modal');
    modal.style.display = 'flex';
}

function closeCallModal() {
    document.getElementById('call-modal').style.display = 'none';
}

function selectOutcome(radio, color) {
    document.querySelectorAll('.outcome-label').forEach(l => {
        l.style.borderColor = 'transparent';
        l.style.background  = 'var(--color-surface-container)';
    });
    radio.parentElement.style.borderColor = color;
    radio.parentElement.style.background  = 'rgba(57,255,20,.05)';
}
</script>
@endpush
@endsection
