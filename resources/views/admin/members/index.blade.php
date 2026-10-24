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
                            <div style="width:38px;height:38px;border-radius:50%;background:var(--color-surface-highest);overflow:hidden;display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:12px;font-weight:700;flex-shrink:0;color:var(--color-primary-container);border:1px solid var(--color-surface-highest);">
                                @if($member->user->avatar_url)
                                    <img src="{{ $member->user->avatar_url }}" alt="{{ $member->user->name }}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    {{ $member->initials() }}
                                @endif
                            </div>
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
                        <div style="display:flex;gap:6px;justify-content:flex-end;align-items:center;flex-wrap:nowrap;">

                            {{-- ── CALL LOG button ── --}}
                            <button type="button"
                                onclick="triggerCallLog(this)"
                                data-member-id="{{ $member->id }}"
                                data-name="{{ $member->user->name }}"
                                data-code="{{ $member->member_code }}"
                                data-phone="{{ $member->user->phone ?? '' }}"
                                data-email="{{ $member->user->email ?? '' }}"
                                data-post-url="{{ route('admin.members.call-log', $member) }}"
                                data-logs="{{ json_encode($member->followUps->sortByDesc('followed_up_at')->values()->map(fn($f) => [
                                    'outcome' => $f->outcome,
                                    'notes' => $f->notes,
                                    'date' => $f->followed_up_at ? $f->followed_up_at->format('d M Y, h:i A') : '',
                                    'caller' => $f->caller->name ?? 'Staff'
                                ])) }}"
                                class="btn-ghost btn-sm"
                                style="display:inline-flex;align-items:center;gap:5px;padding:6px 10px;font-size:11px;border:1px solid rgba(57,255,20,0.25);"
                                title="Call {{ $member->user->name }} & view call logs">
                                <span class="material-symbols-outlined" style="font-size:15px;color:var(--color-primary-container);">call</span>
                                <span>Call Log</span>
                                @if($member->followUps->count())
                                    <span style="background:rgba(57,255,20,0.18);color:var(--color-primary-container);padding:1px 5px;border-radius:10px;font-size:10px;font-weight:700;">{{ $member->followUps->count() }}</span>
                                @endif
                            </button>

                            {{-- ── EMAIL button ── --}}
                            <a href="mailto:{{ $member->user->email }}?subject={{ urlencode('Kinetic Apex — Message for ' . $member->user->name) }}"
                               class="btn-ghost btn-sm"
                               style="display:inline-flex;align-items:center;gap:5px;padding:6px 10px;font-size:11px;border:1px solid rgba(129,207,255,0.25);"
                               title="Send Email to {{ $member->user->email }}"
                               target="_blank">
                                <span class="material-symbols-outlined" style="font-size:15px;color:var(--color-secondary);">mail</span>
                                <span>Email</span>
                            </a>

                            <div style="width:1px;height:18px;background:var(--color-surface-highest);margin:0 2px;"></div>

                            <a href="{{ route('admin.members.show', $member) }}" class="btn-ghost btn-sm btn-icon" title="View Profile"><span class="material-symbols-outlined" style="font-size:16px;">visibility</span></a>
                            <a href="{{ route('admin.members.edit', $member) }}" class="btn-ghost btn-sm btn-icon" title="Edit Member"><span class="material-symbols-outlined" style="font-size:16px;">edit</span></a>

                            {{-- Delete User / Member Button --}}
                            <form method="POST" action="{{ route('admin.members.destroy', $member) }}" style="margin:0;display:inline;" onsubmit="return confirm('Permanently delete member &quot;{{ addslashes($member->user->name) }}&quot; ({{ $member->member_code }})? This will deactivate user login access.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger btn-sm btn-icon" title="Delete Member & Account">
                                    <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                                </button>
                            </form>
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

@include('components.call-log-modal')
@endsection
