@extends('layouts.dashboard')
@section('title', 'Attendance Desk')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow">
            <span class="eyebrow-dot"></span>
            <span class="eyebrow-text">RECEPTION DESK</span>
            <span style="color:var(--color-outline);font-size:12px;margin:0 4px;">/</span>
            <span class="eyebrow-text" style="color:var(--color-outline);">MARK ATTENDANCE</span>
        </div>
        <h1 class="page-title">Attendance Desk</h1>
        <p class="page-title-sub">Search a member and mark their check-in for today.</p>
    </div>
    <div style="display:flex;align-items:center;gap:8px;padding:8px 14px;border-radius:6px;background:rgba(57,255,20,.1);border:1px solid rgba(57,255,20,.25);">
        <span class="pulse-green"></span>
        <span style="font-family:var(--font-heading);font-size:11px;letter-spacing:.06em;color:var(--color-primary-container);text-transform:uppercase;">{{ today()->format('d M Y') }} — {{ $todayAttendance->count() }} checked in</span>
    </div>
</div>

{{-- ── Member Search ─────────────────────────────────────────── --}}
<div class="card-dark" style="margin-bottom:24px;">
    <div style="margin-bottom:16px;">
        <div class="section-title" style="font-size:16px;margin-bottom:4px;">Search Member</div>
        <p style="font-size:12px;color:var(--color-outline);margin:0;">Search by name, phone or member code (e.g. APX-1001)</p>
    </div>
    <div style="position:relative;">
        <span class="material-symbols-outlined" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--color-outline);font-size:20px;">search</span>
        <input type="text" id="member-search" class="form-control-dark" style="padding-left:46px;font-size:15px;height:52px;" placeholder="Type name, phone, or APX code..." autocomplete="off">
    </div>

    {{-- Search Results --}}
    <div id="search-results" style="margin-top:12px;display:flex;flex-direction:column;gap:8px;"></div>
    <div id="search-empty" style="display:none;margin-top:12px;padding:16px;text-align:center;color:var(--color-outline);font-size:13px;">
        No member found. <a href="{{ route('admin.members.create') }}" style="color:var(--color-primary-container);text-decoration:none;">Add a new member →</a>
    </div>
</div>

{{-- ── Today's Log ───────────────────────────────────────────── --}}
<div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
    <div style="padding:16px;background:var(--color-surface-container);display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:10px;">
            <span class="material-symbols-outlined" style="color:var(--color-primary-container);font-size:20px;">format_list_bulleted</span>
            <div>
                <div class="section-title" style="font-size:15px;">Today's Check-in Log</div>
                <p style="font-size:11px;color:var(--color-outline);margin:0;">{{ today()->format('l, d F Y') }}</p>
            </div>
        </div>
        <span class="badge-green">{{ $todayAttendance->count() }} Members</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="table-dark">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Member</th>
                    <th>Check-in Time</th>
                    <th>Plan</th>
                    <th>Marked by</th>
                    @can('admin') <th style="text-align:right;">Action</th> @endcan
                </tr>
            </thead>
            <tbody>
                @forelse($todayAttendance as $att)
                <tr>
                    <td style="font-family:var(--font-heading);font-size:11px;color:var(--color-outline);">{{ $loop->iteration }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;border-radius:50%;background:var(--color-surface-highest);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:12px;font-weight:700;color:var(--color-on-surface);flex-shrink:0;">{{ $att->member->initials() }}</div>
                            <div>
                                <div class="td-name">{{ $att->member->user->name }}</div>
                                <div class="td-sub">{{ $att->member->member_code }} · {{ $att->member->user->phone }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge-green">{{ \Carbon\Carbon::parse($att->checked_in_at)->format('H:i') }}</span>
                        @if($att->is_corrected)
                        <span class="badge-watch" style="margin-left:4px;">Corrected</span>
                        @endif
                    </td>
                    <td>
                        @php $ms = $att->member->activeMembership; @endphp
                        @if($ms)
                            <span class="badge-active">{{ $ms->plan->name }}</span>
                        @else
                            <span class="badge-expired">No Plan</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $att->marker->name ?? 'System' }}</td>
                    @if(auth()->user()->isAdmin())
                    <td style="text-align:right;">
                        <form method="POST" action="{{ route('receptionist.attendance.destroy', $att) }}" onsubmit="return confirm('Remove this check-in record?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger btn-sm btn-icon" title="Remove">
                                <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <span class="material-symbols-outlined">badge</span>
                            <div class="empty-state-title">No Check-ins Yet</div>
                            <div class="empty-state-body">Search for a member above to mark their attendance for today.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── Hidden mark form (submitted by JS) ──────────────────── --}}
<form id="mark-form" method="POST" action="{{ route('receptionist.attendance.store') }}" style="display:none;">
    @csrf
    <input type="hidden" name="member_id" id="mark-member-id">
</form>

@push('scripts')
<script>
const searchInput   = document.getElementById('member-search');
const resultsBox    = document.getElementById('search-results');
const emptyBox      = document.getElementById('search-empty');
const markForm      = document.getElementById('mark-form');
const markMemberId  = document.getElementById('mark-member-id');
let debounceTimer;

searchInput.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    const q = searchInput.value.trim();
    if (q.length < 2) { resultsBox.innerHTML = ''; emptyBox.style.display = 'none'; return; }

    debounceTimer = setTimeout(async () => {
        const res = await fetch(`{{ route('receptionist.attendance.search') }}?q=${encodeURIComponent(q)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        resultsBox.innerHTML = '';
        if (data.length === 0) { emptyBox.style.display = 'block'; return; }
        emptyBox.style.display = 'none';

        data.forEach(m => {
            const div = document.createElement('div');
            const statusBadge = m.status === 'active'
                ? `<span class="badge-active">${m.plan}</span>`
                : `<span class="badge-expired">${m.plan}</span>`;
            const alreadyIn = m.already_in;

            div.className = 'member-search-result';
            div.innerHTML = `
                <div style="display:flex;align-items:center;gap:12px;min-width:0;">
                    <div style="width:44px;height:44px;border-radius:50%;background:var(--color-surface-highest);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:14px;font-weight:700;flex-shrink:0;">${m.initials}</div>
                    <div style="min-width:0;">
                        <div style="font-weight:600;font-size:14px;color:var(--color-on-surface);">${m.name}</div>
                        <div style="font-size:11px;color:var(--color-outline);">${m.member_code} · ${m.phone || '—'}</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
                    ${statusBadge}
                    <span style="font-size:11px;color:var(--color-outline);">Exp: ${m.expires}</span>
                    ${alreadyIn
                        ? `<span style="font-family:var(--font-heading);font-size:11px;letter-spacing:.06em;padding:6px 14px;border-radius:4px;background:var(--color-surface-highest);color:var(--color-outline);text-transform:uppercase;cursor:not-allowed;">Already In ✓</span>`
                        : `<button onclick="markAttendance(${m.id})" class="btn-primary btn-sm">
                                <span class="material-symbols-outlined" style="font-size:16px;">check</span> Check In
                           </button>`
                    }
                </div>
            `;
            resultsBox.appendChild(div);
        });
    }, 280);
});

function markAttendance(memberId) {
    if (!confirm('Mark this member as checked in for today?')) return;
    markMemberId.value = memberId;
    markForm.submit();
}
</script>
@endpush
@endsection
