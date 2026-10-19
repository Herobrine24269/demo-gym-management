@extends('layouts.dashboard')
@section('title', $plan->name)

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">NUTRITION</span></div>
        <h1 class="page-title">{{ $plan->name }}</h1>
        @if($plan->goal)<p class="page-title-sub">{{ $plan->goal }}</p>@endif
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.diet-plans.edit', $plan) }}" class="btn-ghost"><span class="material-symbols-outlined" style="font-size:18px;">edit</span> Edit</a>
        <a href="{{ route('admin.diet-plans.index') }}" class="btn-ghost">← Back</a>
    </div>
</div>
<div style="max-width:640px;">
    <div class="card-dark" style="margin-bottom:16px;">
        <div class="section-title" style="font-size:14px;margin-bottom:14px;">Macros</div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;">
            @foreach([['Calories', ($plan->total_calories ?? 0).'kcal', 'local_fire_department'], ['Protein', ($plan->protein_g ?? 0).'g', 'egg'], ['Carbs', ($plan->carbs_g ?? 0).'g', 'grain'], ['Fat', ($plan->fat_g ?? 0).'g', 'water_drop']] as [$k,$v,$icon])
            <div style="text-align:center;padding:14px;border-radius:8px;background:var(--color-surface-container);">
                <span class="material-symbols-outlined" style="font-size:20px;color:var(--color-primary-container);">{{ $icon }}</span>
                <div style="font-family:var(--font-heading);font-size:18px;font-weight:700;color:var(--color-on-surface);margin-top:4px;">{{ $v }}</div>
                <div style="font-size:10px;color:var(--color-outline);text-transform:uppercase;letter-spacing:.06em;">{{ $k }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @if($plan->description)
    <div class="card-dark">
        <div class="section-title" style="font-size:14px;margin-bottom:10px;">Description</div>
        <p style="font-size:13px;color:var(--color-on-surface-variant);line-height:1.7;margin:0;">{{ $plan->description }}</p>
    </div>
    @endif
</div>
@endsection
