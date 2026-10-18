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
            <thead><tr><th>Member</th><th>Code</th><th>Plan</th><th>Status</th><th>Streak</th><th>Joined</th><th style="text-align:right;">Actions</th></tr></thead>
            <tbody>
                @forelse($members as $member)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:38px;height:38px;border-radius:50%;background:var(--color-surface-highest);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:12px;font-weight:700;flex-shrink:0;">{{ $member->initials() }}</div>
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
                        <div style="display:flex;gap:4px;justify-content:flex-end;">
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
@endsection
