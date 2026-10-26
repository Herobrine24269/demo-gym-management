<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Could Not Be Verified</title>
</head>
<body style="margin:0;padding:0;background-color:#0d0d0d;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#e6e6e6;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#0d0d0d;padding:30px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width:560px;background-color:#171717;border:1px solid #292929;border-radius:12px;overflow:hidden;text-align:left;">
                    {{-- Header --}}
                    <tr>
                        <td style="padding:28px 32px;background:linear-gradient(180deg,#1f1f1f 0%,#171717 100%);border-bottom:2px solid #ff4444;">
                            <div style="font-size:11px;font-weight:700;letter-spacing:1.5px;color:#ff6b6b;text-transform:uppercase;margin-bottom:6px;">
                                {{ $gymSettings['gym_name'] ?? 'Kinetic Apex' }} • PAYMENT UPDATE
                            </div>
                            <h1 style="margin:0;font-size:22px;color:#ffffff;font-weight:800;letter-spacing:-0.5px;">
                                Payment Could Not Be Verified
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
                                We were unable to verify your recent manual payment submission for <strong>{{ $submission->plan?->name ?? 'Membership' }}</strong> (Rs. {{ number_format($submission->amount) }}).
                            </p>

                            {{-- Rejection reason box --}}
                            <div style="background-color:#1a0f0f;border:1px solid #4a1919;border-left:4px solid #ff4444;border-radius:6px;padding:16px;margin:24px 0;">
                                <div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:#ff8888;font-weight:700;margin-bottom:6px;">
                                    REASON PROVIDED BY ADMIN:
                                </div>
                                <div style="font-size:14px;color:#ffffff;line-height:1.5;">
                                    {{ $submission->rejection_reason ?: 'The uploaded receipt was unreadable or the transaction reference did not match our bank statement.' }}
                                </div>
                            </div>

                            <p style="font-size:14px;line-height:1.6;color:#b0b0b0;">
                                Don't worry! You can easily resubmit with a clear screenshot or check your transaction ID to ensure it matches your payment receipt.
                            </p>

                            <div style="text-align:center;margin:32px 0 16px;">
                                <a href="{{ route('member.renewals.index') }}" style="background-color:#39ff14;color:#000000;padding:14px 28px;border-radius:6px;font-size:14px;font-weight:800;text-decoration:none;display:inline-block;letter-spacing:0.5px;text-transform:uppercase;">
                                    Submit Payment Again
                                </a>
                            </div>

                            <div style="margin-top:28px;padding-top:20px;border-top:1px solid #262626;font-size:12px;color:#888888;line-height:1.6;">
                                Need help? Contact us directly at {{ $gymSettings['gym_email'] ?? 'info@kineticapex.com' }} or call {{ $gymSettings['gym_phone'] ?? '+92-300-0000000' }}.
                            </div>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:16px 32px;background-color:#121212;border-top:1px solid #222222;text-align:center;font-size:11px;color:#666666;">
                            This is an automated notification from {{ $gymSettings['gym_name'] ?? 'Kinetic Apex' }}.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
