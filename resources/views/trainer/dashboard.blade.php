@extends('layouts.dashboard')
@section('title', 'Trainer Dashboard')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow">
            <span class="eyebrow-dot"></span>
            <span class="eyebrow-text">TRAINER PORTAL</span>
        </div>
        <h1 class="page-title">My Dashboard</h1>
        <p class="page-title-sub">{{ $trainer->specialization ?? 'Performance Coach' }}</p>
    </div>
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        <a href="{{ route('trainer.posts.create') }}" class="btn-primary">
            <span class="material-symbols-outlined" style="font-size:18px;">edit_square</span> Write Post
        </a>
        <a href="{{ route('trainer.posts.index') }}" class="btn-ghost">
            <span class="material-symbols-outlined" style="font-size:18px;">article</span> My Posts
        </a>
    </div>
</div>

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:32px;">
    @foreach([
        ['My Members',      $myMembers->count(),        'group',          'var(--color-on-surface)'],
        ['Active Plans',    $activePlans->count(),       'fitness_center', 'var(--color-primary-container)'],
        ['PT Packages',     $ptSessions->count(),        'sports',         'var(--color-secondary)'],
        ['In Today',        $totalCheckInsToday,         'how_to_reg',     'var(--color-primary-container)'],
        ['My Posts',        $myPostsCount ?? 0,          'article',        'var(--color-on-surface)'],
    ] as [$lbl,$val,$icon,$clr])
    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div class="stat-label">{{ $lbl }}</div>
            <span class="material-symbols-outlined" style="font-size:20px;color:{{ $clr }};">{{ $icon }}</span>
        </div>
        <div style="font-family:var(--font-heading);font-size:40px;font-weight:700;color:{{ $clr }};margin-top:6px;">{{ $val }}</div>
    </div>
    @endforeach
</div>

{{-- My Members --}}
<div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;margin-bottom:24px;">
    <div style="padding:16px;background:var(--color-surface-container);display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:10px;">
            <span class="material-symbols-outlined" style="color:var(--color-primary-container);font-size:20px;">group</span>
            <div class="section-title" style="font-size:15px;">My Active Members ({{ $myMembers->count() }})</div>
        </div>
        <a href="{{ route('trainer.members.index') }}" class="btn-ghost btn-sm">View All</a>
    </div>
    <div style="overflow-x:auto;">
        <table class="table-dark">
            <thead><tr><th>Member</th><th>Plan</th><th>Streak</th><th>Workout Plan</th><th style="text-align:right;">Action</th></tr></thead>
            <tbody>
                @forelse($myMembers->take(8) as $assign)
                @php $m = $assign->member; $ms = $m->activeMembership; @endphp
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
                        @if($ms) <span class="badge-active">{{ $ms->plan->name }}</span>
                        @else    <span class="badge-expired">No Plan</span> @endif
                    </td>
                    <td>
                        @php $streak = $m->streak; @endphp
                        @if($streak)
                        <span style="font-family:var(--font-heading);font-size:16px;font-weight:700;color:var(--color-primary-container);">{{ $streak->current_streak }}</span>
                        <span style="font-size:11px;color:var(--color-outline);">days</span>
                        @else <span style="font-size:12px;color:var(--color-outline);">—</span> @endif
                    </td>
                    <td>
                        @php $wp = $m->workoutPlans->where('status','active')->first(); @endphp
                        @if($wp) <span class="badge-watch">{{ Str::limit($wp->title,25) }}</span>
                        @else    <span style="font-size:12px;color:var(--color-outline);">No plan assigned</span> @endif
                    </td>
                    <td style="text-align:right;">
                        <a href="{{ route('trainer.members.show', $m) }}" class="btn-ghost btn-sm">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:24px;font-size:12px;color:var(--color-outline);">No members assigned to you yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Active Workout Plans --}}
<div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
    <div style="padding:16px;background:var(--color-surface-container);display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:10px;">
            <span class="material-symbols-outlined" style="color:var(--color-secondary);font-size:20px;">fitness_center</span>
            <div class="section-title" style="font-size:15px;">My Active Workout Plans</div>
        </div>
        <a href="{{ route('trainer.workout-plans.create') }}" class="btn-primary btn-sm">
            <span class="material-symbols-outlined" style="font-size:14px;">add</span> New Plan
        </a>
    </div>
    <div style="overflow-x:auto;">
        <table class="table-dark">
            <thead><tr><th>Plan</th><th>Member</th><th>Duration</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($activePlans as $plan)
                <tr>
                    <td>
                        <div class="td-name">{{ $plan->title }}</div>
                        <div class="td-sub">{{ $plan->description ? Str::limit($plan->description, 50) : '—' }}</div>
                    </td>
                    <td style="font-size:13px;">{{ $plan->member->user->name }}</td>
                    <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $plan->weeks }} weeks</td>
                    <td><span class="badge-active">{{ ucfirst($plan->status) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;padding:24px;font-size:12px;color:var(--color-outline);">No active workout plans. <a href="{{ route('trainer.workout-plans.create') }}" style="color:var(--color-primary-container);">Create one →</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
