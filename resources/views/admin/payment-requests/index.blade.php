@extends('layouts.dashboard')
@section('title', 'Payment Requests')

@section('content')
{{-- Page Header --}}
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow">
            <span class="eyebrow-dot"></span>
            <span class="eyebrow-text">PAYMENT OPERATIONS</span>
            <span style="color:var(--color-outline);font-size:12px;margin:0 4px;">/</span>
            <span class="eyebrow-text" style="color:var(--color-outline);">MANUAL VERIFICATION</span>
        </div>
        <h1 class="page-title">Payment Requests</h1>
        <p class="page-title-sub">Review manual payments, verify transaction screenshots, and approve renewals.</p>
    </div>

    @if($counts['pending'] > 0)
        <div style="display:flex;align-items:center;gap:10px;background:rgba(245,158,11,0.12);border:1px solid rgba(245,158,11,0.3);padding:8px 16px;border-radius:8px;">
            <span class="material-symbols-outlined" style="color:#f59e0b;font-size:20px;animation:pulseGreen 2s infinite;">hourglass_empty</span>
            <div>
                <div style="font-size:11px;font-family:var(--font-heading);letter-spacing:.08em;color:#f59e0b;text-transform:uppercase;font-weight:700;">AWAITING REVIEW</div>
                <div style="font-size:13px;font-weight:700;color:#fff;">{{ $counts['pending'] }} {{ Str::plural('Payment', $counts['pending']) }} pending approval</div>
            </div>
        </div>
    @endif
</div>

{{-- Status Filter Tabs --}}
<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;border-bottom:1px solid var(--color-surface-highest);padding-bottom:12px;">
    @php
        $currentStatus = request('status', 'all');
        $tabs = [
            'all'      => ['label' => 'All Requests', 'count' => $counts['all']],
            'pending'  => ['label' => 'Pending Review', 'count' => $counts['pending']],
            'approved' => ['label' => 'Approved', 'count' => $counts['approved']],
            'rejected' => ['label' => 'Rejected', 'count' => $counts['rejected']],
        ];
    @endphp

    @foreach($tabs as $key => $tab)
        <a href="{{ request()->fullUrlWithQuery(['status' => $key, 'page' => 1]) }}" 
           class="nav-tab {{ $currentStatus === $key ? 'active' : '' }}" 
           style="text-decoration:none;display:inline-flex;align-items:center;gap:8px;padding:8px 16px;border-radius:6px;font-size:13px;font-weight:600;{{ $currentStatus === $key ? 'background:var(--color-surface-highest);color:var(--color-primary);border:1px solid var(--color-primary);' : 'background:var(--color-surface-low);color:var(--color-outline);border:1px solid transparent;' }}">
            <span>{{ $tab['label'] }}</span>
            <span style="font-size:11px;padding:2px 7px;border-radius:10px;{{ $currentStatus === $key ? 'background:var(--color-primary);color:#000;font-weight:700;' : 'background:var(--color-surface-highest);color:var(--color-outline);' }}">
                {{ $tab['count'] }}
            </span>
        </a>
    @endforeach
</div>

