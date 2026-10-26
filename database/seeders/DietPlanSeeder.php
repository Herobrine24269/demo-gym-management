<?php

namespace Database\Seeders;

use App\Models\DietPlan;
use App\Models\DietPlanMeal;
use App\Models\DietPlanFoodItem;
use App\Models\MemberDietAssignment;
use App\Models\Member;
use App\Models\Trainer;
use Illuminate\Database\Seeder;

class DietPlanSeeder extends Seeder
{
    public function run(): void
    {
        $trainer = Trainer::first();
        $members = Member::take(20)->get();

        $dietTitles = [
            ['Anabolic Mass Builder 3200', 'muscle_gain',   3200, 220, 320, 90, 'diet-plans/diet-mass-builder.jpg'],
            ['Shred Protocol 1800',        'weight_loss',   1800, 180, 120, 55, 'diet-plans/diet-shred-protocol.jpg'],
            ['Athletic Performance 2800',  'performance',   2800, 200, 300, 80, 'diet-plans/diet-athletic-performance.jpg'],
            ['Clean Bulk 2600',            'maintenance',   2600, 190, 280, 75, 'diet-plans/diet-clean-bulk.jpg'],
        ];

        $dietPlans = [];
        foreach ($dietTitles as [$dtitle, $goal, $cal, $pro, $carb, $fat, $cover]) {
            $dp = DietPlan::updateOrCreate(
                ['title' => $dtitle],
                [
                    'created_by'      => $trainer?->user_id ?? 1,
                    'goal'            => $goal,
                    'total_calories'  => $cal,
                    'total_protein_g' => $pro,
                    'total_carbs_g'   => $carb,
                    'total_fat_g'     => $fat,
                    'cover_image'     => $cover,
                    'is_public'       => true,
                ]
            );

            // Add meals if not present
            if ($dp->meals()->count() === 0) {
                $meals = [
                    ['Breakfast',    '07:00 AM', [['Eggs whole', '4 units', 280, 24, 2, 20], ['Oats', '80g', 300, 10, 54, 5]]],
                    ['Pre-Workout',  '12:00 PM', [['Whey Protein', '2 scoops', 240, 50, 8, 3], ['Banana', '1 large', 100, 1, 27, 0]]],
                    ['Post-Workout', '03:00 PM', [['Chicken Breast', '200g', 330, 62, 0, 7], ['Brown Rice', '150g', 195, 4, 43, 1]]],
                    ['Dinner',       '07:00 PM', [['Salmon fillet', '150g', 300, 35, 0, 17], ['Broccoli', '200g', 70, 6, 12, 1]]],
                ];

                foreach ($meals as $order => [$mname, $mtime, $foods]) {
                    $meal = DietPlanMeal::create([
                        'diet_plan_id'    => $dp->id,
                        'meal_name'       => $mname,
                        'time_suggestion' => $mtime,
                        'sort_order'      => $order,
                    ]);
                    foreach ($foods as [$fname, $qty, $fcal, $fpro, $fcarb, $ffat]) {
                        DietPlanFoodItem::create([
                            'meal_id'   => $meal->id,
                            'food_name' => $fname,
                            'quantity'  => $qty,
                            'calories'  => $fcal,
                            'protein_g' => $fpro,
                            'carbs_g'   => $fcarb,
                            'fat_g'     => $ffat,
                        ]);
                    }
                }
            }

            $dietPlans[] = $dp;
        }

        // Assign diet plans to members
        if ($members->isNotEmpty() && !empty($dietPlans)) {
            foreach ($members as $i => $member) {
                MemberDietAssignment::firstOrCreate(
                    [
                        'member_id'    => $member->id,
                        'diet_plan_id' => $dietPlans[$i % count($dietPlans)]->id,
                    ],
                    [
                        'assigned_by' => $trainer?->user_id ?? 1,
                        'assigned_at' => now()->subDays(10),
                    ]
                );
            }
        }
    }
}
