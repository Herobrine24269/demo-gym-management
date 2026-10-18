@extends('layouts.dashboard')
@section('title', 'Payments')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">FINANCIALS</span></div>
        <h1 class="page-title">Payments</h1>
    </div>
</div>

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;margin-bottom:24px;">
    @foreach([['This Month','PKR '.number_format($totalThisMonth),'payments','var(--color-primary-container)'],['Today','PKR '.number_format($totalToday),'receipt_long','var(--color-secondary)']] as [$l,$v,$i,$c])
    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;"><div class="stat-label">{{ $l }}</div><span class="material-symbols-outlined" style="font-size:20px;color:{{ $c }};">{{ $i }}</span></div>
        <div style="font-family:var(--font-heading);font-size:26px;font-weight:700;color:{{ $c }};margin-top:6px;">{{ $v }}</div>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<form method="GET" style="display:flex;gap:12px;margin-bottom:24px;flex-wrap:wrap;">
    <input type="text" name="q" value="{{ request('q') }}" class="form-control-dark" placeholder="Search member name..." style="flex:1;min-width:200px;">
    <select name="type" class="form-control-dark form-select-dark" style="width:auto;min-width:150px;">
        <option value="">All Types</option>
        @foreach(['membership','renewal','pt_session','addon','other'] as $t)
        <option value="{{ $t }}" {{ request('type')==$t ? 'selected':'' }}>{{ ucfirst(str_replace('_',' ',$t)) }}</option>
        @endforeach
    </select>
    <select name="status" class="form-control-dark form-select-dark" style="width:auto;min-width:130px;">
        <option value="">All Status</option>
        @foreach(['paid','pending','refunded'] as $s)
        <option value="{{ $s }}" {{ request('status')==$s ? 'selected':'' }}>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-primary">Filter</button>
    @if(request()->anyFilled(['q','type','status']))<a href="{{ route('admin.payments.index') }}" class="btn-ghost">Clear</a>@endif
</form>

<div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="table-dark">
            <thead><tr><th>Member</th><th>Type</th><th>Amount</th><th>Method</th><th>Status</th><th>Received by</th><th>Date</th><th style="text-align:right;">Action</th></tr></thead>
            <tbody>
                @forelse($payments as $pay)
                <tr>
                    <td>
                        <div class="td-name">{{ $pay->member->user->name }}</div>
                        <div class="td-sub">{{ $pay->member->member_code }}</div>
                    </td>
                    <td><span class="badge-secondary">{{ ucfirst(str_replace('_',' ',$pay->type)) }}</span></td>
                    <td>
                        <div style="font-weight:600;font-size:13px;">PKR {{ number_format($pay->netAmount()) }}</div>
                        @if($pay->discount > 0)<div style="font-size:10px;color:var(--color-outline);">-PKR {{ number_format($pay->discount) }} disc</div>@endif
                    </td>
                    <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ ucfirst(str_replace('_',' ',$pay->payment_method)) }}</td>
                    <td>
                        @if($pay->status==='paid') <span class="badge-active">Paid</span>
                        @elseif($pay->status==='refunded') <span class="badge-paused">Refunded</span>
                        @elseif($pay->status==='pending') <span class="badge-at-risk">Pending</span>
                        @else <span class="badge-expired">{{ ucfirst($pay->status) }}</span> @endif
                    </td>
                    <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $pay->receiver->name ?? '—' }}</td>
                    <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $pay->paid_at->format('d M Y') }}</td>
                    <td style="text-align:right;">
                        @if($pay->status==='paid')
                        <form method="POST" action="{{ route('admin.payments.refund', $pay) }}" onsubmit="return confirm('Refund this payment?');">
                            @csrf
                            <button type="submit" class="btn-danger btn-sm">Refund</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state"><span class="material-symbols-outlined">receipt_long</span><div class="empty-state-title">No Payments Found</div></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
    <div style="padding:16px;">{{ $payments->links() }}</div>
    @endif
</div>
@endsection
