@extends('layouts.dashboard')
@section('title', 'Member Profile')
@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">TRAINER PORTAL</span></div>
        <h1 class="page-title">{{ $member->user->name }}</h1>
        <p class="page-title-sub">{{ $member->member_code }} · Joined {{ $member->joined_at->format('M Y') }}</p>
    </div>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
    <div class="card-dark">
        <div class="section-title" style="font-size:15px;margin-bottom:16px;">Member Stats</div>
        @foreach([['Attendance This Month',$attendanceThisMonth.' sessions'],['Current Streak',($member->streak?->current_streak ?? 0).' days'],['Longest Streak',($member->streak?->longest_streak ?? 0).' days'],['Active Plan',$member->activeMembership?->plan->name ?? 'No plan']] as [$k,$v])
        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--color-surface-highest);">
            <span style="font-size:12px;color:var(--color-outline);">{{ $k }}</span>
            <span style="font-size:13px;font-weight:600;color:var(--color-on-surface);">{{ $v }}</span>
        </div>
        @endforeach
    </div>
    <div class="card-dark">
        <div class="section-title" style="font-size:15px;margin-bottom:16px;">Workout Plans</div>
        @forelse($member->workoutPlans as $wp)
        <div style="padding:10px;border-radius:6px;background:var(--color-surface-container);margin-bottom:8px;">
            <div style="font-weight:600;font-size:13px;">{{ $wp->title }}</div>
            <div style="font-size:11px;color:var(--color-outline);">{{ $wp->weeks }} weeks · {{ ucfirst($wp->status) }}</div>
        </div>
        @empty
        <p style="font-size:12px;color:var(--color-outline);">No workout plans assigned.</p>
        @endforelse
    </div>
</div>
@endsection
