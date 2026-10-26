@extends('layouts.dashboard')
@section('title', 'Payment Methods')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow">
            <span class="eyebrow-dot"></span>
            <span class="eyebrow-text">PAYMENT GATEWAY CONFIGURATION</span>
        </div>
        <h1 class="page-title">Manual Payment Methods</h1>
        <p class="page-title-sub">Configure accounts where members can send manual fees (Easypaisa, JazzCash, and Bank Transfer). Members submit transaction slips for manual staff approval.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.payment-methods.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(340px, 1fr));gap:24px;margin-bottom:28px;">

        {{-- ── CARD 1: EASYPAISA ───────────────────────────────── --}}
        <div class="card-dark" style="display:flex;flex-direction:column;border:1px solid var(--color-surface-highest);position:relative;">
            <div style="display:flex;align-items:center;justify-content:space-between;padding-bottom:14px;border-bottom:1px solid var(--color-surface-highest);margin-bottom:16px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:38px;height:38px;border-radius:8px;background:rgba(57,255,20,.12);border:1px solid rgba(57,255,20,.25);color:var(--color-primary-container);display:flex;align-items:center;justify-content:center;">
                        <span class="material-symbols-outlined" style="font-size:20px;">account_balance_wallet</span>
                    </div>
                    <div>
                        <div style="font-family:var(--font-heading);font-size:16px;font-weight:700;text-transform:uppercase;color:var(--color-on-surface);">Easypaisa</div>
                        <div style="font-size:11px;color:var(--color-outline);">Mobile wallet payments</div>
                    </div>
                </div>

                {{-- Enabled Toggle --}}
                <label style="display:inline-flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="hidden" name="methods[easypaisa][is_enabled]" value="0">
                    <input type="checkbox" name="methods[easypaisa][is_enabled]" value="1" {{ old('methods.easypaisa.is_enabled', $easypaisa->is_enabled) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--color-primary-container);cursor:pointer;">
                    <span style="font-size:12px;font-family:var(--font-heading);letter-spacing:.05em;text-transform:uppercase;color:var(--color-on-surface);font-weight:600;">Enabled</span>
                </label>
            </div>

            <div style="display:flex;flex-direction:column;gap:14px;flex:1;">
                <div>
                    <label class="form-label-dark">Account Holder Name <span style="color:var(--color-primary-container);">*</span></label>
                    <input type="text" name="methods[easypaisa][account_name]" value="{{ old('methods.easypaisa.account_name', $easypaisa->account_name) }}" class="form-control-dark" placeholder="e.g. Kinetic Apex Official">
                    @error('methods.easypaisa.account_name')
                        <div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="form-label-dark">Phone Number (Pakistani Format) <span style="color:var(--color-primary-container);">*</span></label>
                    <input type="text" name="methods[easypaisa][phone]" value="{{ old('methods.easypaisa.phone', $easypaisa->phone) }}" class="form-control-dark" placeholder="e.g. 03001234567">
                    <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">Accepts 03001234567 or +923001234567</div>
                    @error('methods.easypaisa.phone')
                        <div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- QR Code Upload --}}
                <div>
                    <label class="form-label-dark">QR Code Image (Optional, max 2 MB)</label>
                    <div style="display:flex;align-items:center;gap:14px;margin-top:6px;">
                        <div id="easypaisa-qr-preview-box" style="width:72px;height:72px;border-radius:8px;background:var(--color-surface-container);border:1px dashed var(--color-surface-highest);display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;">
                            @if($easypaisa->qr_image_url)
                                <img src="{{ $easypaisa->qr_image_url }}" alt="QR" style="width:100%;height:100%;object-fit:cover;" id="easypaisa-qr-img">
                            @else
                                <span class="material-symbols-outlined" style="font-size:32px;color:var(--color-outline);" id="easypaisa-qr-placeholder">qr_code_2</span>
                            @endif
                        </div>
                        <div style="flex:1;">
                            <input type="file" name="methods[easypaisa][qr_image]" accept="image/*" class="form-control-dark" style="padding:6px;font-size:11px;" onchange="previewQr(this, 'easypaisa')">
                            @if($easypaisa->qr_image_url)
                            <label style="display:inline-flex;align-items:center;gap:6px;font-size:12px;color:var(--color-error);margin-top:6px;cursor:pointer;">
                                <input type="checkbox" name="methods[easypaisa][remove_qr]" value="1">
                                <span>Remove Current QR Code</span>
                            </label>
                            @endif
                        </div>
                    </div>
                    @error('methods.easypaisa.qr_image')
                        <div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="form-label-dark">Instructions for Member (Optional)</label>
                    <textarea name="methods[easypaisa][instructions]" class="form-control-dark form-textarea-dark" rows="3" placeholder="e.g. Send exact fee amount and keep the transaction screenshot.">{{ old('methods.easypaisa.instructions', $easypaisa->instructions) }}</textarea>
                </div>
            </div>
        </div>

        {{-- ── CARD 2: JAZZCASH ────────────────────────────────── --}}
        <div class="card-dark" style="display:flex;flex-direction:column;border:1px solid var(--color-surface-highest);position:relative;">
            <div style="display:flex;align-items:center;justify-content:space-between;padding-bottom:14px;border-bottom:1px solid var(--color-surface-highest);margin-bottom:16px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:38px;height:38px;border-radius:8px;background:rgba(255,180,171,.12);border:1px solid rgba(255,180,171,.25);color:var(--color-error);display:flex;align-items:center;justify-content:center;">
                        <span class="material-symbols-outlined" style="font-size:20px;">payments</span>
                    </div>
                    <div>
                        <div style="font-family:var(--font-heading);font-size:16px;font-weight:700;text-transform:uppercase;color:var(--color-on-surface);">JazzCash</div>
                        <div style="font-size:11px;color:var(--color-outline);">Mobile wallet payments</div>
                    </div>
                </div>

                {{-- Enabled Toggle --}}
                <label style="display:inline-flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="hidden" name="methods[jazzcash][is_enabled]" value="0">
                    <input type="checkbox" name="methods[jazzcash][is_enabled]" value="1" {{ old('methods.jazzcash.is_enabled', $jazzcash->is_enabled) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--color-primary-container);cursor:pointer;">
                    <span style="font-size:12px;font-family:var(--font-heading);letter-spacing:.05em;text-transform:uppercase;color:var(--color-on-surface);font-weight:600;">Enabled</span>
                </label>
            </div>

            <div style="display:flex;flex-direction:column;gap:14px;flex:1;">
                <div>
                    <label class="form-label-dark">Account Holder Name <span style="color:var(--color-primary-container);">*</span></label>
                    <input type="text" name="methods[jazzcash][account_name]" value="{{ old('methods.jazzcash.account_name', $jazzcash->account_name) }}" class="form-control-dark" placeholder="e.g. Kinetic Apex Official">
                    @error('methods.jazzcash.account_name')
                        <div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="form-label-dark">Phone Number (Pakistani Format) <span style="color:var(--color-primary-container);">*</span></label>
                    <input type="text" name="methods[jazzcash][phone]" value="{{ old('methods.jazzcash.phone', $jazzcash->phone) }}" class="form-control-dark" placeholder="e.g. 03007654321">
                    <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">Accepts 03001234567 or +923001234567</div>
                    @error('methods.jazzcash.phone')
                        <div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- QR Code Upload --}}
                <div>
                    <label class="form-label-dark">QR Code Image (Optional, max 2 MB)</label>
                    <div style="display:flex;align-items:center;gap:14px;margin-top:6px;">
                        <div id="jazzcash-qr-preview-box" style="width:72px;height:72px;border-radius:8px;background:var(--color-surface-container);border:1px dashed var(--color-surface-highest);display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;">
                            @if($jazzcash->qr_image_url)
                                <img src="{{ $jazzcash->qr_image_url }}" alt="QR" style="width:100%;height:100%;object-fit:cover;" id="jazzcash-qr-img">
                            @else
                                <span class="material-symbols-outlined" style="font-size:32px;color:var(--color-outline);" id="jazzcash-qr-placeholder">qr_code_2</span>
                            @endif
                        </div>
                        <div style="flex:1;">
                            <input type="file" name="methods[jazzcash][qr_image]" accept="image/*" class="form-control-dark" style="padding:6px;font-size:11px;" onchange="previewQr(this, 'jazzcash')">
                            @if($jazzcash->qr_image_url)
                            <label style="display:inline-flex;align-items:center;gap:6px;font-size:12px;color:var(--color-error);margin-top:6px;cursor:pointer;">
                                <input type="checkbox" name="methods[jazzcash][remove_qr]" value="1">
                                <span>Remove Current QR Code</span>
                            </label>
                            @endif
                        </div>
                    </div>
                    @error('methods.jazzcash.qr_image')
                        <div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="form-label-dark">Instructions for Member (Optional)</label>
                    <textarea name="methods[jazzcash][instructions]" class="form-control-dark form-textarea-dark" rows="3" placeholder="e.g. Transfer the fee to this JazzCash account and keep the screenshot.">{{ old('methods.jazzcash.instructions', $jazzcash->instructions) }}</textarea>
                </div>
            </div>
        </div>

        {{-- ── CARD 3: BANK TRANSFER ───────────────────────────── --}}
        <div class="card-dark" style="display:flex;flex-direction:column;border:1px solid var(--color-surface-highest);position:relative;">
            <div style="display:flex;align-items:center;justify-content:space-between;padding-bottom:14px;border-bottom:1px solid var(--color-surface-highest);margin-bottom:16px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:38px;height:38px;border-radius:8px;background:rgba(129,207,255,.12);border:1px solid rgba(129,207,255,.25);color:var(--color-secondary);display:flex;align-items:center;justify-content:center;">
                        <span class="material-symbols-outlined" style="font-size:20px;">account_balance</span>
                    </div>
                    <div>
                        <div style="font-family:var(--font-heading);font-size:16px;font-weight:700;text-transform:uppercase;color:var(--color-on-surface);">Bank Transfer</div>
                        <div style="font-size:11px;color:var(--color-outline);">Direct online / Raast wire</div>
                    </div>
                </div>

                {{-- Enabled Toggle --}}
                <label style="display:inline-flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="hidden" name="methods[bank_transfer][is_enabled]" value="0">
                    <input type="checkbox" name="methods[bank_transfer][is_enabled]" value="1" {{ old('methods.bank_transfer.is_enabled', $bankTransfer->is_enabled) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--color-primary-container);cursor:pointer;">
                    <span style="font-size:12px;font-family:var(--font-heading);letter-spacing:.05em;text-transform:uppercase;color:var(--color-on-surface);font-weight:600;">Enabled</span>
                </label>
            </div>

            <div style="display:flex;flex-direction:column;gap:14px;flex:1;">
                <div>
                    <label class="form-label-dark">Bank Name <span style="color:var(--color-primary-container);">*</span></label>
                    <input type="text" name="methods[bank_transfer][bank_name]" value="{{ old('methods.bank_transfer.bank_name', $bankTransfer->bank_name) }}" class="form-control-dark" placeholder="e.g. Meezan Bank Ltd">
                    @error('methods.bank_transfer.bank_name')
                        <div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="form-label-dark">Account Title <span style="color:var(--color-primary-container);">*</span></label>
                    <input type="text" name="methods[bank_transfer][account_name]" value="{{ old('methods.bank_transfer.account_name', $bankTransfer->account_name) }}" class="form-control-dark" placeholder="e.g. Kinetic Apex Pvt Ltd">
                    @error('methods.bank_transfer.account_name')
                        <div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="form-label-dark">Account Number <span style="color:var(--color-primary-container);">*</span></label>
                    <input type="text" name="methods[bank_transfer][account_number]" value="{{ old('methods.bank_transfer.account_number', $bankTransfer->account_number) }}" class="form-control-dark" placeholder="e.g. 010101010101">
                    @error('methods.bank_transfer.account_number')
                        <div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div>
                        <label class="form-label-dark">IBAN (Optional)</label>
                        <input type="text" name="methods[bank_transfer][iban]" value="{{ old('methods.bank_transfer.iban', $bankTransfer->iban) }}" class="form-control-dark" placeholder="e.g. PK72MEZN000...">
                    </div>
                    <div>
                        <label class="form-label-dark">Branch / Code (Optional)</label>
                        <input type="text" name="methods[bank_transfer][branch]" value="{{ old('methods.bank_transfer.branch', $bankTransfer->branch) }}" class="form-control-dark" placeholder="e.g. Blue Area (0123)">
                    </div>
                </div>

                <div>
                    <label class="form-label-dark">Instructions for Member (Optional)</label>
                    <textarea name="methods[bank_transfer][instructions]" class="form-control-dark form-textarea-dark" rows="3" placeholder="e.g. Transfer through mobile banking or Raast and enter your transaction ID.">{{ old('methods.bank_transfer.instructions', $bankTransfer->instructions) }}</textarea>
                </div>
            </div>
        </div>

    </div>

    {{-- ── FOOTER: EMAIL ALERTS & SAVE BUTTON ───────────────────── --}}
    <div class="card-dark" style="border:1px solid var(--color-surface-highest);padding:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:20px;">
        <div style="flex:1;min-width:280px;max-width:540px;">
            <label class="form-label-dark" style="display:flex;align-items:center;gap:6px;">
                <span class="material-symbols-outlined" style="font-size:16px;color:var(--color-primary-container);">mail</span>
                Email for Payment Alerts <span style="color:var(--color-primary-container);">*</span>
            </label>
            <input type="email" name="payment_alert_email" value="{{ old('payment_alert_email', $alertEmail) }}" class="form-control-dark" placeholder="e.g. admin@kineticapex.com" required>
            <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">
                Staff alert notifications will be queued to this email whenever a member submits a payment proof.
            </div>
            @error('payment_alert_email')
                <div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <button type="submit" class="btn-primary" style="padding:14px 32px;font-size:14px;">
                <span class="material-symbols-outlined" style="font-size:18px;">save</span>
                Save Changes
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
function previewQr(input, type) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const box = document.getElementById(type + '-qr-preview-box');
            box.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
