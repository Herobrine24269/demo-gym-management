{{-- Trainer members index --}}
@extends('layouts.dashboard')
@section('title', 'My Members')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">TRAINER PORTAL</span></div>
        <h1 class="page-title">My Members</h1>
        <p class="page-title-sub">{{ $assignments->total() }} assigned athletes under your training</p>
    </div>
</div>

<div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
    <div style="padding:16px 20px;background:var(--color-surface-container);display:flex;align-items:center;justify-content:space-between;">
        <div class="section-title" style="font-size:15px;">Active Assignments ({{ $assignments->total() }})</div>
    </div>
    <div style="overflow-x:auto;">
        <table class="table-dark">
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Plan</th>
                    <th>Streak</th>
                    <th>Contact Info</th>
                    <th style="text-align:right;">Contact & Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $assign)
                @php $m = $assign->member; @endphp
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:38px;height:38px;border-radius:50%;background:var(--color-surface-highest);overflow:hidden;display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:12px;font-weight:700;color:var(--color-primary-container);flex-shrink:0;">
                                @if($m->user->avatar_url)
                                    <img src="{{ $m->user->avatar_url }}" alt="{{ $m->user->name }}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    {{ $m->initials() }}
                                @endif
                            </div>
                            <div>
                                <div class="td-name">{{ $m->user->name }}</div>
                                <div class="td-sub" style="font-family:monospace;color:var(--color-primary-container);">{{ $m->member_code }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($m->activeMembership)
                            <span class="badge-active">{{ $m->activeMembership->plan->name }}</span>
                        @else
                            <span class="badge-expired">No Plan</span>
                        @endif
                    </td>
                    <td>
                        @if($m->streak)
                            <span style="font-family:var(--font-heading);font-size:18px;font-weight:700;color:var(--color-primary-container);">{{ $m->streak->current_streak }}</span>
                            <span style="font-size:11px;color:var(--color-outline);">d</span>
                        @else
                            <span style="color:var(--color-outline);">—</span>
                        @endif
                    </td>
                    <td>
                        @if($m->user->phone)
                            <div style="font-family:monospace;font-size:12px;color:var(--color-on-surface);font-weight:600;">{{ $m->user->phone }}</div>
                        @else
                            <div style="font-size:11px;color:var(--color-outline);">No phone on file</div>
                        @endif
                        <div style="font-size:11px;color:var(--color-outline);">{{ $m->user->email }}</div>
                    </td>
                    <td style="text-align:right;">
                        <div style="display:flex;gap:6px;justify-content:flex-end;align-items:center;flex-wrap:nowrap;">

                            {{-- ── CALL LOG button ── --}}
                            <button type="button"
                                onclick="triggerCallLog(this)"
                                data-member-id="{{ $m->id }}"
                                data-name="{{ $m->user->name }}"
                                data-code="{{ $m->member_code }}"
                                data-phone="{{ $m->user->phone ?? '' }}"
                                data-email="{{ $m->user->email ?? '' }}"
                                data-post-url="{{ route('trainer.members.call-log', $m) }}"
                                data-logs="{{ json_encode($m->followUps->sortByDesc('followed_up_at')->values()->map(fn($f) => [
                                    'outcome' => $f->outcome,
                                    'notes' => $f->notes,
                                    'date' => $f->followed_up_at ? $f->followed_up_at->format('d M Y, h:i A') : '',
                                    'caller' => $f->caller->name ?? 'Staff'
                                ])) }}"
                                class="btn-ghost btn-sm"
                                style="display:inline-flex;align-items:center;gap:5px;padding:6px 10px;font-size:11px;border:1px solid rgba(57,255,20,0.25);"
                                title="Call {{ $m->user->name }} & view call logs">
                                <span class="material-symbols-outlined" style="font-size:15px;color:var(--color-primary-container);">call</span>
                                <span>Call Log</span>
                                @if($m->followUps->count())
                                    <span style="background:rgba(57,255,20,0.18);color:var(--color-primary-container);padding:1px 5px;border-radius:10px;font-size:10px;font-weight:700;">{{ $m->followUps->count() }}</span>
                                @endif
                            </button>

                            {{-- ── EMAIL button ── --}}
                            <a href="mailto:{{ $m->user->email }}?subject={{ urlencode('Kinetic Apex — Trainer Message for ' . $m->user->name) }}"
                               class="btn-ghost btn-sm"
                               style="display:inline-flex;align-items:center;gap:5px;padding:6px 10px;font-size:11px;border:1px solid rgba(129,207,255,0.25);"
                               title="Send Email to {{ $m->user->email }}"
                               target="_blank">
                                <span class="material-symbols-outlined" style="font-size:15px;color:var(--color-secondary);">mail</span>
                                <span>Email</span>
                            </a>

                            <div style="width:1px;height:18px;background:var(--color-surface-highest);margin:0 2px;"></div>

                            <a href="{{ route('trainer.members.show', $m) }}" class="btn-ghost btn-sm btn-icon" title="View Profile">
                                <span class="material-symbols-outlined" style="font-size:16px;">visibility</span>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <span class="material-symbols-outlined">group</span>
                            <div class="empty-state-title">No Members Assigned</div>
                            <div class="empty-state-body">You have no active member assignments at this time.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($assignments->hasPages())
    <div style="padding:16px;">{{ $assignments->links() }}</div>
    @endif
</div>

{{-- Call Log & Contact Modal --}}
@include('components.call-log-modal')

@endsection
