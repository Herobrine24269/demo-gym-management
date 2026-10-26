<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Received, Membership Renewed</title>
</head>
<body style="margin:0;padding:0;background-color:#0d0d0d;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#e6e6e6;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#0d0d0d;padding:30px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width:580px;background-color:#171717;border:1px solid #292929;border-radius:12px;overflow:hidden;text-align:left;">
                    {{-- Header --}}
                    <tr>
                        <td style="padding:28px 32px;background:linear-gradient(180deg,#1f1f1f 0%,#171717 100%);border-bottom:2px solid #39ff14;">
                            <div style="font-size:11px;font-weight:700;letter-spacing:1.5px;color:#39ff14;text-transform:uppercase;margin-bottom:6px;">
                                {{ $gymSettings['gym_name'] ?? 'Kinetic Apex Gym' }} • PAYMENT CONFIRMED
                            </div>
                            <h1 style="margin:0;font-size:22px;color:#ffffff;font-weight:800;letter-spacing:-0.5px;">
                                Payment Received, Membership Renewed
                            </h1>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin-top:0;font-size:15px;line-height:1.6;color:#d1d1d1;">
                                Hello <strong style="color:#ffffff;">{{ $submission->member?->user?->name ?? 'Member' }}</strong>,
                            </p>
                            <p style="font-size:15px;line-height:1.6;color:#d1d1d1;">
                                Great news! We have verified your manual payment. Your membership is now renewed and active. Your official PDF receipt is attached to this email.
                            </p>

                            {{-- Receipt summary box --}}
                            <div style="background-color:#111111;border:1px solid #262626;border-radius:8px;padding:20px;margin:24px 0;">
                                <div style="display:flex;justify-content:space-between;border-bottom:1px solid #222222;padding-bottom:12px;margin-bottom:14px;">
                                    <div>
                                        <div style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:#888888;">RECEIPT NUMBER</div>
                                        <div style="font-size:16px;font-weight:800;color:#39ff14;">{{ $submission->receipt_number }}</div>
                                    </div>
                                    <div style="text-align:right;">
                                        <div style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:#888888;">STATUS</div>
                                        <div style="font-size:13px;font-weight:700;color:#39ff14;">PAID & VERIFIED</div>
                                    </div>
                                </div>

                                <table role="presentation" width="100%" style="font-size:13px;color:#d1d1d1;">
                                    <tr>
                                        <td style="padding:6px 0;color:#888888;">Member Code:</td>
                                        <td style="padding:6px 0;color:#ffffff;font-weight:600;text-align:right;">{{ $submission->member?->member_code ?? ('MEM-' . str_pad($submission->member_id, 4, '0', STR_PAD_LEFT)) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:6px 0;color:#888888;">Plan Renewed:</td>
                                        <td style="padding:6px 0;color:#ffffff;font-weight:600;text-align:right;">{{ $submission->plan?->name ?? 'Membership' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:6px 0;color:#888888;">Amount Paid:</td>
                                        <td style="padding:6px 0;color:#39ff14;font-weight:800;font-size:15px;text-align:right;">Rs. {{ number_format($submission->amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:6px 0;color:#888888;">Payment Method:</td>
                                        <td style="padding:6px 0;color:#ffffff;text-align:right;">{{ $submission->paymentMethod?->display_name ?? 'Manual' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:6px 0;color:#888888;">Transaction ID:</td>
                                        <td style="padding:6px 0;color:#ffffff;font-family:monospace;font-weight:600;text-align:right;">{{ $submission->transaction_reference }}</td>
                                    </tr>
                                    @if($submission->membership)
                                    <tr>
                                        <td style="padding:6px 0;color:#888888;">New Access Period:</td>
                                        <td style="padding:6px 0;color:#ffffff;font-weight:600;text-align:right;">
                                            {{ $submission->membership->start_date?->format('d M Y') }} to {{ $submission->membership->end_date?->format('d M Y') }}
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                            </div>

                            <p style="font-size:14px;line-height:1.6;color:#b0b0b0;">
                                You can download and print your receipt at any time from your member portal under <strong>My Payments & Billing</strong>.
                            </p>

                            <div style="text-align:center;margin:30px 0 10px;">
                                <a href="{{ route('member.payments.index') }}" style="background-color:#39ff14;color:#000000;padding:14px 28px;border-radius:6px;font-size:14px;font-weight:800;text-decoration:none;display:inline-block;letter-spacing:0.5px;text-transform:uppercase;">
                                    View in Portal
                                </a>
                            </div>

                            <div style="margin-top:28px;padding-top:20px;border-top:1px solid #262626;font-size:12px;color:#888888;line-height:1.6;">
                                <strong>{{ $gymSettings['gym_name'] ?? 'Kinetic Apex Gym' }}</strong><br>
                                {{ $gymSettings['gym_address'] ?? 'Karachi, Pakistan' }}<br>
                                Phone: {{ $gymSettings['gym_phone'] ?? '+92-300-0000000' }} • Email: {{ $gymSettings['gym_email'] ?? 'info@kineticapex.com' }}
                            </div>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:16px 32px;background-color:#121212;border-top:1px solid #222222;text-align:center;font-size:11px;color:#666666;">
                            This is an automated receipt confirmation from {{ $gymSettings['gym_name'] ?? 'Kinetic Apex' }}.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
