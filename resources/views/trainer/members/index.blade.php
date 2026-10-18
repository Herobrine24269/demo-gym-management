{{-- Trainer members index --}}
@extends('layouts.dashboard')
@section('title', 'My Members')
@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">TRAINER PORTAL</span></div>
        <h1 class="page-title">My Members</h1>
    </div>
</div>
<div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
    <div style="padding:16px;background:var(--color-surface-container);">
        <div class="section-title" style="font-size:15px;">Active Assignments ({{ $assignments->total() }})</div>
    </div>
    <div style="overflow-x:auto;">
        <table class="table-dark">
            <thead><tr><th>Member</th><th>Plan</th><th>Streak</th><th style="text-align:right;">Action</th></tr></thead>
            <tbody>
                @forelse($assignments as $assign)
                @php $m = $assign->member; @endphp
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;border-radius:50%;background:var(--color-surface-highest);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:12px;font-weight:700;">{{ $m->initials() }}</div>
                            <div>
                                <div class="td-name">{{ $m->user->name }}</div>
                                <div class="td-sub">{{ $m->member_code }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($m->activeMembership) <span class="badge-active">{{ $m->activeMembership->plan->name }}</span>
                        @else <span class="badge-expired">No Plan</span> @endif
                    </td>
                    <td>
                        @if($m->streak)
                            <span style="font-family:var(--font-heading);font-size:18px;font-weight:700;color:var(--color-primary-container);">{{ $m->streak->current_streak }}</span>
                            <span style="font-size:11px;color:var(--color-outline);">days</span>
                        @else — @endif
                    </td>
                    <td style="text-align:right;"><a href="{{ route('trainer.members.show', $m) }}" class="btn-ghost btn-sm">View</a></td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;padding:24px;color:var(--color-outline);">No members assigned yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($assignments->hasPages())
    <div style="padding:16px;">{{ $assignments->links() }}</div>
    @endif
</div>
@endsection
