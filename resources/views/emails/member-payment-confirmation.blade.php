<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Received - Under Review</title>
</head>
<body style="margin:0;padding:0;background-color:#0d0d0d;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#e6e6e6;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#0d0d0d;padding:30px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width:560px;background-color:#171717;border:1px solid #292929;border-radius:12px;overflow:hidden;text-align:left;">
                    <tr>
                        <td style="padding:28px 32px;background:linear-gradient(180deg,#1f1f1f 0%,#171717 100%);border-bottom:2px solid #39ff14;">
                            <div style="font-size:11px;font-weight:700;letter-spacing:1.5px;color:#39ff14;text-transform:uppercase;margin-bottom:6px;">
                                {{ $gymSettings['gym_name'] ?? 'Kinetic Apex' }} • SUBMISSION CONFIRMATION
                            </div>
                            <h1 style="margin:0;font-size:22px;color:#ffffff;font-weight:800;letter-spacing:-0.5px;">
                                Payment Submitted
                            </h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin-top:0;font-size:15px;line-height:1.6;color:#d1d1d1;">
                                Hello <strong style="color:#ffffff;">{{ $submission->member?->user?->name ?? 'Member' }}</strong>,
                            </p>
                            <p style="font-size:15px;line-height:1.6;color:#d1d1d1;">
                                We received your payment proof and will check it soon. Our team manually reviews each payment to confirm transaction credentials with our bank records.
                            </p>
                            <div style="background-color:#111111;border:1px solid #262626;border-radius:8px;padding:16px;margin:24px 0;font-size:13px;">
                                <div style="display:flex;justify-content:space-between;padding:4px 0;color:#888888;">
                                    <span>Plan Selected:</span>
                                    <span style="color:#ffffff;font-weight:600;">{{ $submission->plan?->name ?? 'Membership' }}</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;padding:4px 0;color:#888888;">
                                    <span>Amount Paid:</span>
                                    <span style="color:#39ff14;font-weight:700;">Rs. {{ number_format($submission->amount) }}</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;padding:4px 0;color:#888888;">
                                    <span>Payment Method:</span>
                                    <span style="color:#ffffff;">{{ $submission->paymentMethod?->display_name ?? 'Manual' }}</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;padding:4px 0;color:#888888;">
                                    <span>Transaction Reference:</span>
                                    <span style="color:#ffffff;font-family:monospace;font-weight:600;">{{ $submission->transaction_reference }}</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;padding:4px 0;color:#888888;">
                                    <span>Status:</span>
                                    <span style="color:#f59e0b;font-weight:700;">Pending Review</span>
                                </div>
                            </div>
                            <p style="font-size:14px;line-height:1.6;color:#b0b0b0;">
                                Once verified, you will receive your official membership renewal receipt and full access immediately.
                            </p>
                            <div style="text-align:center;margin:28px 0 10px;">
                                <a href="{{ route('member.payments.index') }}" style="background-color:#39ff14;color:#000000;padding:12px 24px;border-radius:6px;font-size:13px;font-weight:800;text-decoration:none;display:inline-block;letter-spacing:0.5px;text-transform:uppercase;">
                                    Track Submission Status
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 32px;background-color:#121212;border-top:1px solid #222222;text-align:center;font-size:11px;color:#666666;">
                            {{ $gymSettings['gym_name'] ?? 'Kinetic Apex' }} Support • {{ $gymSettings['gym_phone'] ?? '+92-300-0000000' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
