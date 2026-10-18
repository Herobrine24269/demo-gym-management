@extends('layouts.dashboard')
@section('title', 'Member Payments')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">MEMBERS</span></div>
        <h1 class="page-title">{{ $member->user->name }} — Payments</h1>
        <p class="page-title-sub">{{ $member->member_code }}</p>
    </div>
    <a href="{{ route('admin.members.show', $member) }}" class="btn-ghost">← Back to Member</a>
</div>
<div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="table-dark">
            <thead><tr><th>Type</th><th>Amount</th><th>Method</th><th>Status</th><th>Received by</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($payments as $pay)
                <tr>
                    <td><span class="badge-secondary">{{ ucfirst(str_replace('_',' ',$pay->type)) }}</span></td>
                    <td style="font-weight:600;font-size:13px;">PKR {{ number_format($pay->netAmount()) }}</td>
                    <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ ucfirst(str_replace('_',' ',$pay->payment_method)) }}</td>
                    <td>
                        @if($pay->status==='paid') <span class="badge-active">Paid</span>
                        @elseif($pay->status==='refunded') <span class="badge-paused">Refunded</span>
                        @else <span class="badge-at-risk">{{ ucfirst($pay->status) }}</span> @endif
                    </td>
                    <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $pay->receiver->name ?? '—' }}</td>
                    <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $pay->paid_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><span class="material-symbols-outlined">receipt_long</span><div class="empty-state-title">No Payments</div></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())<div style="padding:16px;">{{ $payments->links() }}</div>@endif
</div>
@endsection
