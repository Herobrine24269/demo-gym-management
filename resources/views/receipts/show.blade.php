@extends('layouts.dashboard')
@section('title', 'Payment Receipt ' . $submission->receipt_number)

@section('content')
<div class="page-header" style="max-width:800px;margin:0 auto 20px;">
    <div class="page-title-group">
        <div class="page-eyebrow">
            <span class="eyebrow-dot"></span>
            <span class="eyebrow-text">PAYMENT RECORD</span>
            <span style="color:var(--color-outline);font-size:12px;margin:0 4px;">/</span>
            <span class="eyebrow-text" style="color:var(--color-outline);">OFFICIAL RECEIPT</span>
        </div>
        <h1 class="page-title">{{ $submission->receipt_number }}</h1>
    </div>
    <div style="display:flex;align-items:center;gap:10px;">
        <button onclick="window.print()" class="btn-secondary" style="display:flex;align-items:center;gap:6px;">
            <span class="material-symbols-outlined" style="font-size:18px;">print</span>
            <span>Print Receipt</span>
        </button>
        <a href="{{ route('member.payments.receipt-pdf', $submission) }}" class="btn-primary" style="display:flex;align-items:center;gap:6px;text-decoration:none;">
            <span class="material-symbols-outlined" style="font-size:18px;">download</span>
            <span>Download PDF</span>
        </a>
    </div>
</div>

