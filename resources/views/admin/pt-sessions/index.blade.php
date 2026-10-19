@extends('layouts.dashboard')
@section('title', 'Personal Training Sessions')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">PT</span></div>
        <h1 class="page-title">Personal Training Sessions</h1>
    </div>
    <a href="{{ route('admin.pt-sessions.create') }}" class="btn-primary">
        <span class="material-symbols-outlined" style="font-size:18px;">add</span> Book Session
    </a>
</div>

<div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="table-dark">
            <thead><tr><th>Member</th><th>Trainer</th><th>Session Date</th><th>Duration</th><th>Status</th><th>Amount</th><th style="text-align:right;">Actions</th></tr></thead>
            <tbody>
            @forelse($sessions as $session)
            <tr>
                <td>
                    <div class="td-name">{{ $session->member->user->name }}</div>
                    <div class="td-sub">{{ $session->member->member_code }}</div>
                </td>
                <td style="font-size:13px;">{{ $session->trainer->user->name }}</td>
                <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ \Carbon\Carbon::parse($session->scheduled_at)->format('d M Y, H:i') }}</td>
                <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $session->duration_minutes }} min</td>
                <td>
                    @if($session->status === 'completed') <span class="badge-active">Completed</span>
                    @elseif($session->status === 'scheduled') <span class="badge-watch">Scheduled</span>
                    @elseif($session->status === 'cancelled') <span class="badge-expired">Cancelled</span>
                    @else <span class="badge-paused">{{ ucfirst($session->status) }}</span> @endif
                </td>
                <td style="font-size:13px;font-weight:600;">PKR {{ number_format($session->amount ?? 0) }}</td>
                <td style="text-align:right;">
                    <div style="display:flex;gap:4px;justify-content:flex-end;">
                        <a href="{{ route('admin.pt-sessions.edit', $session) }}" class="btn-ghost btn-sm btn-icon" title="Edit"><span class="material-symbols-outlined" style="font-size:16px;">edit</span></a>
                        <form method="POST" action="{{ route('admin.pt-sessions.destroy', $session) }}" onsubmit="return confirm('Delete this session?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger btn-sm btn-icon"><span class="material-symbols-outlined" style="font-size:16px;">delete</span></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7"><div class="empty-state"><span class="material-symbols-outlined">fitness_center</span><div class="empty-state-title">No PT Sessions Yet</div></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($sessions->hasPages())<div style="padding:16px;">{{ $sessions->links() }}</div>@endif
</div>
@endsection
