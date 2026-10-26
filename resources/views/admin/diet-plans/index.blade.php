@extends('layouts.dashboard')
@section('title', 'Diet Plans')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">NUTRITION</span></div>
        <h1 class="page-title">Diet Plans</h1>
    </div>
    <a href="{{ route('admin.diet-plans.create') }}" class="btn-primary">
        <span class="material-symbols-outlined" style="font-size:18px;">add</span> New Plan
    </a>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;">
    @forelse($plans as $plan)
    <div class="card-dark" style="display:flex;flex-direction:column;">
        @if($plan->cover_image_url)
        <div style="height:120px;margin:-16px -16px 14px -16px;border-radius:12px 12px 0 0;overflow:hidden;position:relative;">
            <img src="{{ $plan->cover_image_url }}" alt="{{ $plan->title ?? $plan->name }}" style="width:100%;height:100%;object-fit:cover;">
            <span class="badge-green" style="position:absolute;top:10px;left:10px;font-size:9px;text-transform:uppercase;">
                {{ str_replace('_', ' ', $plan->goal ?? 'Nutrition') }}
            </span>
        </div>
        @endif
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
            <div>
                <div style="font-family:var(--font-heading);font-size:16px;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);">{{ $plan->title ?? $plan->name }}</div>
                <div style="font-size:12px;color:var(--color-primary-container);">{{ str_replace('_', ' ', $plan->goal) }}</div>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:12px;">
            @foreach([['Calories', $plan->total_calories.'kcal'], ['Protein', $plan->protein_g.'g'], ['Carbs', $plan->carbs_g.'g'], ['Fat', $plan->fat_g.'g']] as [$k,$v])
            <div style="padding:8px;border-radius:6px;background:var(--color-surface-container);text-align:center;">
                <div style="font-family:var(--font-heading);font-size:14px;font-weight:600;color:var(--color-on-surface);">{{ $v }}</div>
                <div style="font-size:10px;color:var(--color-outline);text-transform:uppercase;letter-spacing:.06em;">{{ $k }}</div>
            </div>
            @endforeach
        </div>
        @if($plan->description)<p style="font-size:12px;color:var(--color-outline);flex:1;margin:0 0 12px;">{{ Str::limit($plan->description, 80) }}</p>@endif
        <div style="display:flex;gap:8px;margin-top:auto;">
            <a href="{{ route('admin.diet-plans.show', $plan) }}" class="btn-ghost btn-sm" style="flex:1;justify-content:center;">View</a>
            <a href="{{ route('admin.diet-plans.edit', $plan) }}" class="btn-ghost btn-sm btn-icon" title="Edit"><span class="material-symbols-outlined" style="font-size:16px;">edit</span></a>
            <form method="POST" action="{{ route('admin.diet-plans.destroy', $plan) }}" onsubmit="return confirm('Delete this diet plan?');">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger btn-sm btn-icon"><span class="material-symbols-outlined" style="font-size:16px;">delete</span></button>
            </form>
        </div>
    </div>
    @empty
    <div class="card-dark" style="grid-column:1/-1;"><div class="empty-state"><span class="material-symbols-outlined">restaurant_menu</span><div class="empty-state-title">No Diet Plans</div><div class="empty-state-body"><a href="{{ route('admin.diet-plans.create') }}" style="color:var(--color-primary-container);">Create first plan →</a></div></div></div>
    @endforelse
</div>
@endsection