<div class="card-dark" style="max-width:800px;margin:0 auto;background:#181818;border:1px solid #2d2d2d;padding:36px;border-radius:12px;">
    {{-- Top header --}}
    <div style="display:flex;justify-content:space-between;align-items:flex-start;border-bottom:1px solid #2d2d2d;padding-bottom:24px;margin-bottom:28px;">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                <div style="width:36px;height:36px;border-radius:8px;background:rgba(57,255,20,0.12);border:1px solid var(--color-primary);display:flex;align-items:center;justify-content:center;color:var(--color-primary);">
                    <span class="material-symbols-outlined">bolt</span>
                </div>
                <div style="font-family:var(--font-heading);font-size:22px;font-weight:900;letter-spacing:1px;color:#fff;">
                    {{ $gymSettings['gym_name'] ?? 'KINETIC APEX' }}
                </div>
            </div>
            <div style="font-size:12px;color:var(--color-outline);line-height:1.6;">
                {{ $gymSettings['gym_address'] ?? 'Karachi, Pakistan' }}<br>
                Phone: {{ $gymSettings['gym_phone'] ?? '+92-300-0000000' }} • Email: {{ $gymSettings['gym_email'] ?? 'info@kineticapex.com' }}
            </div>
        </div>
        <div style="text-align:right;">
            <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(57,255,20,0.12);color:var(--color-primary);padding:4px 12px;border-radius:20px;border:1px solid rgba(57,255,20,0.3);font-size:11px;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;">
                <span class="material-symbols-outlined" style="font-size:14px;">verified</span> Verified & Paid
            </div>
            <div style="font-size:16px;font-weight:700;color:#fff;margin-top:8px;">{{ $submission->receipt_number }}</div>
            <div style="font-size:11px;color:var(--color-outline);margin-top:2px;">
                Issued: {{ $submission->reviewed_at ? \Carbon\Carbon::parse($submission->reviewed_at)->format('d M Y, h:i A') : now()->format('d M Y') }}
            </div>
        </div>
    </div>

    {{-- Details grid --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:28px;">
        <div style="background:#131313;border:1px solid #222;border-radius:8px;padding:16px;">
            <div style="font-size:10px;font-weight:700;letter-spacing:0.08em;color:var(--color-outline);text-transform:uppercase;margin-bottom:8px;">
                MEMBER DETAILS
            </div>
            <div style="font-size:16px;font-weight:700;color:#fff;margin-bottom:4px;">
                {{ $submission->member?->user?->name ?? 'Gym Member' }}
            </div>
            <div style="font-size:12px;color:var(--color-outline);line-height:1.6;">
                <strong>Member ID:</strong> {{ $submission->member?->member_code ?? ('MEM-' . str_pad($submission->member_id, 4, '0', STR_PAD_LEFT)) }}<br>
                <strong>Email:</strong> {{ $submission->member?->user?->email ?? 'N/A' }}<br>
                <strong>Phone:</strong> {{ $submission->member?->user?->phone ?? 'N/A' }}
            </div>
        </div>

        <div style="background:#131313;border:1px solid #222;border-radius:8px;padding:16px;">
            <div style="font-size:10px;font-weight:700;letter-spacing:0.08em;color:var(--color-outline);text-transform:uppercase;margin-bottom:8px;">
                PAYMENT TRANSACTION
            </div>
            <div style="font-size:12px;color:var(--color-outline);line-height:1.6;">
                <strong>Method:</strong> <span style="color:#fff;">{{ $submission->paymentMethod?->display_name ?? 'Manual Payment' }}</span><br>
                <strong>Transaction ID:</strong> <span style="font-family:monospace;font-weight:700;color:var(--color-primary);">{{ $submission->transaction_reference }}</span><br>
                <strong>Paid At:</strong> {{ $submission->paid_at ? \Carbon\Carbon::parse($submission->paid_at)->format('d M Y, h:i A') : 'N/A' }}<br>
                <strong>Verified By:</strong> {{ $submission->reviewer?->name ?? 'Admin Desk' }}
            </div>
        </div>
    </div>

    {{-- Table of items --}}
    <div style="border:1px solid #222;border-radius:8px;overflow:hidden;margin-bottom:24px;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="background:#131313;border-bottom:1px solid #222;color:var(--color-outline);text-transform:uppercase;font-size:11px;letter-spacing:0.06em;">
                    <th style="padding:12px 16px;text-align:left;">Description</th>
                    <th style="padding:12px 16px;text-align:left;">Access Period</th>
                    <th style="padding:12px 16px;text-align:left;">Duration</th>
                    <th style="padding:12px 16px;text-align:right;">Amount (PKR)</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom:1px solid #222;">
                    <td style="padding:14px 16px;">
                        <strong style="color:#fff;font-size:14px;">{{ $submission->plan?->name ?? 'Gym Membership Renewal' }}</strong>
                        <div style="color:var(--color-outline);font-size:11px;margin-top:2px;">Verified Manual Renewal • Unrestricted Gym Access</div>
                    </td>
                    <td style="padding:14px 16px;color:var(--color-outline);">
                        @if($submission->membership)
                            {{ $submission->membership->start_date?->format('d M Y') }} - {{ $submission->membership->end_date?->format('d M Y') }}
                        @else
                            Active Membership
                        @endif
                    </td>
                    <td style="padding:14px 16px;color:var(--color-outline);">
                        {{ $submission->plan?->duration_days ?? 30 }} Days
                    </td>
                    <td style="padding:14px 16px;text-align:right;color:#fff;font-weight:700;font-size:15px;">
                        Rs. {{ number_format($submission->amount, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Summary --}}
    <div style="display:flex;justify-content:flex-end;margin-bottom:28px;">
        <div style="width:280px;background:#131313;border:1px solid #222;border-radius:8px;padding:16px;">
            <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--color-outline);margin-bottom:8px;">
                <span>Subtotal:</span>
                <span style="color:#fff;">Rs. {{ number_format($submission->amount, 2) }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--color-outline);margin-bottom:12px;">
                <span>Discount:</span>
                <span style="color:#fff;">Rs. 0.00</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:15px;font-weight:800;color:var(--color-primary);border-top:1px solid #222;padding-top:10px;">
                <span>Total Paid:</span>
                <span>Rs. {{ number_format($submission->amount, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Note --}}
    @if($submission->note)
        <div style="background:#131313;border-left:3px solid var(--color-primary);padding:10px 14px;border-radius:4px;font-size:12px;color:var(--color-outline);margin-bottom:24px;">
            <strong style="color:#fff;">Member Note:</strong> {{ $submission->note }}
        </div>
    @endif

    {{-- Footer --}}
    <div style="border-top:1px dashed #333;padding-top:18px;text-align:center;font-size:11px;color:var(--color-outline);line-height:1.6;">
        Thank you for working out with {{ $gymSettings['gym_name'] ?? 'Kinetic Apex' }}!<br>
        This is an official computer-generated receipt for a verified manual payment. No signature is required.
    </div>
</div>
@endsection
