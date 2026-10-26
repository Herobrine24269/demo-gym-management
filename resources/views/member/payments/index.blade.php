@extends('layouts.dashboard')
@section('title', 'My Payments & Billing')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow">
            <span class="eyebrow-dot"></span>
            <span class="eyebrow-text">FINANCIAL & ACCESS LEDGER</span>
        </div>
        <h1 class="page-title">My Payments & Billing</h1>
        <p class="page-title-sub">Track your manual payment requests, verification statuses, and official membership receipts.</p>
    </div>
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="{{ route('member.renewals.index') }}" class="btn-primary" style="padding:10px 20px;font-size:13px;">
            <span class="material-symbols-outlined" style="font-size:18px;">autorenew</span>
            Renew Membership
        </a>
    </div>
</div>

{{-- ── SECTION 1: MANUAL PAYMENT REQUESTS ───────────────────── --}}
<div class="card-dark" style="margin-bottom:28px;border:1px solid var(--color-surface-highest);">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;border-bottom:1px solid var(--color-surface-highest);padding-bottom:14px;flex-wrap:wrap;gap:10px;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:34px;height:34px;border-radius:8px;background:rgba(57,255,20,.12);border:1px solid rgba(57,255,20,.3);color:var(--color-primary-container);display:flex;align-items:center;justify-content:center;">
                <span class="material-symbols-outlined" style="font-size:20px;">hourglass_top</span>
            </div>
            <div>
                <div class="section-title" style="font-size:16px;">Manual Payment Submissions</div>
                <div style="font-size:11px;color:var(--color-outline);">Direct receipts submitted for staff manual check and renewal approval.</div>
            </div>
        </div>

        <div style="font-size:12px;color:var(--color-outline);">
            {{ $submissions->count() }} request{{ $submissions->count() === 1 ? '' : 's' }}
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table class="table-dark" style="font-size:12px;">
            <thead>
                <tr>
                    <th>Submitted On</th>
                    <th>Plan</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Transaction ID</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($submissions as $sub)
                @php
                    $statusBadge = match($sub->status) {
                        'approved' => ['badge-active', 'APPROVED', '#39ff14'],
                        'rejected' => ['badge-danger', 'REJECTED', 'var(--color-error)'],
                        default    => ['badge-paused', 'PENDING REVIEW', '#facc15'],
                    };
                @endphp
                <tr>
                    <td style="color:var(--color-outline);">
                        <div>{{ $sub->created_at->format('d M Y') }}</div>
                        <div style="font-size:10px;color:var(--color-outline);">{{ $sub->created_at->format('h:i A') }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600;color:var(--color-on-surface);">{{ $sub->plan->name }}</div>
                        <div style="font-size:10px;color:var(--color-outline);">Paid at: {{ $sub->paid_at->format('d M Y') }}</div>
                    </td>
                    <td>
                        <span style="display:inline-flex;align-items:center;gap:4px;color:var(--color-on-surface);">
                            <span class="material-symbols-outlined" style="font-size:15px;color:var(--color-primary-container);">{{ $sub->paymentMethod->icon ?? 'payments' }}</span>
                            {{ $sub->paymentMethod->display_name ?? 'Manual' }}
                        </span>
                    </td>
                    <td style="font-family:var(--font-heading);font-weight:700;font-size:14px;color:var(--color-on-surface);">
                        PKR {{ number_format($sub->amount) }}
                    </td>
                    <td>
                        <code style="font-size:12px;color:var(--color-primary-container);background:rgba(0,0,0,.3);padding:2px 8px;border-radius:4px;">
                            {{ $sub->transaction_reference }}
                        </code>
                    </td>
                    <td>
                        <span class="{{ $statusBadge[0] }}" style="font-size:10px;padding:3px 8px;">
                            {{ $statusBadge[1] }}
                        </span>
                        @if($sub->isApproved() && $sub->receipt_number)
                            <div style="font-size:11px;color:var(--color-primary-container);margin-top:4px;font-family:monospace;font-weight:700;">
                                {{ $sub->receipt_number }}
                            </div>
                        @elseif($sub->isRejected() && $sub->rejection_reason)
                            <div style="font-size:11px;color:var(--color-error);margin-top:4px;max-width:240px;line-height:1.3;">
                                <strong>Reason:</strong> {{ $sub->rejection_reason }}
                            </div>
                        @elseif($sub->isPending())
                            <div style="font-size:10px;color:#facc15;margin-top:2px;">
                                Awaiting staff verification
                            </div>
                        @endif
                    </td>
                    <td style="text-align:right;">
                        <div style="display:inline-flex;align-items:center;gap:6px;justify-content:flex-end;">
                            <a href="{{ route('member.payment-submissions.screenshot', $sub) }}"
                               target="_blank"
                               class="btn-ghost btn-sm"
                               style="padding:4px 10px;font-size:11px;"
                               title="View uploaded proof in new tab">
                                <span class="material-symbols-outlined" style="font-size:15px;">visibility</span>
                                View Slip
                            </a>
                            @if($sub->isApproved() && $sub->receipt_number)
                                <a href="{{ route('member.payments.receipt', $sub) }}"
                                   target="_blank"
                                   class="btn-secondary btn-sm"
                                   style="padding:4px 8px;font-size:11px;text-decoration:none;"
                                   title="View Printable Receipt">
                                    <span class="material-symbols-outlined" style="font-size:14px;">receipt</span>
                                    Receipt
                                </a>
                                <a href="{{ route('member.payments.receipt-pdf', $sub) }}"
                                   class="btn-primary btn-sm"
                                   style="padding:4px 8px;font-size:11px;text-decoration:none;"
                                   title="Download Official PDF Receipt">
                                    <span class="material-symbols-outlined" style="font-size:14px;">download</span>
                                    PDF
                                </a>
                            @endif
                            @if($sub->isRejected())
                                <a href="{{ route('member.renewals.index', ['plan_id' => $sub->membership_plan_id]) }}"
                                   class="btn-primary btn-sm"
                                   style="padding:4px 10px;font-size:11px;"
                                   title="Submit a fresh payment proof">
                                    Submit Again
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:40px 20px;color:var(--color-outline);">
                        <span class="material-symbols-outlined" style="font-size:36px;color:var(--color-outline);margin-bottom:8px;display:block;">receipt_long</span>
                        You have not submitted any manual payment requests yet.
                        <div style="margin-top:10px;">
                            <a href="{{ route('member.renewals.index') }}" class="btn-primary btn-sm">Renew Membership Online</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── SECTION 2: OFFICIAL PAYMENT RECEIPTS ──────────────────── --}}
