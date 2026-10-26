<?php

namespace App\Http\Requests;

use App\Models\PaymentMethod;
use App\Models\PaymentSubmission;
use Illuminate\Foundation\Http\FormRequest;

class SubmitPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->member !== null;
    }

    public function rules(): array
    {
        return [
            'membership_plan_id'    => 'required|exists:membership_plans,id',
            'payment_method_id'     => [
                'required',
                'exists:payment_methods,id',
                function ($attribute, $value, $fail) {
                    $method = PaymentMethod::find($value);
                    if (!$method || !$method->is_enabled) {
                        $fail('The selected payment method is currently disabled or unavailable.');
                    }
                },
            ],
            'amount'                => 'required|numeric|min:1',
            'transaction_reference' => [
                'required',
                'string',
                'max:100',
                function ($attribute, $value, $fail) {
                    $methodId = $this->input('payment_method_id');
                    $exists = PaymentSubmission::where('payment_method_id', $methodId)
                        ->where('transaction_reference', trim($value))
                        ->exists();

                    if ($exists) {
                        $fail('This transaction ID has already been submitted for this payment method.');
                    }
                },
            ],
            'paid_at'               => 'required|date',
            'screenshot'            => 'required|file|mimes:jpeg,png,jpg,webp,pdf|max:3072',
            'note'                  => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'membership_plan_id.required'    => 'Please select a membership plan.',
            'payment_method_id.required'     => 'Please choose a payment method.',
            'amount.required'                => 'Please enter the payment amount.',
            'amount.min'                     => 'Amount must be at least 1 PKR.',
            'transaction_reference.required' => 'Transaction ID or reference number is required.',
            'paid_at.required'               => 'Payment date and time is required.',
            'screenshot.required'            => 'Please upload a screenshot or receipt of your payment.',
            'screenshot.file'                => 'The payment proof must be a valid file.',
            'screenshot.mimes'               => 'Payment proof must be an image (JPG, PNG, WebP) or PDF document.',
            'screenshot.max'                 => 'Payment proof file size must not exceed 3 MB.',
        ];
    }
}
