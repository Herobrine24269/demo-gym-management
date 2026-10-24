<?php

namespace Database\Seeders;

use App\Models\MembershipPlan;
use Illuminate\Database\Seeder;

class MembershipPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            ['name' => 'Monthly',    'duration_days' => 30,  'price' => 4500,  'features' => ['Full gym access', 'Locker room', 'Free towel']],
            ['name' => 'Quarterly',  'duration_days' => 90,  'price' => 12000, 'features' => ['Full gym access', 'Locker room', 'Free towel', 'Diet consultation']],
            ['name' => 'Semi-Annual','duration_days' => 180, 'price' => 21000, 'features' => ['Full gym access', 'Locker', 'Towel', 'Diet plan', '1 PT session']],
            ['name' => 'Annual',     'duration_days' => 365, 'price' => 38000, 'features' => ['Full gym access', 'Locker', 'Towel', 'Diet plan', '3 PT sessions', 'Priority booking']],
        ];

        foreach ($plans as $p) {
            MembershipPlan::firstOrCreate(
                ['name' => $p['name']],
                [
                    'duration_days' => $p['duration_days'],
                    'price'         => $p['price'],
                    'features'      => $p['features'],
                    'is_active'     => true,
                ]
            );
        }
    }
}
