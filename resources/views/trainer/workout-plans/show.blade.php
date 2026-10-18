@extends('layouts.dashboard')
@section('title', $workoutPlan->title)

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">TRAINER</span></div>
        <h1 class="page-title">{{ $workoutPlan->title }}</h1>
        <p class="page-title-sub">{{ $workoutPlan->member->user->name }} · {{ $workoutPlan->weeks }} weeks</p>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('trainer.workout-plans.edit', $workoutPlan) }}" class="btn-ghost">
            <span class="material-symbols-outlined" style="font-size:18px;">edit</span> Edit
        </a>
        <a href="{{ route('trainer.workout-plans.index') }}" class="btn-ghost">← Back</a>
    </div>
</div>
<div style="max-width:640px;">
    <div class="card-dark">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
            @foreach([['Member',$workoutPlan->member->user->name],['Duration',$workoutPlan->weeks.' weeks'],['Status',ucfirst($workoutPlan->status)],['Created',$workoutPlan->created_at->format('d M Y')]] as [$k,$v])
            <div style="padding:12px;border-radius:6px;background:var(--color-surface-container);">
                <div style="font-size:10px;color:var(--color-outline);text-transform:uppercase;letter-spacing:.06em;font-family:var(--font-heading);">{{ $k }}</div>
                <div style="font-size:14px;font-weight:600;color:var(--color-on-surface);margin-top:4px;">{{ $v }}</div>
            </div>
            @endforeach
        </div>
        @if($workoutPlan->description)
        <div>
            <div class="form-label-dark" style="margin-bottom:8px;">Notes & Description</div>
            <p style="font-size:13px;color:var(--color-on-surface-variant);line-height:1.6;">{{ $workoutPlan->description }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
