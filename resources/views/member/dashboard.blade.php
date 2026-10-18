@extends('layouts.dashboard')
@section('title', 'My Dashboard')

@section('content')
@php
    $ms      = $member->activeMembership;
    $streak  = $member->streak;
    $wp      = $member->workoutPlans->where('status','active')->first();
    $diet    = $member->dietAssignments->first()?->dietPlan;
    $ptPack  = $member->ptSessions->where('status','active')->first();
@endphp

<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow">
            <span class="eyebrow-dot"></span>
            <span class="eyebrow-text">MEMBER PORTAL</span>
        </div>
        <h1 class="page-title">Welcome, {{ explode(' ', auth()->user()->name)[0] }}!</h1>
        <p class="page-title-sub">{{ $member->member_code }} · Member since {{ $member->joined_at->format('F Y') }}</p>
    </div>
    @if($ms)
    <div style="text-align:right;">
        <div class="badge-active" style="font-size:12px;padding:4px 12px;">{{ $ms->plan->name }}</div>
        <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">Expires {{ $ms->end_date->format('d M Y') }} · {{ $ms->daysRemaining() }}d left</div>
    </div>
    @endif
</div>

{{-- Stat cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:32px;">
    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div class="stat-label">This Month</div>
            <span class="material-symbols-outlined" style="font-size:18px;color:var(--color-primary-container);">calendar_month</span>
        </div>
        <div style="font-family:var(--font-heading);font-size:48px;font-weight:700;color:var(--color-primary-container);margin-top:4px;">{{ $attendanceThisMonth }}</div>
        <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">Sessions attended</div>
    </div>

    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div class="stat-label">Current Streak</div>
            <span class="material-symbols-outlined" style="font-size:18px;color:var(--color-primary-container);">local_fire_department</span>
        </div>
        <div style="font-family:var(--font-heading);font-size:48px;font-weight:700;color:var(--color-primary-container);margin-top:4px;">{{ $streak?->current_streak ?? 0 }}</div>
        <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">Days in a row · Best: {{ $streak?->longest_streak ?? 0 }}d</div>
    </div>

    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div class="stat-label">Days Left</div>
            <span class="material-symbols-outlined" style="font-size:18px;color:{{ $ms && $ms->daysRemaining() <= 7 ? 'var(--color-error)' : 'var(--color-on-surface)' }};">event_available</span>
        </div>
        <div style="font-family:var(--font-heading);font-size:48px;font-weight:700;color:{{ $ms && $ms->daysRemaining() <= 7 ? 'var(--color-error)' : 'var(--color-on-surface)' }};margin-top:4px;">{{ $ms?->daysRemaining() ?? '—' }}</div>
        <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">{{ $ms ? $ms->plan->name : 'No active plan' }}</div>
    </div>

    @if($ptPack)
    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div class="stat-label">PT Sessions</div>
            <span class="material-symbols-outlined" style="font-size:18px;color:var(--color-secondary);">fitness_center</span>
        </div>
        <div style="font-family:var(--font-heading);font-size:48px;font-weight:700;color:var(--color-secondary);margin-top:4px;">{{ $ptPack->sessionsRemaining() }}</div>
        <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">Remaining of {{ $ptPack->total_sessions }}</div>
    </div>
    @endif
</div>

{{-- Last 14 days attendance visual --}}
<div class="card-dark" style="margin-bottom:24px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <div class="section-title" style="font-size:16px;">Attendance — Last 14 Days</div>
        <a href="{{ route('member.attendance.index') }}" class="btn-ghost btn-sm">Full History</a>
    </div>
    <div style="display:flex;gap:6px;flex-wrap:wrap;">
        @foreach(range(13, 0) as $d)
        @php
            $date = today()->subDays($d);
            $key  = $date->format('Y-m-d');
            $hit  = isset($attendanceMap[$key]);
        @endphp
        <div style="flex:1;min-width:40px;text-align:center;">
            <div style="height:32px;border-radius:4px;background:{{ $hit ? 'var(--color-primary-container)' : 'var(--color-surface-highest)' }};box-shadow:{{ $hit ? 'var(--glow-green)' : 'none' }};transition:all .2s;" title="{{ $date->format('D d M') . ($hit ? ' — Attended' : ' — Absent') }}"></div>
            <div style="font-size:10px;color:var(--color-outline);margin-top:4px;font-family:var(--font-heading);letter-spacing:.04em;">{{ $date->format('D')[0] }}</div>
        </div>
        @endforeach
    </div>
    <div style="display:flex;align-items:center;gap:16px;margin-top:12px;padding-top:12px;border-top:1px solid var(--color-surface-highest);">
        <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:var(--color-on-surface-variant);">
            <span style="width:12px;height:12px;border-radius:2px;background:var(--color-primary-container);display:inline-block;"></span> Attended
        </div>
        <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:var(--color-on-surface-variant);">
            <span style="width:12px;height:12px;border-radius:2px;background:var(--color-surface-highest);display:inline-block;"></span> Absent
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
    {{-- Workout Plan --}}
    <div class="card-dark">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <span class="material-symbols-outlined" style="font-size:20px;color:var(--color-secondary);">fitness_center</span>
                <div class="section-title" style="font-size:15px;">My Workout Plan</div>
            </div>
        </div>
        @if($wp)
        <div>
            <div style="font-family:var(--font-heading);font-size:16px;font-weight:600;color:var(--color-on-surface);text-transform:uppercase;margin-bottom:4px;">{{ $wp->title }}</div>
            <div style="font-size:12px;color:var(--color-outline);margin-bottom:12px;">{{ $wp->weeks }} weeks · by {{ $wp->trainer->user->name ?? '—' }}</div>
            <span class="badge-active">Active</span>
        </div>
        @else
        <div class="empty-state" style="padding:24px;">
            <span class="material-symbols-outlined" style="font-size:36px;color:var(--color-outline);">fitness_center</span>
            <div style="font-size:12px;color:var(--color-outline);margin-top:8px;">No workout plan assigned yet. Contact your trainer.</div>
        </div>
        @endif
    </div>

    {{-- Diet Plan --}}
    <div class="card-dark">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <span class="material-symbols-outlined" style="font-size:20px;color:var(--color-primary-container);">restaurant</span>
                <div class="section-title" style="font-size:15px;">My Diet Plan</div>
            </div>
        </div>
        @if($diet)
        <div>
            <div style="font-family:var(--font-heading);font-size:16px;font-weight:600;color:var(--color-on-surface);text-transform:uppercase;margin-bottom:4px;">{{ $diet->title }}</div>
            <div style="display:flex;align-items:center;gap:16px;margin-top:8px;flex-wrap:wrap;">
                @foreach([['🔥',$diet->total_calories,'kcal'],['💪',$diet->total_protein_g.'g','Protein'],['🌾',$diet->total_carbs_g.'g','Carbs'],['🥑',$diet->total_fat_g.'g','Fat']] as [$em,$val,$lbl])
                <div style="text-align:center;">
                    <div style="font-family:var(--font-heading);font-size:18px;font-weight:700;color:var(--color-on-surface);">{{ $em }} {{ $val }}</div>
                    <div style="font-size:10px;color:var(--color-outline);text-transform:uppercase;letter-spacing:.06em;">{{ $lbl }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="empty-state" style="padding:24px;">
            <span class="material-symbols-outlined" style="font-size:36px;color:var(--color-outline);">restaurant</span>
            <div style="font-size:12px;color:var(--color-outline);margin-top:8px;">No diet plan assigned yet. Contact your trainer.</div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<style>@media(max-width:900px){div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important;}}</style>
@endpush
@endsection
