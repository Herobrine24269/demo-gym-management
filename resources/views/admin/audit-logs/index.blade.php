@extends('layouts.dashboard')
@section('title', 'Audit Log')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">SECURITY</span></div>
        <h1 class="page-title">Activity History</h1>
    </div>
</div>
<form method="GET" style="display:flex;gap:12px;margin-bottom:24px;flex-wrap:wrap;">
    <select name="action" class="form-control-dark form-select-dark" style="width:auto;min-width:200px;">
        <option value="">All Actions</option>
        @foreach($actions as $act)
        <option value="{{ $act }}" {{ request('action')==$act ? 'selected':'' }}>{{ ucfirst(str_replace('_',' ',$act)) }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-primary">Filter</button>
    @if(request('action'))<a href="{{ route('admin.audit-logs.index') }}" class="btn-ghost">Clear</a>@endif
</form>
<div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="table-dark">
            <thead><tr><th>Action</th><th>Target</th><th>By</th><th>IP</th><th>Time</th></tr></thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td><span class="badge-secondary">{{ ucfirst(str_replace('_',' ',$log->action)) }}</span></td>
                    <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $log->target_type }} #{{ $log->target_id }}</td>
                    <td style="font-size:12px;">{{ $log->user->name ?? '—' }}</td>
                    <td style="font-family:monospace;font-size:11px;color:var(--color-outline);">{{ $log->ip ?? '—' }}</td>
                    <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $log->created_at->format('d M Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state"><span class="material-symbols-outlined">security</span><div class="empty-state-title">No Audit Logs</div></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())<div style="padding:16px;">{{ $logs->links() }}</div>@endif
</div>
@endsection
