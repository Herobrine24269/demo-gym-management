@extends('layouts.dashboard')
@section('title', 'Workout Plans')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">TRAINER</span></div>
        <h1 class="page-title">Workout Plans</h1>
    </div>
    <a href="{{ route('trainer.workout-plans.create') }}" class="btn-primary">
        <span class="material-symbols-outlined" style="font-size:18px;">add</span> New Plan
    </a>
</div>

<div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="table-dark">
            <thead><tr><th>Plan Title</th><th>Member</th><th>Duration</th><th>Status</th><th style="text-align:right;">Actions</th></tr></thead>
            <tbody>
                @forelse($plans as $plan)
                <tr>
                    <td>
                        <div class="td-name">{{ $plan->title }}</div>
                        @if($plan->description)<div class="td-sub">{{ Str::limit($plan->description, 60) }}</div>@endif
                    </td>
                    <td style="font-size:13px;">{{ $plan->member->user->name }}</td>
                    <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $plan->weeks }} weeks</td>
                    <td>
                        @if($plan->status === 'active') <span class="badge-active">Active</span>
                        @elseif($plan->status === 'completed') <span class="badge-watch">Completed</span>
                        @else <span class="badge-paused">Paused</span> @endif
                    </td>
                    <td style="text-align:right;">
                        <div style="display:flex;gap:4px;justify-content:flex-end;">
                            <a href="{{ route('trainer.workout-plans.show', $plan) }}" class="btn-ghost btn-sm btn-icon" title="View"><span class="material-symbols-outlined" style="font-size:16px;">visibility</span></a>
                            <a href="{{ route('trainer.workout-plans.edit', $plan) }}" class="btn-ghost btn-sm btn-icon" title="Edit"><span class="material-symbols-outlined" style="font-size:16px;">edit</span></a>
                            <form method="POST" action="{{ route('trainer.workout-plans.destroy', $plan) }}" onsubmit="return confirm('Delete this plan?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger btn-sm btn-icon"><span class="material-symbols-outlined" style="font-size:16px;">delete</span></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state"><span class="material-symbols-outlined">fitness_center</span><div class="empty-state-title">No Workout Plans Yet</div><div class="empty-state-body"><a href="{{ route('trainer.workout-plans.create') }}" style="color:var(--color-primary-container);">Create your first plan →</a></div></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($plans->hasPages())<div style="padding:16px;">{{ $plans->links() }}</div>@endif
</div>
@endsection
