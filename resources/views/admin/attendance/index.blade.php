@extends('layouts.dashboard')
@section('title', 'Attendance Log')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">ADMIN</span></div>
        <h1 class="page-title">Attendance Log</h1>
    </div>
</div>

<form method="GET" style="display:flex;gap:12px;margin-bottom:24px;flex-wrap:wrap;align-items:center;">
    <div>
        <label class="form-label-dark" style="display:inline;margin-right:8px;">Date</label>
        <input type="date" name="date" class="form-control-dark" value="{{ $date }}" style="width:auto;">
    </div>
    <input type="text" name="q" value="{{ request('q') }}" class="form-control-dark" placeholder="Search member..." style="flex:1;min-width:180px;">
    <button type="submit" class="btn-primary">View</button>
    @if(request('q'))<a href="{{ route('admin.attendance.index') }}" class="btn-ghost">Clear</a>@endif
</form>

<div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
    <div style="padding:16px;background:var(--color-surface-container);display:flex;align-items:center;justify-content:space-between;">
        <div class="section-title" style="font-size:15px;">{{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}</div>
        <span class="badge-green">{{ $records->total() }} Records</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="table-dark">
            <thead><tr><th>#</th><th>Member</th><th>Check-in</th><th>Check-out</th><th>Marked by</th><th style="text-align:right;">Actions</th></tr></thead>
            <tbody>
                @forelse($records as $att)
                <tr>
                    <td style="font-family:var(--font-heading);font-size:11px;color:var(--color-outline);">{{ $loop->iteration }}</td>
                    <td>
                        <div class="td-name">{{ $att->member->user->name }}</div>
                        <div class="td-sub">{{ $att->member->member_code }}</div>
                    </td>
                    <td>
                        <span class="badge-green">{{ \Carbon\Carbon::parse($att->checked_in_at)->format('H:i') }}</span>
                        @if($att->is_corrected)<span class="badge-watch" style="margin-left:4px;">Corrected</span>@endif
                    </td>
                    <td style="font-size:12px;color:var(--color-outline);">{{ $att->checked_out_at ? \Carbon\Carbon::parse($att->checked_out_at)->format('H:i') : '—' }}</td>
                    <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $att->marker->name ?? 'System' }}</td>
                    <td style="text-align:right;">
                        <div style="display:flex;gap:4px;justify-content:flex-end;">
                            <button class="btn-ghost btn-sm btn-icon" onclick="openCorrect({{ $att->id }}, '{{ \Carbon\Carbon::parse($att->checked_in_at)->format('H:i') }}')" title="Correct time">
                                <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                            </button>
                            <form method="POST" action="{{ route('admin.attendance.destroy', $att) }}" onsubmit="return confirm('Delete this record?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger btn-sm btn-icon"><span class="material-symbols-outlined" style="font-size:16px;">delete</span></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><span class="material-symbols-outlined">calendar_month</span><div class="empty-state-title">No Records for This Date</div></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($records->hasPages())<div style="padding:16px;">{{ $records->links() }}</div>@endif
</div>

{{-- Correct time modal --}}
<div id="correct-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.6);backdrop-filter:blur(4px);place-items:center;" onclick="if(event.target===this)closeCorrect()">
    <div style="background:var(--color-surface-low);border:1px solid var(--color-surface-highest);border-radius:12px;padding:24px;width:100%;max-width:400px;margin:auto;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
            <div class="section-title" style="font-size:15px;">Correct Attendance Time</div>
            <button onclick="closeCorrect()" style="background:none;border:none;color:var(--color-outline);cursor:pointer;font-size:20px;">×</button>
        </div>
        <form method="POST" id="correct-form">
            @csrf @method('PUT')
            <div style="margin-bottom:12px;">
                <label class="form-label-dark">Check-in Time *</label>
                <input type="time" name="checked_in_at" id="correct-time" class="form-control-dark" required>
            </div>
            <div style="margin-bottom:12px;">
                <label class="form-label-dark">Check-out Time</label>
                <input type="time" name="checked_out_at" class="form-control-dark">
            </div>
            <div style="margin-bottom:16px;">
                <label class="form-label-dark">Reason for correction</label>
                <input type="text" name="note" class="form-control-dark" placeholder="e.g. System error, manual log">
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="closeCorrect()" class="btn-ghost">Cancel</button>
                <button type="submit" class="btn-primary"><span class="material-symbols-outlined" style="font-size:16px;">save</span> Save</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openCorrect(id, time) {
    document.getElementById('correct-form').action = `/admin/attendance/${id}/correct`;
    document.getElementById('correct-time').value = time;
    document.getElementById('correct-modal').style.display = 'grid';
}
function closeCorrect() { document.getElementById('correct-modal').style.display = 'none'; }
</script>
@endpush
@endsection
