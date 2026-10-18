@extends('layouts.dashboard')
@section('title', 'Membership Plans')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">PLANS</span></div>
        <h1 class="page-title">Membership Plans</h1>
    </div>
    <a href="{{ route('admin.plans.create') }}" class="btn-primary">
        <span class="material-symbols-outlined" style="font-size:18px;">add</span> New Plan
    </a>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;">
    @forelse($plans as $plan)
    <div class="card-dark" style="display:flex;flex-direction:column;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
            <div>
                <div style="font-family:var(--font-heading);font-size:18px;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);">{{ $plan->name }}</div>
                <div style="font-size:12px;color:var(--color-outline);">{{ $plan->duration_days }} days</div>
            </div>
            @if($plan->is_active) <span class="badge-active">Active</span> @else <span class="badge-expired">Inactive</span> @endif
        </div>
        <div style="font-family:var(--font-heading);font-size:36px;font-weight:700;color:var(--color-primary-container);margin-bottom:12px;">PKR {{ number_format($plan->price) }}</div>
        @if($plan->features)
        <ul style="list-style:none;padding:0;margin:0 0 16px;flex:1;display:flex;flex-direction:column;gap:6px;">
            @foreach($plan->features as $feat)
            <li style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--color-on-surface-variant);">
                <span class="material-symbols-outlined" style="font-size:14px;color:var(--color-primary-container);">check</span> {{ $feat }}
            </li>
            @endforeach
        </ul>
        @endif
        <div style="padding-top:12px;border-top:1px solid var(--color-surface-highest);display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:11px;color:var(--color-outline);">{{ $plan->memberships_count }} total memberships</span>
            <div style="display:flex;gap:4px;">
                <a href="{{ route('admin.plans.edit', $plan) }}" class="btn-ghost btn-sm btn-icon" title="Edit"><span class="material-symbols-outlined" style="font-size:16px;">edit</span></a>
                <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('Delete this plan?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger btn-sm btn-icon" title="Delete"><span class="material-symbols-outlined" style="font-size:16px;">delete</span></button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="card-dark" style="grid-column:1/-1;"><div class="empty-state"><span class="material-symbols-outlined">card_membership</span><div class="empty-state-title">No Plans Yet</div><div class="empty-state-body"><a href="{{ route('admin.plans.create') }}" style="color:var(--color-primary-container);">Create first plan →</a></div></div></div>
    @endforelse
</div>
@endsection
