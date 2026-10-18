@extends('layouts.dashboard')
@section('title', 'Trainers & Staff')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">STAFF</span></div>
        <h1 class="page-title">Trainers & Staff</h1>
    </div>
    <a href="{{ route('admin.trainers.create') }}" class="btn-primary">
        <span class="material-symbols-outlined" style="font-size:18px;">person_add</span> Add Trainer
    </a>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:16px;">
    @forelse($trainers as $trainer)
    <div class="card-dark" style="display:flex;flex-direction:column;gap:0;">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;">
            <div style="width:52px;height:52px;border-radius:50%;background:var(--color-surface-container);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:18px;font-weight:700;color:var(--color-secondary);flex-shrink:0;">{{ $trainer->initials() }}</div>
            <div style="flex:1;min-width:0;">
                <div style="font-family:var(--font-heading);font-size:15px;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);">{{ $trainer->user->name }}</div>
                <div style="font-size:12px;color:var(--color-primary-container);">{{ $trainer->specialization }}</div>
            </div>
            @if($trainer->is_active)
                <span class="badge-active">Active</span>
            @else
                <span class="badge-expired">Inactive</span>
            @endif
        </div>

        <div style="display:flex;gap:12px;padding:12px;border-radius:6px;background:var(--color-surface-container);margin-bottom:12px;">
            @foreach([['Members', $trainer->active_members_count, 'group'], ['Salary', 'PKR '.number_format($trainer->salary ?? 0), 'payments']] as [$lbl,$val,$icon])
            <div style="flex:1;text-align:center;">
                <span class="material-symbols-outlined" style="font-size:16px;color:var(--color-outline);">{{ $icon }}</span>
                <div style="font-family:var(--font-heading);font-size:18px;font-weight:700;color:var(--color-on-surface);">{{ $val }}</div>
                <div style="font-size:10px;color:var(--color-outline);text-transform:uppercase;letter-spacing:.06em;">{{ $lbl }}</div>
            </div>
            @endforeach
        </div>

        @if($trainer->bio)
        <p style="font-size:12px;color:var(--color-outline);margin:0 0 12px;flex:1;">{{ Str::limit($trainer->bio, 80) }}</p>
        @endif

        <div style="display:flex;gap:8px;margin-top:auto;">
            <a href="{{ route('admin.trainers.show', $trainer) }}" class="btn-ghost btn-sm" style="flex:1;justify-content:center;">View</a>
            <a href="{{ route('admin.trainers.edit', $trainer) }}" class="btn-ghost btn-sm btn-icon" title="Edit"><span class="material-symbols-outlined" style="font-size:16px;">edit</span></a>
            <form method="POST" action="{{ route('admin.trainers.destroy', $trainer) }}" onsubmit="return confirm('Remove this trainer?');">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger btn-sm btn-icon" title="Delete"><span class="material-symbols-outlined" style="font-size:16px;">delete</span></button>
            </form>
        </div>
    </div>
    @empty
    <div class="card-dark" style="grid-column:1/-1;">
        <div class="empty-state">
            <span class="material-symbols-outlined">sports_gymnastics</span>
            <div class="empty-state-title">No Trainers Yet</div>
            <div class="empty-state-body"><a href="{{ route('admin.trainers.create') }}" style="color:var(--color-primary-container);">Add your first trainer →</a></div>
        </div>
    </div>
    @endforelse
</div>
@if($trainers->hasPages())<div style="margin-top:16px;">{{ $trainers->links() }}</div>@endif
@endsection
