<?php

namespace Database\Seeders;

use App\Models\GymSetting;
use Illuminate\Database\Seeder;

class GymSettingSeeder extends Seeder
{
    public function run(): void
    {
        GymSetting::set('gym_name', 'Kinetic Apex');
        GymSetting::set('gym_phone', '+92-300-0000000');
        GymSetting::set('gym_email', 'info@kineticapex.com');
        GymSetting::set('gym_address', 'Sector 4, Athletic District, Karachi');
        GymSetting::set('inactive_threshold_days', '10');
        GymSetting::set('renewal_alert_days', '7');
        GymSetting::set('receptionist_can_view_payment_requests', '0');
    }
}
