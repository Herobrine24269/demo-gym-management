<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminPaymentMethodRequest;
use App\Models\AuditLog;
use App\Models\GymSetting;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $easypaisa    = PaymentMethod::firstOrCreate(['type' => 'easypaisa'], ['is_enabled' => false]);
        $jazzcash     = PaymentMethod::firstOrCreate(['type' => 'jazzcash'], ['is_enabled' => false]);
        $bankTransfer = PaymentMethod::firstOrCreate(['type' => 'bank_transfer'], ['is_enabled' => false]);
        $alertEmail   = GymSetting::get('payment_alert_email', auth()->user()->email ?? 'admin@kineticapex.com');

        return view('admin.payment-methods.index', compact('easypaisa', 'jazzcash', 'bankTransfer', 'alertEmail'));
    }

    public function update(AdminPaymentMethodRequest $request)
    {
        // 1. Update Alert Email
        $oldEmail = GymSetting::get('payment_alert_email');
        $newEmail = $request->input('payment_alert_email');
        if ($oldEmail !== $newEmail) {
            GymSetting::set('payment_alert_email', $newEmail);
        }

        // 2. Update Payment Methods
        $types = ['easypaisa', 'jazzcash', 'bank_transfer'];

        foreach ($types as $type) {
            $methodData = $request->input("methods.{$type}", []);
            $method = PaymentMethod::firstOrCreate(['type' => $type]);
            $before = $method->only(['is_enabled', 'account_name', 'account_number', 'phone', 'bank_name', 'iban', 'branch', 'instructions', 'qr_image_path']);

            $isEnabled = $request->boolean("methods.{$type}.is_enabled");
            $qrPath    = $method->qr_image_path;

            // Check if removing QR code
            if ($request->boolean("methods.{$type}.remove_qr")) {
                if ($method->qr_image_path && Storage::disk('public')->exists($method->qr_image_path)) {
                    Storage::disk('public')->delete($method->qr_image_path);
                }
                $qrPath = null;
            }

            // Check if new QR code uploaded
            if ($request->hasFile("methods.{$type}.qr_image")) {
                if ($method->qr_image_path && Storage::disk('public')->exists($method->qr_image_path)) {
                    Storage::disk('public')->delete($method->qr_image_path);
                }
                $qrPath = $request->file("methods.{$type}.qr_image")->store('payment-methods', 'public');
            }

            $updateData = [
                'is_enabled'    => $isEnabled,
                'instructions'  => $methodData['instructions'] ?? null,
                'qr_image_path' => $qrPath,
                'updated_by'    => auth()->id(),
            ];

            if ($type === 'bank_transfer') {
                $updateData['bank_name']      = $methodData['bank_name'] ?? null;
                $updateData['account_name']   = $methodData['account_name'] ?? null;
                $updateData['account_number'] = $methodData['account_number'] ?? null;
                $updateData['iban']           = $methodData['iban'] ?? null;
                $updateData['branch']         = $methodData['branch'] ?? null;
                $updateData['phone']          = null;
            } else {
                $updateData['account_name']   = $methodData['account_name'] ?? null;
                $updateData['phone']          = $methodData['phone'] ?? null;
                $updateData['account_number'] = null;
                $updateData['bank_name']      = null;
                $updateData['iban']           = null;
                $updateData['branch']         = null;
            }

            $method->update($updateData);

            $after = $method->only(['is_enabled', 'account_name', 'account_number', 'phone', 'bank_name', 'iban', 'branch', 'instructions', 'qr_image_path']);

            // Log audit if changed
            if ($before !== $after) {
                AuditLog::record("updated_{$type}_payment_method", $method, $before, $after);
            }
        }

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Manual payment methods and alert settings updated successfully.');
    }
}
