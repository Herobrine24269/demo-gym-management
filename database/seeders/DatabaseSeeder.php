<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GymSettingSeeder::class,
            UserSeeder::class,
            MembershipPlanSeeder::class,
            MemberSeeder::class,
            WorkoutPlanSeeder::class,
            AddonSeeder::class,
            PersonalTrainingSessionSeeder::class,
            ExpenseSeeder::class,
            PostSeeder::class,
            DietPlanSeeder::class,
        ]);

        $this->command->info('✅ All seeders executed successfully: Settings, Users, Plans, Members, Workouts, Addons, PT Sessions, Expenses, Posts, Diet plans, and Call logs connected.');
    }
}
