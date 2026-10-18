@extends('layouts.dashboard')
@section('title', 'Renewals')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">RENEWALS</span></div>
        <h1 class="page-title">Membership Renewals</h1>
        <p class="page-title-sub">Expiring in 7 days: {{ $expiringSoon->total() }}</p>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;">

    {{-- Expiring list --}}
    <div>
        <div style="margin-bottom:16px;" class="section-header">
            <span class="section-title" style="font-size:15px;">Expiring Soon (7 days)</span>
        </div>
        <div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;margin-bottom:24px;">
            <div style="overflow-x:auto;">
                <table class="table-dark">
                    <thead><tr><th>Member</th><th>Plan</th><th>Expires</th><th style="text-align:right;">Action</th></tr></thead>
                    <tbody>
                        @forelse($expiringSoon as $ms)
                        <tr>
                            <td>
                                <div class="td-name">{{ $ms->member->user->name }}</div>
                                <div class="td-sub">{{ $ms->member->user->phone }}</div>
                            </td>
                            <td><span class="badge-paused">{{ $ms->plan->name }}</span></td>
                            <td>
                                <span style="font-size:13px;font-weight:600;color:var(--color-error);">{{ $ms->end_date->format('d M Y') }}</span>
                                <div style="font-size:10px;color:var(--color-outline);">{{ $ms->daysRemaining() }}d left</div>
                            </td>
                            <td style="text-align:right;">
                                <button onclick="openRenew({{ $ms->member->id }}, '{{ addslashes($ms->member->user->name) }}', {{ $ms->plan->price }})" class="btn-primary btn-sm">Renew Now</button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center;padding:24px;font-size:12px;color:var(--color-primary-container);">🎉 No memberships expiring in the next 7 days!</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($expiringSoon->hasPages())
            <div style="padding:16px;">{{ $expiringSoon->links() }}</div>
            @endif
        </div>

        <div style="margin-bottom:12px;" class="section-header">
            <span class="section-title" style="font-size:15px;">Recently Expired (Last 30 days)</span>
        </div>
        <div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
            <div style="overflow-x:auto;">
                <table class="table-dark">
                    <thead><tr><th>Member</th><th>Plan</th><th>Expired</th><th style="text-align:right;">Action</th></tr></thead>
                    <tbody>
                        @forelse($expiredRecentlyMs as $ms)
                        <tr>
                            <td>
                                <div class="td-name">{{ $ms->member->user->name }}</div>
                                <div class="td-sub">{{ $ms->member->user->phone }}</div>
                            </td>
                            <td><span class="badge-expired">{{ $ms->plan->name }}</span></td>
                            <td style="font-size:12px;color:var(--color-error);">{{ $ms->end_date->format('d M Y') }}</td>
                            <td style="text-align:right;">
                                <button onclick="openRenew({{ $ms->member->id }}, '{{ addslashes($ms->member->user->name) }}', {{ $ms->plan->price }})" class="btn-ghost btn-sm">Renew</button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center;padding:16px;font-size:12px;color:var(--color-outline);">No recently expired memberships.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Quick renew form --}}
    <div>
        <div class="card-dark" style="position:sticky;top:calc(var(--topbar-h) + 24px);">
            <div class="section-title" style="font-size:15px;margin-bottom:16px;">Quick Renewal</div>
            <form method="POST" action="{{ route('admin.renewals.store') }}">
                @csrf
                <div style="margin-bottom:12px;">
                    <label class="form-label-dark">Member *</label>
                    <select name="member_id" id="renew-member-id" class="form-control-dark form-select-dark" required>
                        <option value="">Select member...</option>
                        @foreach($members as $m)
                        <option value="{{ $m->id }}" {{ old('member_id')==$m->id ? 'selected':'' }}>{{ $m->user->name }} ({{ $m->member_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:12px;">
                    <label class="form-label-dark">Plan *</label>
                    <select name="plan_id" id="renew-plan-id" class="form-control-dark form-select-dark" required onchange="updateAmount(this)">
                        <option value="">Select plan...</option>
                        @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" data-price="{{ $plan->price }}">{{ $plan->name }} — PKR {{ number_format($plan->price) }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:12px;">
                    <label class="form-label-dark">Amount (PKR) *</label>
                    <input type="number" name="amount_paid" id="renew-amount" class="form-control-dark" value="{{ old('amount_paid') }}" required min="0" step="0.01">
                </div>
                <div style="margin-bottom:20px;">
                    <label class="form-label-dark">Payment Method *</label>
                    <select name="payment_method" class="form-control-dark form-select-dark" required>
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
                    <span class="material-symbols-outlined" style="font-size:18px;">autorenew</span>
                    Process Renewal
                </button>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
function openRenew(memberId, memberName, planPrice) {
    document.getElementById('renew-member-id').value = memberId;
    document.getElementById('renew-amount').value = planPrice;
    document.querySelector('[style*="sticky"]').scrollIntoView({ behavior: 'smooth' });
}
function updateAmount(sel) {
    const price = sel.options[sel.selectedIndex].getAttribute('data-price');
    if (price) document.getElementById('renew-amount').value = price;
}
</script>
<style>@media(max-width:900px){div[style*="grid-template-columns:2fr 1fr"]{grid-template-columns:1fr!important;}}</style>
@endpush
@endsection
