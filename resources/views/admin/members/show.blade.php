@extends('layouts.dashboard')
@section('title', $member->user->name)

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">MEMBERS</span></div>
        <h1 class="page-title">{{ $member->user->name }}</h1>
        <p class="page-title-sub">{{ $member->member_code }} · Joined {{ $member->joined_at->format('d M Y') }}</p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
        <button type="button"
            onclick="triggerCallLog(this)"
            data-member-id="{{ $member->id }}"
            data-name="{{ $member->user->name }}"
            data-code="{{ $member->member_code }}"
            data-phone="{{ $member->user->phone ?? '' }}"
            data-email="{{ $member->user->email ?? '' }}"
            data-post-url="{{ route('admin.members.call-log', $member) }}"
            data-logs="{{ json_encode($member->followUps->sortByDesc('followed_up_at')->values()->map(fn($f) => [
                'outcome' => $f->outcome,
                'notes' => $f->notes,
                'date' => $f->followed_up_at ? $f->followed_up_at->format('d M Y, h:i A') : '',
                'caller' => $f->caller->name ?? 'Staff'
            ])) }}"
            class="btn-primary"
            style="gap:6px;">
            <span class="material-symbols-outlined" style="font-size:18px;">call</span> Call Log
        </button>

        <a href="mailto:{{ $member->user->email }}?subject={{ urlencode('Kinetic Apex — Message for ' . $member->user->name) }}"
           class="btn-ghost"
           style="gap:6px;border:1px solid rgba(129,207,255,0.3);color:var(--color-secondary);"
           target="_blank">
            <span class="material-symbols-outlined" style="font-size:18px;">mail</span> Email
        </a>

        <a href="{{ route('admin.members.edit', $member) }}" class="btn-ghost">
            <span class="material-symbols-outlined" style="font-size:18px;">edit</span> Edit
        </a>

        <form method="POST" action="{{ route('admin.members.destroy', $member) }}" style="margin:0;display:inline;" onsubmit="return confirm('Permanently delete member &quot;{{ addslashes($member->user->name) }}&quot; ({{ $member->member_code }})?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger" style="gap:6px;">
                <span class="material-symbols-outlined" style="font-size:18px;">delete</span> Delete
            </button>
        </form>

        <a href="{{ route('admin.members.index') }}" class="btn-ghost">← Back</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:20px;">

    {{-- Left sidebar --}}
    <div style="display:flex;flex-direction:column;gap:16px;">
        {{-- Profile card --}}
        <div class="card-dark" style="text-align:center;">
            <div style="width:72px;height:72px;border-radius:50%;background:var(--color-surface-container);border:2px solid var(--color-primary-container);overflow:hidden;display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:28px;font-weight:700;color:var(--color-primary-container);margin:0 auto 12px;">
                @if($member->user->avatar_url)
                    <img src="{{ $member->user->avatar_url }}" alt="{{ $member->user->name }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    {{ $member->initials() }}
                @endif
            </div>
            <div style="font-family:var(--font-heading);font-size:18px;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);">{{ $member->user->name }}</div>
            <div style="font-size:12px;color:var(--color-outline);margin:4px 0 12px;">{{ $member->user->email }}</div>
            @if($member->user->phone)<div style="font-size:13px;color:var(--color-on-surface-variant);">{{ $member->user->phone }}</div>@endif
        </div>

        {{-- Membership card --}}
        @php $ms = $member->activeMembership; @endphp
        <div class="card-dark">
            <div class="section-title" style="font-size:13px;margin-bottom:12px;">Membership</div>
            @if($ms)
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                <span style="font-family:var(--font-heading);font-size:16px;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);">{{ $ms->plan->name }}</span>
                <span class="{{ $ms->isExpiringSoon() ? 'badge-paused' : 'badge-active' }}">{{ $ms->isExpiringSoon() ? 'Expiring Soon' : 'Active' }}</span>
            </div>
            <div style="background:var(--color-surface-highest);height:6px;border-radius:999px;overflow:hidden;margin-bottom:8px;">
                @php $pct = max(0,min(100, (1 - $ms->daysRemaining() / $ms->plan->duration_days) * 100)); @endphp
                <div style="background:var(--color-primary-container);height:100%;border-radius:999px;width:{{ $pct }}%;"></div>
            </div>
            <div style="font-size:11px;color:var(--color-outline);">{{ $ms->daysRemaining() }} days left · Expires {{ $ms->end_date->format('d M Y') }}</div>
            <div style="margin-top:12px;">
                <a href="{{ route('admin.renewals.index') }}" class="btn-primary btn-sm" style="width:100%;justify-content:center;">Renew Plan</a>
            </div>
            @else
            <div style="text-align:center;padding:16px;color:var(--color-outline);font-size:12px;">No active membership. <a href="{{ route('admin.renewals.index') }}" style="color:var(--color-primary-container);">Assign one →</a></div>
            @endif
        </div>

        {{-- Streak --}}
        @if($member->streak)
        <div class="card-dark">
            <div class="section-title" style="font-size:13px;margin-bottom:8px;">Attendance Streak</div>
            <div style="display:flex;gap:16px;">
                <div>
                    <div style="font-family:var(--font-heading);font-size:36px;font-weight:700;color:var(--color-primary-container);">{{ $member->streak->current_streak }}</div>
                    <div style="font-size:10px;color:var(--color-outline);text-transform:uppercase;letter-spacing:.06em;">Current</div>
                </div>
                <div>
                    <div style="font-family:var(--font-heading);font-size:36px;font-weight:700;color:var(--color-on-surface);">{{ $member->streak->longest_streak }}</div>
                    <div style="font-size:10px;color:var(--color-outline);text-transform:uppercase;letter-spacing:.06em;">Best</div>
                </div>
            </div>
        </div>
        @endif

        {{-- Trainer --}}
        @php $assign = $member->trainerAssignments->where('ended_at', null)->first(); @endphp
        @if($assign)
        <div class="card-dark">
            <div class="section-title" style="font-size:13px;margin-bottom:8px;">Assigned Trainer</div>
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;border-radius:50%;background:var(--color-surface-container);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:12px;font-weight:700;color:var(--color-secondary);">{{ $assign->trainer->initials() }}</div>
                <div>
                    <div style="font-size:13px;font-weight:600;color:var(--color-on-surface);">{{ $assign->trainer->user->name }}</div>
                    <div style="font-size:11px;color:var(--color-outline);">{{ $assign->trainer->specialization }}</div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Right: Tabs area --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Payment history --}}
        <div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
            <div style="padding:14px 16px;background:var(--color-surface-container);display:flex;align-items:center;justify-content:space-between;">
                <div class="section-title" style="font-size:14px;">Payment History</div>
                <a href="{{ route('admin.members.payments', $member) }}" class="btn-ghost btn-sm">All Payments</a>
            </div>
            <table class="table-dark">
                <thead><tr><th>Type</th><th>Amount</th><th>Method</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                    @forelse($member->payments->take(5) as $pay)
                    <tr>
                        <td><span class="badge-secondary">{{ ucfirst(str_replace('_',' ',$pay->type)) }}</span></td>
                        <td style="font-weight:600;font-size:13px;">PKR {{ number_format($pay->netAmount()) }}</td>
                        <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ ucfirst(str_replace('_',' ',$pay->payment_method)) }}</td>
                        <td>
                            @if($pay->status==='paid') <span class="badge-active">Paid</span>
                            @elseif($pay->status==='refunded') <span class="badge-paused">Refunded</span>
                            @else <span class="badge-expired">{{ ucfirst($pay->status) }}</span> @endif
                        </td>
                        <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $pay->paid_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;padding:20px;font-size:12px;color:var(--color-outline);">No payments recorded.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Attendance --}}
        <div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
            <div style="padding:14px 16px;background:var(--color-surface-container);">
                <div class="section-title" style="font-size:14px;">Recent Attendance</div>
            </div>
            <table class="table-dark">
                <thead><tr><th>Date</th><th>Day</th><th>Check-in</th><th>Check-out</th></tr></thead>
                <tbody>
                    @forelse($member->attendance->take(10) as $att)
                    <tr>
                        <td style="font-size:13px;font-weight:600;">{{ $att->date->format('d M Y') }}</td>
                        <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $att->date->format('l') }}</td>
                        <td><span class="badge-green">{{ \Carbon\Carbon::parse($att->checked_in_at)->format('H:i') }}</span></td>
                        <td style="font-size:12px;color:var(--color-outline);">{{ $att->checked_out_at ? \Carbon\Carbon::parse($att->checked_out_at)->format('H:i') : '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;padding:20px;font-size:12px;color:var(--color-outline);">No attendance records.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Follow-ups --}}
        @if($member->followUps->count())
        <div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
            <div style="padding:14px 16px;background:var(--color-surface-container);">
                <div class="section-title" style="font-size:14px;">Follow-up Log ({{ $member->followUps->count() }})</div>
            </div>
            @foreach($member->followUps as $fu)
            <div style="padding:12px 16px;border-bottom:1px solid var(--color-surface-highest);display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <span class="{{ $fu->outcome==='answered' ? 'badge-active' : ($fu->outcome==='no_answer' ? 'badge-paused' : 'badge-watch') }}">{{ ucfirst(str_replace('_',' ',$fu->outcome)) }}</span>
                    @if($fu->notes)<p style="font-size:12px;color:var(--color-on-surface-variant);margin:4px 0 0;">{{ $fu->notes }}</p>@endif
                </div>
                <div style="text-align:right;font-size:11px;color:var(--color-outline);">
                    {{ $fu->followed_up_at->format('d M Y H:i') }}<br>by {{ $fu->caller->name ?? '—' }}
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>

@include('components.call-log-modal')

@push('scripts')
<style>@media(max-width:900px){div[style*="grid-template-columns:1fr 2fr"]{grid-template-columns:1fr!important;}}</style>
@endpush
@endsection
