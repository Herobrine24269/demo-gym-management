<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Payment Submitted</title>
</head>
<body style="margin:0;padding:0;background-color:#0d0d0d;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#e6e6e6;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#0d0d0d;padding:30px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width:560px;background-color:#171717;border:1px solid #292929;border-radius:12px;overflow:hidden;text-align:left;">
                    {{-- Header --}}
                    <tr>
                        <td style="padding:28px 32px;background:linear-gradient(180deg,#1f1f1f 0%,#171717 100%);border-bottom:2px solid #39ff14;">
                            <div style="font-size:11px;font-weight:700;letter-spacing:1.5px;color:#39ff14;text-transform:uppercase;margin-bottom:6px;">
                                {{ $gymName }} • PAYMENT ALERT
                            </div>
                            <h1 style="margin:0;font-size:22px;color:#ffffff;font-weight:800;letter-spacing:-0.5px;">
                                New Payment Submitted
                            </h1>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin-top:0;font-size:15px;line-height:1.6;color:#d1d1d1;">
                                Hello Admin,
                            </p>
                            <p style="font-size:15px;line-height:1.6;color:#d1d1d1;">
                                A member has submitted a manual payment proof on the website. Please review the transaction details and verify the payment.
                            </p>

                            {{-- Payment details table --}}
                            <table role="presentation" width="100%" style="background-color:#101010;border:1px solid #262626;border-radius:8px;margin:24px 0;padding:16px;font-size:14px;">
                                <tr>
                                    <td style="padding:8px;color:#8a8a8a;width:40%;">Member Name:</td>
                                    <td style="padding:8px;color:#ffffff;font-weight:600;">{{ $submission->member?->user?->name ?? 'Gym Member' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px;color:#8a8a8a;">Selected Plan:</td>
                                    <td style="padding:8px;color:#ffffff;font-weight:600;">{{ $submission->plan?->name ?? 'Membership' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px;color:#8a8a8a;">Amount Paid:</td>
                                    <td style="padding:8px;color:#39ff14;font-weight:700;font-size:16px;">Rs. {{ number_format($submission->amount) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px;color:#8a8a8a;">Payment Method:</td>
                                    <td style="padding:8px;color:#ffffff;">{{ $submission->paymentMethod?->display_name ?? 'Manual Payment' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px;color:#8a8a8a;">Transaction ID:</td>
                                    <td style="padding:8px;color:#ffffff;font-family:monospace;font-weight:700;">{{ $submission->transaction_reference }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px;color:#8a8a8a;">Submitted At:</td>
                                    <td style="padding:8px;color:#ffffff;">{{ $submission->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            </table>

                            @if($submission->note)
                                <div style="background-color:#121212;border-left:3px solid #39ff14;padding:12px 14px;border-radius:4px;font-size:13px;color:#b0b0b0;margin-bottom:24px;">
                                    <strong style="color:#ffffff;">Member Note:</strong> {{ $submission->note }}
                                </div>
                            @endif

                            {{-- CTA button --}}
                            <div style="text-align:center;margin:32px 0 16px;">
                                <a href="{{ $reviewUrl }}" style="background-color:#39ff14;color:#000000;padding:14px 28px;border-radius:6px;font-size:14px;font-weight:800;text-decoration:none;display:inline-block;letter-spacing:0.5px;text-transform:uppercase;">
                                    Review Payment
                                </a>
                            </div>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:20px 32px;background-color:#121212;border-top:1px solid #222222;text-align:center;font-size:12px;color:#707070;">
                            This is an automated alert from {{ $gymName }}.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
