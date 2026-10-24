<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Trainer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin
        User::firstOrCreate(
            ['email' => 'admin@kineticapex.com'],
            [
                'name'     => 'Marcus Vance',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'phone'    => '+92-300-1111111',
            ]
        );

        // 2. Receptionist
        User::firstOrCreate(
            ['email' => 'reception@kineticapex.com'],
            [
                'name'     => 'Sara Ahmed',
                'password' => Hash::make('password'),
                'role'     => 'receptionist',
                'phone'    => '+92-300-2222222',
            ]
        );

        // 3. Trainers
        $trainers = [
            ['Coach Raza Khan', 'trainer1@kineticapex.com', 'Strength & Powerlifting', '+92-300-3333331'],
            ['Coach Zara Malik', 'trainer2@kineticapex.com', 'Cardio & Weight Loss', '+92-300-3333332'],
            ['Coach Ali Hassan', 'trainer3@kineticapex.com', 'Bodybuilding & Hypertrophy', '+92-300-3333333'],
        ];

        foreach ($trainers as [$name, $email, $spec, $phone]) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'     => $name,
                    'password' => Hash::make('password'),
                    'role'     => 'trainer',
                    'phone'    => $phone,
                ]
            );

            Trainer::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'specialization' => $spec,
                    'bio'            => "Expert in {$spec} with 8+ years of coaching experience.",
                    'salary'         => 60000,
                    'hired_at'       => now()->subMonths(12),
                    'is_active'      => true,
                ]
            );
        }
    }
}