{{-- Filters Card --}}
<div class="card-dark" style="margin-bottom:24px;padding:16px;">
    <form method="GET" action="{{ route('admin.payment-requests.index') }}" style="display:flex;gap:14px;align-items:flex-end;flex-wrap:wrap;">
        <input type="hidden" name="status" value="{{ $currentStatus }}">

        {{-- Method filter --}}
        <div style="min-width:180px;">
            <label class="form-label-dark" style="font-size:11px;">Payment Method</label>
            <select name="method" class="form-control-dark" style="padding:8px 12px;font-size:13px;">
                <option value="all" {{ request('method') === 'all' || !request('method') ? 'selected' : '' }}>All Methods</option>
                <option value="easypaisa" {{ request('method') === 'easypaisa' ? 'selected' : '' }}>Easypaisa</option>
                <option value="jazzcash" {{ request('method') === 'jazzcash' ? 'selected' : '' }}>JazzCash</option>
                <option value="bank_transfer" {{ request('method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
            </select>
        </div>

        {{-- Date range --}}
        <div>
            <label class="form-label-dark" style="font-size:11px;">From Date</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control-dark" style="padding:8px 12px;font-size:13px;">
        </div>

        <div>
            <label class="form-label-dark" style="font-size:11px;">To Date</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control-dark" style="padding:8px 12px;font-size:13px;">
        </div>

        {{-- Buttons --}}
        <div style="display:flex;gap:8px;">
            <button type="submit" class="btn-primary" style="padding:8px 16px;font-size:13px;">
                <span class="material-symbols-outlined" style="font-size:16px;">filter_list</span>
                Apply Filter
            </button>
            @if(request()->hasAny(['method', 'date_from', 'date_to']) && (request('method') !== 'all' || request('date_from') || request('date_to')))
                <a href="{{ route('admin.payment-requests.index', ['status' => $currentStatus]) }}" class="btn-secondary" style="padding:8px 14px;font-size:13px;text-decoration:none;">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Submissions Table --}}
<div class="card-dark" style="padding:0;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="table-dark" style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="background:var(--color-surface-low);border-bottom:1px solid var(--color-surface-highest);text-align:left;font-size:11px;font-family:var(--font-heading);letter-spacing:.08em;color:var(--color-outline);text-transform:uppercase;">
                    <th style="padding:14px 18px;">Member</th>
                    <th style="padding:14px 18px;">Plan</th>
                    <th style="padding:14px 18px;">Method</th>
                    <th style="padding:14px 18px;">Amount</th>
                    <th style="padding:14px 18px;">Transaction ID</th>
                    <th style="padding:14px 18px;">Submitted</th>
                    <th style="padding:14px 18px;">Status</th>
                    <th style="padding:14px 18px;text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($submissions as $sub)
                    <tr style="border-bottom:1px solid var(--color-surface-highest);transition:background .15s;" onmouseover="this.style.background='var(--color-surface-low)'" onmouseout="this.style.background='transparent'">
                        {{-- Member --}}
                        <td style="padding:14px 18px;">
                            <div style="font-weight:600;color:var(--color-on-surface);">
                                {{ $sub->member?->user?->name ?? 'Gym Member' }}
                            </div>
                            <div style="font-size:11px;color:var(--color-outline);margin-top:2px;">
                                {{ $sub->member?->member_code ?? ('MEM-' . str_pad($sub->member_id, 4, '0', STR_PAD_LEFT)) }} • {{ $sub->member?->user?->phone ?? 'No phone' }}
                            </div>
                        </td>

                        {{-- Plan --}}
                        <td style="padding:14px 18px;">
                            <div style="font-weight:600;color:var(--color-on-surface);">
                                {{ $sub->plan?->name ?? 'Plan' }}
                            </div>
                            <div style="font-size:11px;color:var(--color-outline);">
                                {{ $sub->plan?->duration_days ?? 30 }} Days
                            </div>
                        </td>

                        {{-- Method --}}
                        <td style="padding:14px 18px;">
                            <div style="display:flex;align-items:center;gap:6px;">
                                <span class="material-symbols-outlined" style="font-size:16px;color:var(--color-primary);">
                                    {{ $sub->paymentMethod?->icon ?? 'payments' }}
                                </span>
                                <span style="font-weight:500;">
                                    {{ $sub->paymentMethod?->display_name ?? 'Manual' }}
                                </span>
                            </div>
                        </td>

                        {{-- Amount --}}
                        <td style="padding:14px 18px;">
                            <span style="font-weight:700;color:#fff;">
                                Rs. {{ number_format($sub->amount) }}
                            </span>
                        </td>

                        {{-- Transaction Reference --}}
                        <td style="padding:14px 18px;">
                            <span style="font-family:monospace;font-weight:600;color:var(--color-primary);background:rgba(57,255,20,0.06);padding:3px 6px;border-radius:4px;border:1px solid rgba(57,255,20,0.15);">
                                {{ $sub->transaction_reference }}
                            </span>
                        </td>

                        {{-- Submitted --}}
                        <td style="padding:14px 18px;color:var(--color-outline);">
                            <div>{{ $sub->created_at->format('d M Y') }}</div>
                            <div style="font-size:11px;">{{ $sub->created_at->format('h:i A') }}</div>
                        </td>

                        {{-- Status --}}
                        <td style="padding:14px 18px;">
                            @if($sub->isPending())
                                <span class="badge-accent" style="background:rgba(245,158,11,0.15);color:#f59e0b;border:1px solid rgba(245,158,11,0.3);font-size:11px;padding:3px 8px;border-radius:12px;font-weight:700;display:inline-flex;align-items:center;gap:4px;">
                                    <span class="material-symbols-outlined" style="font-size:12px;">hourglass_empty</span> Pending
                                </span>
                            @elseif($sub->isApproved())
                                <span class="badge-accent" style="background:rgba(57,255,20,0.12);color:var(--color-primary);border:1px solid rgba(57,255,20,0.3);font-size:11px;padding:3px 8px;border-radius:12px;font-weight:700;display:inline-flex;align-items:center;gap:4px;">
                                    <span class="material-symbols-outlined" style="font-size:12px;">check_circle</span> Approved
                                </span>
                            @else
                                <span class="badge-accent" style="background:rgba(239,68,68,0.15);color:#ef4444;border:1px solid rgba(239,68,68,0.3);font-size:11px;padding:3px 8px;border-radius:12px;font-weight:700;display:inline-flex;align-items:center;gap:4px;">
                                    <span class="material-symbols-outlined" style="font-size:12px;">cancel</span> Rejected
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td style="padding:14px 18px;text-align:right;">
                            <button type="button" class="btn-secondary" style="padding:6px 12px;font-size:12px;display:inline-flex;align-items:center;gap:4px;" onclick="openDetailsModal({{ $sub->id }})">
                                <span class="material-symbols-outlined" style="font-size:16px;">visibility</span>
                                View Proof
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding:48px 20px;text-align:center;color:var(--color-outline);">
                            <span class="material-symbols-outlined" style="font-size:48px;opacity:0.4;margin-bottom:8px;">pending_actions</span>
                            <div style="font-size:15px;font-weight:600;color:var(--color-on-surface);">No payment requests found</div>
                            <div style="font-size:12px;margin-top:4px;">When members submit payment proofs for renewals, they will appear here for review.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($submissions->hasPages())
        <div style="padding:16px 20px;border-top:1px solid var(--color-surface-highest);">
            {{ $submissions->links() }}
        </div>
    @endif
</div>

{{-- Details Modal / Panel --}}
<div id="details-modal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.8);z-index:9999;overflow-y:auto;padding:24px 16px;">
    <div style="max-width:680px;margin:20px auto;background:#181818;border:1px solid #2e2e2e;border-radius:12px;padding:24px;position:relative;box-shadow:0 20px 40px rgba(0,0,0,0.5);">
        {{-- Close button --}}
        <button type="button" onclick="closeDetailsModal()" style="position:absolute;top:16px;right:16px;background:none;border:none;color:var(--color-outline);cursor:pointer;font-size:24px;line-height:1;" title="Close">
            &times;
        </button>

        {{-- Dynamic content container --}}
        <div id="details-modal-content">
            <div style="text-align:center;padding:40px;color:var(--color-outline);">
                <span class="material-symbols-outlined" style="font-size:32px;animation:spin 1s infinite linear;">progress_activity</span>
                <div style="margin-top:8px;">Loading payment details...</div>
            </div>
        </div>
    </div>
</div>

{{-- Inline modal details for non-AJAX or instant fallback --}}
<div id="hidden-details-data" style="display:none;">
    @foreach($submissions as $submission)
        <div id="sub-detail-{{ $submission->id }}">
            @include('admin.payment-requests.partials.details', ['submission' => $submission])
        </div>
    @endforeach
</div>

@push('head')
<script>
    function openDetailsModal(id) {
        var modal = document.getElementById('details-modal');
        var content = document.getElementById('details-modal-content');
        var cached = document.getElementById('sub-detail-' + id);

        if (cached) {
            content.innerHTML = cached.innerHTML;
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        } else {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
            fetch('{{ url("admin/payment-requests") }}/' + id, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.html) {
                    content.innerHTML = data.html;
                }
            })
            .catch(function(err) {
                content.innerHTML = '<div style="color:var(--color-error);padding:20px;text-align:center;">Failed to load details. Please refresh the page.</div>';
            });
        }
    }

    function closeDetailsModal() {
        var modal = document.getElementById('details-modal');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDetailsModal();
    });
</script>
<style>
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endpush
@endsection
