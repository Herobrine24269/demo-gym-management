<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Member;
use App\Models\Trainer;
use App\Models\MembershipPlan;
use App\Models\Membership;
use App\Models\Attendance;
use App\Models\Streak;
use App\Models\TrainerAssignment;
use App\Models\WorkoutPlan;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\InactiveCase;
use App\Models\Addon;
use App\Models\AddonOrder;
use App\Models\PersonalTrainingSession;
use App\Models\PostCategory;
use App\Models\Post;
use App\Models\DietPlan;
use App\Models\DietPlanMeal;
use App\Models\DietPlanFoodItem;
use App\Models\MemberDietAssignment;
use App\Models\GymSetting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /* ============================================================
           DEMO DATA — clearly marked
           ============================================================ */

        /* ── 1. Gym settings ───────────────────────────────────── */
        GymSetting::set('gym_name', 'Kinetic Apex');
        GymSetting::set('gym_phone', '+92-300-0000000');
        GymSetting::set('gym_email', 'info@kineticapex.com');
        GymSetting::set('gym_address', 'Sector 4, Athletic District, Karachi');
        GymSetting::set('inactive_threshold_days', '10');
        GymSetting::set('renewal_alert_days', '7');

        /* ── 2. Staff users ────────────────────────────────────── */
        // DEMO: 1 admin
        $admin = User::create([
            'name'     => 'Marcus Vance',
            'email'    => 'admin@kineticapex.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'phone'    => '+92-300-1111111',
        ]);

        // DEMO: 1 receptionist
        $receptionist = User::create([
            'name'     => 'Sara Ahmed',
            'email'    => 'reception@kineticapex.com',
            'password' => Hash::make('password'),
            'role'     => 'receptionist',
            'phone'    => '+92-300-2222222',
        ]);

        // DEMO: 3 trainers
        $trainerUsers = [];
        $trainerData = [
            ['Coach Raza Khan', 'trainer1@kineticapex.com', 'Strength & Powerlifting'],
            ['Coach Zara Malik', 'trainer2@kineticapex.com', 'Cardio & Weight Loss'],
            ['Coach Ali Hassan', 'trainer3@kineticapex.com', 'Bodybuilding & Hypertrophy'],
        ];
        foreach ($trainerData as [$name, $email, $spec]) {
            $u = User::create([
                'name'     => $name,
                'email'    => $email,
                'password' => Hash::make('password'),
                'role'     => 'trainer',
                'phone'    => '+92-300-' . rand(3000000, 9999999),
            ]);
            $trainerUsers[] = Trainer::create([
                'user_id'        => $u->id,
                'specialization' => $spec,
                'bio'            => "Expert in {$spec} with 8+ years of coaching experience.",
                'salary'         => rand(40000, 80000),
                'hired_at'       => now()->subMonths(rand(6, 24)),
                'is_active'      => true,
            ]);
        }

        /* ── 3. Membership plans ───────────────────────────────── */
        $plans = [
            MembershipPlan::create(['name'=>'Monthly',   'duration_days'=>30,  'price'=>4500,  'features'=>['Full gym access','Locker room','Free towel'],'is_active'=>true]),
            MembershipPlan::create(['name'=>'Quarterly', 'duration_days'=>90,  'price'=>12000, 'features'=>['Full gym access','Locker room','Free towel','Diet consultation'],'is_active'=>true]),
            MembershipPlan::create(['name'=>'Semi-Annual','duration_days'=>180,'price'=>21000, 'features'=>['Full gym access','Locker','Towel','Diet plan','1 PT session'],'is_active'=>true]),
            MembershipPlan::create(['name'=>'Annual',    'duration_days'=>365, 'price'=>38000, 'features'=>['Full gym access','Locker','Towel','Diet plan','3 PT sessions','Priority booking'],'is_active'=>true]),
        ];

        /* ── 4. Create 30 members ──────────────────────────────── */
        $memberNames = [
            'Ahmed Raza','Bilal Khan','Hina Shah','Fatima Ali','Usman Siddiqui',
            'Ayesha Noor','Omer Farooq','Sana Tariq','Waqar Hussain','Nadia Iqbal',
            'Hassan Mirza','Maria Yousuf','Asad Javed','Rabia Malik','Imran Sheikh',
            'Kiran Butt','Zubair Ahmad','Sofia Baig','Kashif Anwar','Amna Rizvi',
            'Daniyal Qureshi','Layla Hamid','Tahir Mehmood','Sadia Akhtar','Faisal Nadeem',
            'Amara Zafar','Saad Chaudhry','Noor Fatima','Abdul Rehman','Zainab Qamar',
        ];

        $members = [];
        foreach ($memberNames as $i => $name) {
            $u = User::create([
                'name'     => $name,
                'email'    => strtolower(str_replace(' ', '.', $name)) . '@demo.com',
                'password' => Hash::make('password'),
                'role'     => 'member',
                'phone'    => '+92-300-' . (3000000 + $i * 111111),
            ]);
            $joinedAt = now()->subMonths(rand(1, 18));
            $m = Member::create([
                'user_id'     => $u->id,
                'member_code' => 'APX-' . str_pad(1001 + $i, 4, '0', STR_PAD_LEFT),
                'gender'      => $i % 3 === 0 ? 'female' : 'male',
                'dob'         => now()->subYears(rand(20, 40))->subDays(rand(0, 365)),
                'joined_at'   => $joinedAt,
            ]);
            $members[] = $m;

            // Create membership
            $plan      = $plans[array_rand($plans)];
            $startDate = $joinedAt->copy();
            $endDate   = $startDate->copy()->addDays($plan->duration_days);
            // Some expire within 7 days (5 members)
            if ($i < 5) {
                $endDate = now()->addDays(rand(1, 6));
                $startDate = $endDate->copy()->subDays($plan->duration_days);
            }
            $ms = Membership::create([
                'member_id'      => $m->id,
                'plan_id'        => $plan->id,
                'start_date'     => $startDate,
                'end_date'       => $endDate,
                'status'         => $endDate->gte(now()) ? 'active' : 'expired',
                'amount_paid'    => $plan->price,
                'payment_method' => 'cash',
                'created_by'     => $admin->id,
            ]);

            Payment::create([
                'member_id'      => $m->id,
                'type'           => 'membership',
                'amount'         => $plan->price,
                'payment_method' => 'cash',
                'reference_id'   => $ms->id,
                'reference_type' => 'Membership',
                'status'         => 'paid',
                'received_by'    => $receptionist->id,
                'paid_at'        => $startDate,
            ]);
        }

        /* ── 5. Attendance — regular members ───────────────────── */
        $regularMembers = array_slice($members, 0, 24); // first 24 attend regularly
        foreach ($regularMembers as $member) {
            for ($d = 60; $d >= 1; $d--) {
                if (rand(0, 4) > 0) { // 80% attendance
                    $date = now()->subDays($d);
                    if ($date->isWeekday()) {
                        Attendance::create([
                            'member_id'     => $member->id,
                            'date'          => $date->toDateString(),
                            'checked_in_at' => sprintf('%02d:%02d:00', rand(6, 20), rand(0, 59)),
                            'marked_by'     => $receptionist->id,
                        ]);
                    }
                }
            }
        }

        /* ── 6. Streaks ─────────────────────────────────────────── */
        foreach ($regularMembers as $member) {
            $last   = $member->attendance()->latest('date')->first();
            $streak = Streak::create([
                'member_id'      => $member->id,
                'current_streak' => rand(1, 21),
                'longest_streak' => rand(10, 45),
                'last_seen_date' => $last?->date ?? now()->subDays(rand(1, 3)),
            ]);
        }

        /* ── 7. Inactive members (6 absent 10-20 days) ─────────── */
        $inactiveMembers = array_slice($members, 24, 6);
        foreach ($inactiveMembers as $idx => $member) {
            $daysAbsent  = rand(10, 20);
            $lastDate    = now()->subDays($daysAbsent);
            $priority    = InactiveCase::priorityFor($daysAbsent);

            // Only add a few old attendance records
            for ($d = 60; $d > $daysAbsent; $d -= rand(2, 5)) {
                $date = now()->subDays($d);
                if (!Attendance::where('member_id', $member->id)->whereDate('date', $date)->exists()) {
                    Attendance::create([
                        'member_id'     => $member->id,
                        'date'          => $date->toDateString(),
                        'checked_in_at' => sprintf('%02d:%02d:00', rand(7, 19), rand(0, 59)),
                        'marked_by'     => $receptionist->id,
                    ]);
                }
            }

            InactiveCase::create([
                'member_id'            => $member->id,
                'days_absent'          => $daysAbsent,
                'last_attendance_date' => $lastDate,
                'priority'             => $priority,
                'status'               => 'open',
            ]);

            Streak::create([
                'member_id'      => $member->id,
                'current_streak' => 0,
                'longest_streak' => rand(5, 15),
                'last_seen_date' => $lastDate,
            ]);
        }

        /* ── 8. Trainer assignments ────────────────────────────── */
        foreach ($members as $i => $member) {
            $trainer = $trainerUsers[$i % 3];
            TrainerAssignment::create([
                'trainer_id'  => $trainer->id,
                'member_id'   => $member->id,
                'assigned_at' => $member->joined_at,
            ]);
        }

        /* ── 9. Workout plans ──────────────────────────────────── */
        foreach (array_slice($members, 0, 15) as $i => $member) {
            $trainer = $trainerUsers[$i % 3];
            WorkoutPlan::create([
                'trainer_id'  => $trainer->id,
                'member_id'   => $member->id,
                'title'       => ['Push-Pull-Legs Program', 'Strength Foundation', 'Cut & Shred Protocol', 'Mass Builder 12-Week', 'Functional Athletic Block'][$i % 5],
                'description' => 'Structured progressive overload plan targeting compound lifts.',
                'weeks'       => rand(4, 12),
                'status'      => 'active',
            ]);
        }

        /* ── 10. Add-ons / Shop ─────────────────────────────────── */
        $addons = [
            Addon::create(['name'=>'Apex Whey Vanilla',  'type'=>'supplement','price'=>3500,'stock_qty'=>50,'is_active'=>true]),
            Addon::create(['name'=>'Protein Shaker Bottle','type'=>'equipment','price'=>800,'stock_qty'=>30,'is_active'=>true]),
            Addon::create(['name'=>'Locker Monthly',     'type'=>'locker',    'price'=>1500,'stock_qty'=>20,'is_active'=>true]),
            Addon::create(['name'=>'Energy Drink',       'type'=>'drink',     'price'=>150, 'stock_qty'=>100,'is_active'=>true]),
            Addon::create(['name'=>'Creatine Monohydrate','type'=>'supplement','price'=>2800,'stock_qty'=>40,'is_active'=>true]),
        ];

        /* ── 11. Personal training sessions ─────────────────────── */
        foreach (array_slice($members, 0, 8) as $i => $member) {
            $trainer = $trainerUsers[$i % 3];
            $total   = rand(10, 20);
            $done    = rand(1, $total - 1);
            $pay     = Payment::create([
                'member_id'      => $member->id,
                'type'           => 'pt_session',
                'amount'         => $total * 1500,
                'payment_method' => 'cash',
                'status'         => 'paid',
                'received_by'    => $receptionist->id,
                'paid_at'        => now()->subDays(rand(1, 30)),
            ]);
            PersonalTrainingSession::create([
                'member_id'      => $member->id,
                'trainer_id'     => $trainer->id,
                'package_name'   => "{$total}-Session PT Pack",
                'total_sessions' => $total,
                'sessions_done'  => $done,
                'price'          => $total * 1500,
                'payment_id'     => $pay->id,
                'status'         => 'active',
                'started_at'     => now()->subDays(rand(5, 20)),
            ]);
        }

        /* ── 12. Expenses ───────────────────────────────────────── */
        $expenseCategories = ['Equipment Maintenance', 'Utilities', 'Staff Salary', 'Cleaning Supplies', 'Marketing'];
        for ($i = 0; $i < 12; $i++) {
            Expense::create([
                'category'    => $expenseCategories[array_rand($expenseCategories)],
                'amount'      => rand(2000, 50000),
                'description' => 'Monthly operating expense — DEMO DATA',
                'incurred_at' => now()->subDays(rand(0, 30)),
                'recorded_by' => $admin->id,
            ]);
        }

        /* ── 13. Post categories & posts ─────────────────────────── */
        $cats = [
            PostCategory::create(['name'=>'Workout Guides',    'slug'=>'workout-guides']),
            PostCategory::create(['name'=>'Diet Plans',        'slug'=>'diet-plans']),
            PostCategory::create(['name'=>'Fitness Tips',      'slug'=>'fitness-tips']),
            PostCategory::create(['name'=>'Announcements',     'slug'=>'announcements']),
            PostCategory::create(['name'=>'Muscle Gain',       'slug'=>'muscle-gain']),
        ];

        $postData = [
            [$cats[0], 'Progressive Overload Mastery: The 5x5 Protocol Breakdown', 'Learn how systematically micro-loading compound lifts accelerates hypertrophy while preventing CNS fatigue.'],
            [$cats[1], 'High Protein Cutting Diet: 2,200 Calorie Plan',            'Structured macronutrient blueprint designed to preserve lean mass while targeting aggressive fat loss.'],
            [$cats[2], 'Top 7 Recovery Protocols Elite Athletes Swear By',         'Sleep optimization, active recovery, contrast therapy, and strategic deload weeks explained.'],
            [$cats[4], 'Creatine Loading Phase: Science vs Myths',                 'Evidence-based breakdown of creatine monohydrate supplementation timing, dosing and cycling.'],
            [$cats[0], 'Romanian Deadlift Form Guide for Maximum Hamstring Growth', 'Step-by-step breakdown of RDL mechanics with common errors and cue corrections.'],
        ];

        foreach ($postData as $i => [$cat, $title, $excerpt]) {
            Post::create([
                'category_id'    => $cat->id,
                'author_id'      => $admin->id,
                'title'          => $title,
                'slug'           => Str::slug($title),
                'excerpt'        => $excerpt,
                'body'           => "<p>{$excerpt}</p><p>This is sample post content for demo purposes. Full article content would be written by the trainer.</p>",
                'status'         => 'published',
                'published_at'   => now()->subDays($i * 7),
                'read_minutes'   => rand(4, 10),
            ]);
        }

        /* ── 14. Diet plans ─────────────────────────────────────── */
        $dietGoals = ['muscle_gain', 'weight_loss', 'maintenance', 'performance'];
        $dietTitles = [
            ['Anabolic Mass Builder 3200',  'muscle_gain',   3200, 220, 320, 90],
            ['Shred Protocol 1800',         'weight_loss',   1800, 180, 120, 55],
            ['Athletic Performance 2800',   'performance',   2800, 200, 300, 80],
            ['Clean Bulk 2600',             'maintenance',   2600, 190, 280, 75],
        ];

        $dietPlans = [];
        foreach ($dietTitles as [$dtitle, $goal, $cal, $pro, $carb, $fat]) {
            $dp = DietPlan::create([
                'created_by'      => $trainerUsers[0]->user_id,
                'title'           => $dtitle,
                'goal'            => $goal,
                'total_calories'  => $cal,
                'total_protein_g' => $pro,
                'total_carbs_g'   => $carb,
                'total_fat_g'     => $fat,
                'is_public'       => true,
            ]);

            // Add meals
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
            $dietPlans[] = $dp;
        }

        /* ── 15. Assign diet plans to members ───────────────────── */
        foreach (array_slice($members, 0, 20) as $i => $member) {
            MemberDietAssignment::create([
                'member_id'   => $member->id,
                'diet_plan_id'=> $dietPlans[$i % count($dietPlans)]->id,
                'assigned_by' => $trainerUsers[$i % 3]->user_id,
                'assigned_at' => now()->subDays(rand(5, 30)),
            ]);
        }

        $this->command->info('✅ DEMO seed complete: 1 admin, 1 receptionist, 3 trainers, 30 members, 4 plans, 6 inactive, 5 expiring-soon, sample posts, diet plans.');
    }
}
