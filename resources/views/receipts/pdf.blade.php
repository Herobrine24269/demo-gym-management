<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt - {{ $submission->receipt_number }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1a1a1a;
            margin: 0;
            padding: 24px;
            font-size: 13px;
            background: #ffffff;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #22c55e;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .gym-title {
            font-size: 24px;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .gym-sub {
            color: #64748b;
            font-size: 11px;
            margin-top: 4px;
        }
        .receipt-badge {
            text-align: right;
        }
        .receipt-title {
            font-size: 20px;
            font-weight: bold;
            color: #16a34a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .receipt-number {
            font-size: 13px;
            font-weight: bold;
            color: #334155;
            margin-top: 4px;
        }
        .badge-verified {
            display: inline-block;
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 6px;
        }
        .details-table {
            width: 100%;
            margin-bottom: 24px;
        }
        .details-col {
            width: 50%;
            vertical-align: top;
        }
        .card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 14px;
            margin-right: 8px;
        }
        .card-right {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 14px;
            margin-left: 8px;
        }
        .section-label {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #64748b;
            margin-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .item-table th {
            background: #0f172a;
            color: #ffffff;
            padding: 10px 12px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: left;
        }
        .item-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 12px;
        }
        .total-box {
            width: 100%;
            margin-bottom: 24px;
        }
        .total-table {
            float: right;
            width: 280px;
            border-collapse: collapse;
        }
        .total-table td {
            padding: 8px 12px;
        }
        .total-row {
            background: #f0fdf4;
            border-top: 2px solid #22c55e;
            font-weight: bold;
            font-size: 15px;
            color: #15803d;
        }
        .clear { clear: both; }
        .footer {
            border-top: 1px dashed #cbd5e1;
            padding-top: 14px;
            font-size: 10px;
            color: #64748b;
            text-align: center;
            line-height: 1.5;
        }
    </style>
</head>
<body>

    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <div class="gym-title">{{ $gymSettings['gym_name'] ?? 'Kinetic Apex Gym' }}</div>
                <div class="gym-sub">
                    {{ $gymSettings['gym_address'] ?? 'Karachi, Pakistan' }}<br>
                    Phone: {{ $gymSettings['gym_phone'] ?? '+92-300-0000000' }} | Email: {{ $gymSettings['gym_email'] ?? 'info@kineticapex.com' }}
                </div>
            </td>
            <td class="receipt-badge">
                <div class="receipt-title">Payment Receipt</div>
                <div class="receipt-number">{{ $submission->receipt_number }}</div>
                <div class="badge-verified">Verified & Paid</div>
            </td>
        </tr>
    </table>

    <table class="details-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="details-col">
                <div class="card">
                    <div class="section-label">Member Details</div>
                    <div style="font-size:14px;font-weight:bold;color:#0f172a;margin-bottom:4px;">
                        {{ $submission->member?->user?->name ?? 'Gym Member' }}
                    </div>
                    <div style="color:#475569;line-height:1.6;">
                        <strong>Member Code:</strong> {{ $submission->member?->member_code ?? ('MEM-' . str_pad($submission->member_id, 4, '0', STR_PAD_LEFT)) }}<br>
                        <strong>Phone:</strong> {{ $submission->member?->user?->phone ?? 'N/A' }}<br>
                        <strong>Email:</strong> {{ $submission->member?->user?->email ?? 'N/A' }}
                    </div>
                </div>
            </td>
            <td class="details-col">
                <div class="card-right">
                    <div class="section-label">Payment Information</div>
                    <div style="color:#475569;line-height:1.6;">
                        <strong>Payment Date:</strong> {{ $submission->paid_at ? \Carbon\Carbon::parse($submission->paid_at)->format('d M Y, h:i A') : $submission->created_at->format('d M Y') }}<br>
                        <strong>Payment Method:</strong> {{ $submission->paymentMethod?->display_name ?? ucfirst($submission->paymentMethod?->type ?? 'Manual') }}<br>
                        <strong>Transaction ID:</strong> <span style="font-family:monospace;font-weight:bold;color:#0f172a;">{{ $submission->transaction_reference }}</span><br>
                        <strong>Verified By:</strong> {{ $submission->reviewer?->name ?? 'Admin Desk' }} ({{ $submission->reviewed_at ? \Carbon\Carbon::parse($submission->reviewed_at)->format('d M Y') : 'Verified' }})
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <table class="item-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th>Description</th>
                <th>Validity Period</th>
                <th>Duration</th>
                <th style="text-align:right;">Amount (PKR)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong style="color:#0f172a;font-size:13px;">{{ $submission->plan?->name ?? 'Gym Membership Renewal' }}</strong><br>
                    <span style="color:#64748b;font-size:11px;">Full Gym Access • Manual verification confirmed</span>
                </td>
                <td>
                    @if($submission->membership)
                        {{ $submission->membership->start_date?->format('d M Y') }} to {{ $submission->membership->end_date?->format('d M Y') }}
                    @else
                        Active
                    @endif
                </td>
                <td>{{ $submission->plan?->duration_days ?? 30 }} Days</td>
                <td style="text-align:right;font-weight:bold;font-size:13px;">
                    Rs. {{ number_format($submission->amount, 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="total-box">
        <table class="total-table" cellpadding="0" cellspacing="0">
            <tr>
                <td style="color:#64748b;">Subtotal:</td>
                <td style="text-align:right;font-weight:bold;">Rs. {{ number_format($submission->amount, 2) }}</td>
            </tr>
            <tr>
                <td style="color:#64748b;">Discount:</td>
                <td style="text-align:right;">Rs. 0.00</td>
            </tr>
            <tr class="total-row">
                <td>Total Paid:</td>
                <td style="text-align:right;">Rs. {{ number_format($submission->amount, 2) }}</td>
            </tr>
        </table>
        <div class="clear"></div>
    </div>

    @if($submission->note)
        <div style="background:#f8fafc;border-left:3px solid #64748b;padding:8px 12px;margin-bottom:20px;font-size:11px;color:#475569;">
            <strong>Member Note:</strong> {{ $submission->note }}
        </div>
    @endif

    <div class="footer">
        Thank you for working out with {{ $gymSettings['gym_name'] ?? 'Kinetic Apex' }}!<br>
        This is an official computer-generated receipt for a verified manual payment. No signature is required.<br>
        For inquiries, contact {{ $gymSettings['gym_email'] ?? 'support@kineticapex.com' }} or call {{ $gymSettings['gym_phone'] ?? '+92-300-0000000' }}.
    </div>

</body>
</html>
