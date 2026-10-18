@extends('layouts.dashboard')
@section('title', $trainer->user->name)

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">STAFF</span></div>
        <h1 class="page-title">{{ $trainer->user->name }}</h1>
        <p class="page-title-sub">{{ $trainer->specialization }}</p>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.trainers.edit', $trainer) }}" class="btn-ghost">
            <span class="material-symbols-outlined" style="font-size:18px;">edit</span> Edit
        </a>
        <a href="{{ route('admin.trainers.index') }}" class="btn-ghost">← Back</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:20px;">
    <div style="display:flex;flex-direction:column;gap:16px;">
        <div class="card-dark" style="text-align:center;">
            <div style="width:72px;height:72px;border-radius:50%;background:var(--color-surface-container);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:28px;font-weight:700;color:var(--color-secondary);margin:0 auto 12px;">{{ $trainer->initials() }}</div>
            <div style="font-family:var(--font-heading);font-size:16px;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);">{{ $trainer->user->name }}</div>
            <div style="font-size:12px;color:var(--color-outline);margin:4px 0 8px;">{{ $trainer->user->email }}</div>
            @if($trainer->is_active) <span class="badge-active">Active</span> @else <span class="badge-expired">Inactive</span> @endif
        </div>
        <div class="card-dark">
            @foreach([['Specialization',$trainer->specialization??'—'],['Phone',$trainer->user->phone??'—'],['Salary','PKR '.number_format($trainer->salary??0)],['Hired',($trainer->hired_at??now())->format('d M Y')]] as [$k,$v])
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--color-surface-highest);">
                <span style="font-size:11px;color:var(--color-outline);text-transform:uppercase;letter-spacing:.06em;font-family:var(--font-heading);">{{ $k }}</span>
                <span style="font-size:13px;font-weight:600;color:var(--color-on-surface);">{{ $v }}</span>
            </div>
            @endforeach
        </div>
    </div>
    <div style="display:flex;flex-direction:column;gap:16px;">
        <div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
            <div style="padding:14px 16px;background:var(--color-surface-container);">
                <div class="section-title" style="font-size:14px;">Assigned Members ({{ $trainer->assignments->count() }})</div>
            </div>
            <table class="table-dark">
                <thead><tr><th>Member</th><th>Plan</th><th>Since</th></tr></thead>
                <tbody>
                @forelse($trainer->assignments->take(10) as $assign)
                <tr>
                    <td>
                        <div class="td-name">{{ $assign->member->user->name }}</div>
                        <div class="td-sub">{{ $assign->member->member_code }}</div>
                    </td>
                    <td>
                        @if($assign->member->activeMembership) <span class="badge-active">{{ $assign->member->activeMembership->plan->name }}</span>
                        @else <span class="badge-expired">No Plan</span> @endif
                    </td>
                    <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $assign->assigned_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;padding:20px;font-size:12px;color:var(--color-outline);">No members assigned.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
            <div style="padding:14px 16px;background:var(--color-surface-container);">
                <div class="section-title" style="font-size:14px;">Active Workout Plans</div>
            </div>
            <table class="table-dark">
                <thead><tr><th>Plan</th><th>Member</th><th>Duration</th></tr></thead>
                <tbody>
                @forelse($trainer->workoutPlans->take(8) as $plan)
                <tr>
                    <td class="td-name">{{ $plan->title }}</td>
                    <td style="font-size:12px;">{{ $plan->member->user->name }}</td>
                    <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $plan->weeks }} wks</td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;padding:16px;font-size:12px;color:var(--color-outline);">No workout plans.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<style>@media(max-width:900px){div[style*="grid-template-columns:1fr 2fr"]{grid-template-columns:1fr!important;}}</style>
@endpush
@endsection
