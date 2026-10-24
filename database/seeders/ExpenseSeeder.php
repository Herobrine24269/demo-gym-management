<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        // Only seed if no expenses exist yet
        if (Expense::count() === 0) {
            $categories = ['Equipment Maintenance', 'Utilities', 'Staff Salary', 'Cleaning Supplies', 'Marketing'];
            $amounts    = [15000, 32000, 80000, 8500, 20000, 12000, 45000];

            foreach ($categories as $i => $cat) {
                Expense::create([
                    'category'    => $cat,
                    'amount'      => $amounts[$i % count($amounts)],
                    'description' => 'Monthly operating expense — DEMO DATA',
                    'incurred_at' => now()->subDays($i * 4),
                    'recorded_by' => $admin?->id ?? 1,
                ]);
            }
        }
    }
}