<div class="card-dark" style="border:1px solid var(--color-surface-highest);">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;border-bottom:1px solid var(--color-surface-highest);padding-bottom:14px;flex-wrap:wrap;gap:10px;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:34px;height:34px;border-radius:8px;background:rgba(129,207,255,.12);border:1px solid rgba(129,207,255,.3);color:var(--color-secondary);display:flex;align-items:center;justify-content:center;">
                <span class="material-symbols-outlined" style="font-size:20px;">verified</span>
            </div>
            <div>
                <div class="section-title" style="font-size:16px;">Official Membership Receipts</div>
                <div style="font-size:11px;color:var(--color-outline);">Approved and settled payments verified on the facility ledger.</div>
            </div>
        </div>

        <div style="font-size:12px;color:var(--color-outline);">
            Total Paid: <strong style="color:var(--color-primary-container);font-family:var(--font-heading);font-size:15px;">PKR {{ number_format($totalPaid) }}</strong>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table class="table-dark" style="font-size:12px;">
            <thead>
                <tr>
                    <th>Date Paid</th>
                    <th>Invoice #</th>
                    <th>Description</th>
                    <th>Channel</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th style="text-align:right;">Receipt</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $pay)
                <tr>
                    <td style="color:var(--color-outline);">
                        {{ $pay->paid_at ? $pay->paid_at->format('d M Y') : $pay->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <span style="font-family:monospace;font-weight:700;color:var(--color-primary-container);">
                            INV-{{ str_pad($pay->id, 5, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>
                    <td style="font-weight:600;color:var(--color-on-surface);">
                        {{ ucfirst($pay->type ?? 'Membership Renewal') }}
                    </td>
                    <td>
                        <span style="font-size:11px;color:var(--color-on-surface-variant);">
                            {{ ucwords(str_replace('_', ' ', $pay->payment_method)) }}
                        </span>
                    </td>
                    <td style="font-family:var(--font-heading);font-weight:700;font-size:14px;color:var(--color-on-surface);">
                        PKR {{ number_format($pay->amount) }}
                    </td>
                    <td>
                        <span class="badge-active" style="font-size:9px;padding:2px 6px;">
                            {{ strtoupper($pay->status ?? 'PAID') }}
                        </span>
                    </td>
                    <td style="text-align:right;">
                        <button type="button"
                                class="btn-ghost btn-sm btn-icon"
                                title="View Receipt Voucher"
                                onclick="viewPaymentReceipt('INV-{{ str_pad($pay->id, 5, '0', STR_PAD_LEFT) }}', '{{ $pay->paid_at ? $pay->paid_at->format('d M Y, h:i A') : $pay->created_at->format('d M Y') }}', '{{ ucfirst($pay->type ?? 'Membership') }}', 'PKR {{ number_format($pay->amount) }}', '{{ ucfirst(str_replace('_', ' ', $pay->payment_method)) }}', '{{ $member->user->name }}', '{{ $member->member_code }}')">
                            <span class="material-symbols-outlined" style="font-size:16px;">receipt</span>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:32px;color:var(--color-outline);">
                        No settled payment records found on your account.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── RECEIPT MODAL ──────────────────────────────────────────── --}}
<div id="receipt-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.8);z-index:9999;backdrop-filter:blur(6px);align-items:center;justify-content:center;padding:16px;">
    <div style="background:var(--color-surface-container);border:1px solid var(--color-surface-highest);border-radius:12px;width:100%;max-width:440px;box-shadow:0 24px 64px rgba(0,0,0,.7);overflow:hidden;position:relative;" id="receipt-printable">
        <div style="background:var(--color-surface-lowest);padding:24px;border-bottom:1px dashed var(--color-surface-highest);text-align:center;position:relative;">
            <button type="button" onclick="closeReceiptModal()" style="position:absolute;top:16px;right:16px;background:none;border:none;color:var(--color-outline);font-size:22px;cursor:pointer;">×</button>
            <div style="width:38px;height:38px;border-radius:6px;background:var(--color-primary-container);color:var(--color-on-primary);display:inline-flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:20px;font-weight:700;margin-bottom:8px;">K</div>
            <div style="font-family:var(--font-heading);font-size:16px;font-weight:700;letter-spacing:.06em;color:var(--color-on-surface);text-transform:uppercase;">KINETIC APEX</div>
            <div style="font-size:11px;color:var(--color-outline);text-transform:uppercase;letter-spacing:.08em;">Official Payment Voucher</div>
        </div>

        <div style="padding:24px;display:flex;flex-direction:column;gap:14px;">
            <div style="display:flex;justify-content:space-between;font-size:12px;">
                <span style="color:var(--color-outline);">Invoice #:</span>
                <span style="font-family:monospace;font-weight:700;color:var(--color-primary-container);" id="modal-invoice"></span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:12px;">
                <span style="color:var(--color-outline);">Issued To:</span>
                <span style="font-weight:600;color:var(--color-on-surface);" id="modal-member"></span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:12px;">
                <span style="color:var(--color-outline);">Member Code:</span>
                <span style="font-family:monospace;color:var(--color-on-surface);" id="modal-code"></span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:12px;">
                <span style="color:var(--color-outline);">Date &amp; Time:</span>
                <span style="color:var(--color-on-surface-variant);" id="modal-date"></span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:12px;">
                <span style="color:var(--color-outline);">Payment Method:</span>
                <span style="color:var(--color-on-surface-variant);" id="modal-method"></span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:12px;">
                <span style="color:var(--color-outline);">Description:</span>
                <span style="color:var(--color-on-surface);" id="modal-desc"></span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:16px;font-weight:700;padding-top:12px;border-top:1px dashed var(--color-surface-highest);">
                <span style="color:var(--color-on-surface);">Total Paid:</span>
                <span style="color:var(--color-primary-container);font-family:var(--font-heading);font-size:20px;" id="modal-amount"></span>
            </div>
        </div>

        <div style="padding:16px 24px;background:var(--color-surface-lowest);border-top:1px solid var(--color-surface-highest);display:flex;gap:10px;">
            <button type="button" onclick="window.print()" class="btn-ghost" style="flex:1;justify-content:center;font-size:12px;">
                <span class="material-symbols-outlined" style="font-size:16px;">print</span> Print
            </button>
            <button type="button" onclick="closeReceiptModal()" class="btn-primary" style="flex:1;justify-content:center;font-size:12px;">
                Close
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function viewPaymentReceipt(inv, date, desc, amt, method, member, code) {
    document.getElementById('modal-invoice').innerText = inv;
    document.getElementById('modal-date').innerText    = date;
    document.getElementById('modal-desc').innerText    = desc;
    document.getElementById('modal-amount').innerText  = amt;
    document.getElementById('modal-method').innerText  = method;
    document.getElementById('modal-member').innerText  = member;
    document.getElementById('modal-code').innerText    = code;
    const modal = document.getElementById('receipt-modal');
    modal.style.display = 'flex';
}
function closeReceiptModal() {
    document.getElementById('receipt-modal').style.display = 'none';
}
</script>
@endpush
@endsection
