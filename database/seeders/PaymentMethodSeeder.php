<?php

namespace Database\Seeders;

use App\Models\GymSetting;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        PaymentMethod::updateOrCreate(
            ['type' => 'easypaisa'],
            [
                'is_enabled'     => false,
                'account_name'   => 'Kinetic Apex Official (DEMO)',
                'phone'          => '03001234567',
                'account_number' => null,
                'bank_name'      => null,
                'iban'           => null,
                'branch'         => null,
                'instructions'   => 'Send the exact membership fee to this Easypaisa account, take a screenshot of the confirmation receipt, and submit it below.',
            ]
        );

        PaymentMethod::updateOrCreate(
            ['type' => 'jazzcash'],
            [
                'is_enabled'     => false,
                'account_name'   => 'Kinetic Apex Official (DEMO)',
                'phone'          => '03007654321',
                'account_number' => null,
                'bank_name'      => null,
                'iban'           => null,
                'branch'         => null,
                'instructions'   => 'Transfer the exact amount to this JazzCash number and ensure your screenshot displays the TID clearly.',
            ]
        );

        PaymentMethod::updateOrCreate(
            ['type' => 'bank_transfer'],
            [
                'is_enabled'     => false,
                'account_name'   => 'Kinetic Apex Gym Pvt Ltd (DEMO)',
                'account_number' => '010101010101',
                'phone'          => null,
                'bank_name'      => 'Meezan Bank Ltd',
                'iban'           => 'PK72MEZN0001010101010101',
                'branch'         => 'Blue Area Branch (0123)',
                'instructions'   => 'Transfer the fee via online banking or Raast. Save the transaction reference number and upload the e-receipt.',
            ]
        );

        // Alert email for payments
        if (!GymSetting::get('payment_alert_email')) {
            GymSetting::set('payment_alert_email', 'admin@kineticapex.com');
        }
    }
}
