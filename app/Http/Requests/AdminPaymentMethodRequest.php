<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminPaymentMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $pakPhoneRegex = 'regex:/^((03[0-9]{9})|(\+923[0-9]{9})|(923[0-9]{9}))$/';

        return [
            'payment_alert_email' => 'required|email|max:150',

            // Easypaisa
            'methods.easypaisa.is_enabled'   => 'nullable|boolean',
            'methods.easypaisa.account_name' => 'required_if:methods.easypaisa.is_enabled,1|nullable|string|max:150',
            'methods.easypaisa.phone'        => ['required_if:methods.easypaisa.is_enabled,1', 'nullable', $pakPhoneRegex],
            'methods.easypaisa.qr_image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'methods.easypaisa.remove_qr'    => 'nullable|boolean',
            'methods.easypaisa.instructions' => 'nullable|string|max:1000',

            // JazzCash
            'methods.jazzcash.is_enabled'   => 'nullable|boolean',
            'methods.jazzcash.account_name' => 'required_if:methods.jazzcash.is_enabled,1|nullable|string|max:150',
            'methods.jazzcash.phone'        => ['required_if:methods.jazzcash.is_enabled,1', 'nullable', $pakPhoneRegex],
            'methods.jazzcash.qr_image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'methods.jazzcash.remove_qr'    => 'nullable|boolean',
            'methods.jazzcash.instructions' => 'nullable|string|max:1000',

            // Bank Transfer
            'methods.bank_transfer.is_enabled'     => 'nullable|boolean',
            'methods.bank_transfer.bank_name'      => 'required_if:methods.bank_transfer.is_enabled,1|nullable|string|max:150',
            'methods.bank_transfer.account_name'   => 'required_if:methods.bank_transfer.is_enabled,1|nullable|string|max:150',
            'methods.bank_transfer.account_number' => 'required_if:methods.bank_transfer.is_enabled,1|nullable|string|max:100',
            'methods.bank_transfer.iban'           => 'nullable|string|max:100',
            'methods.bank_transfer.branch'         => 'nullable|string|max:150',
            'methods.bank_transfer.instructions'   => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'payment_alert_email.required' => 'Please provide an email address for payment alerts.',
            'payment_alert_email.email'    => 'Please enter a valid email address for alerts.',

            'methods.easypaisa.account_name.required_if' => 'Account holder name is required when Easypaisa is enabled.',
            'methods.easypaisa.phone.required_if'        => 'Phone number is required when Easypaisa is enabled.',
            'methods.easypaisa.phone.regex'              => 'Please enter a valid Pakistani phone number for Easypaisa (e.g. 03001234567).',
            'methods.easypaisa.qr_image.max'             => 'Easypaisa QR code image must not exceed 2 MB.',

            'methods.jazzcash.account_name.required_if' => 'Account holder name is required when JazzCash is enabled.',
            'methods.jazzcash.phone.required_if'        => 'Phone number is required when JazzCash is enabled.',
            'methods.jazzcash.phone.regex'              => 'Please enter a valid Pakistani phone number for JazzCash (e.g. 03007654321).',
            'methods.jazzcash.qr_image.max'             => 'JazzCash QR code image must not exceed 2 MB.',

            'methods.bank_transfer.bank_name.required_if'      => 'Bank name is required when Bank Transfer is enabled.',
            'methods.bank_transfer.account_name.required_if'   => 'Account title is required when Bank Transfer is enabled.',
            'methods.bank_transfer.account_number.required_if' => 'Account number is required when Bank Transfer is enabled.',
        ];
    }
}
