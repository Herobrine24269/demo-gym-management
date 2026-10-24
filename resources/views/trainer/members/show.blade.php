@extends('layouts.dashboard')
@section('title', 'Member Profile')

@section('content')
<div class="page-header">
    <div style="display:flex;align-items:center;gap:14px;">
        <div style="width:48px;height:48px;border-radius:50%;background:var(--color-surface-container);border:2px solid var(--color-primary-container);overflow:hidden;display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:16px;font-weight:700;color:var(--color-primary-container);flex-shrink:0;">
            @if($member->user->avatar_url)
                <img src="{{ $member->user->avatar_url }}" alt="{{ $member->user->name }}" style="width:100%;height:100%;object-fit:cover;">
            @else
                {{ $member->initials() }}
            @endif
        </div>
        <div class="page-title-group">
            <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">TRAINER PORTAL</span></div>
            <h1 class="page-title" style="margin:0;">{{ $member->user->name }}</h1>
            <p class="page-title-sub" style="margin:2px 0 0;">{{ $member->member_code }} · Joined {{ $member->joined_at->format('M Y') }}</p>
        </div>
    </div>
    <div style="display:flex;gap:8px;align-items:center;">
        <button type="button"
            onclick="triggerCallLog(this)"
            data-member-id="{{ $member->id }}"
            data-name="{{ $member->user->name }}"
            data-code="{{ $member->member_code }}"
            data-phone="{{ $member->user->phone ?? '' }}"
            data-email="{{ $member->user->email ?? '' }}"
            data-post-url="{{ route('trainer.members.call-log', $member) }}"
            data-logs="{{ json_encode($member->followUps->sortByDesc('followed_up_at')->values()->map(fn($f) => [
                'outcome' => $f->outcome,
                'notes' => $f->notes,
                'date' => $f->followed_up_at ? $f->followed_up_at->format('d M Y, h:i A') : '',
                'caller' => $f->caller->name ?? 'Staff'
            ])) }}"
            class="btn-primary"
            style="gap:6px;font-size:11px;">
            <span class="material-symbols-outlined" style="font-size:16px;">call</span> Call Log
        </button>

        <a href="mailto:{{ $member->user->email }}?subject={{ urlencode('Kinetic Apex — Trainer Message for ' . $member->user->name) }}"
           class="btn-ghost"
           style="gap:6px;font-size:11px;border:1px solid rgba(129,207,255,0.3);color:var(--color-secondary);"
           target="_blank">
            <span class="material-symbols-outlined" style="font-size:16px;">mail</span> Email
        </a>

        <a href="{{ route('trainer.members.index') }}" class="btn-ghost" style="font-size:11px;">
            Back to Members
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
    <div class="card-dark">
        <div class="section-title" style="font-size:15px;margin-bottom:16px;">Member Stats</div>
        @foreach([
            ['Phone Number', $member->user->phone ?? 'None registered'],
            ['Email Address', $member->user->email],
            ['Attendance This Month', $attendanceThisMonth . ' sessions'],
            ['Current Streak', ($member->streak?->current_streak ?? 0) . ' days'],
            ['Longest Streak', ($member->streak?->longest_streak ?? 0) . ' days'],
            ['Active Plan', $member->activeMembership?->plan->name ?? 'No plan']
        ] as [$k,$v])
        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--color-surface-highest);">
            <span style="font-size:12px;color:var(--color-outline);">{{ $k }}</span>
            <span style="font-size:13px;font-weight:600;color:var(--color-on-surface);font-family:{{ $k === 'Phone Number' ? 'monospace' : 'inherit' }};">{{ $v }}</span>
        </div>
        @endforeach

        {{-- Follow-ups / Call logs preview --}}
        @if($member->followUps->count())
        <div style="margin-top:20px;">
            <div style="font-size:13px;font-weight:700;color:var(--color-on-surface);margin-bottom:10px;text-transform:uppercase;font-family:var(--font-heading);letter-spacing:.05em;">
                Call & Contact History ({{ $member->followUps->count() }})
            </div>
            <div style="display:flex;flex-direction:column;gap:8px;">
                @foreach($member->followUps->sortByDesc('followed_up_at')->take(4) as $fu)
                <div style="padding:10px;border-radius:6px;background:var(--color-surface-container);border:1px solid var(--color-surface-highest);">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                        <span class="{{ $fu->outcome==='answered' ? 'badge-active' : ($fu->outcome==='no_answer' ? 'badge-paused' : ($fu->outcome==='will_return' ? 'badge-watch' : 'badge-critical')) }}">
                            {{ ucfirst(str_replace('_',' ',$fu->outcome)) }}
                        </span>
                        <span style="font-size:11px;color:var(--color-outline);">{{ $fu->followed_up_at->format('d M Y, h:i A') }}</span>
                    </div>
                    @if($fu->notes)
                        <div style="font-size:12px;color:var(--color-on-surface-variant);margin-top:4px;">{{ $fu->notes }}</div>
                    @endif
                    <div style="font-size:10px;color:var(--color-outline);margin-top:4px;">By: {{ $fu->caller->name ?? 'Staff' }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
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

@include('components.call-log-modal')

@endsection
