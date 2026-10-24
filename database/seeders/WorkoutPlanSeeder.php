<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Trainer;
use App\Models\WorkoutPlan;
use Illuminate\Database\Seeder;

class WorkoutPlanSeeder extends Seeder
{
    public function run(): void
    {
        $trainers = Trainer::all();
        $members  = Member::take(15)->get();

        if ($trainers->isEmpty() || $members->isEmpty()) {
            return;
        }

        $titles = [
            'Push-Pull-Legs Program',
            'Strength Foundation',
            'Cut & Shred Protocol',
            'Mass Builder 12-Week',
            'Functional Athletic Block',
        ];

        foreach ($members as $i => $member) {
            $trainer = $trainers[$i % $trainers->count()];
            WorkoutPlan::firstOrCreate(
                [
                    'trainer_id' => $trainer->id,
                    'member_id'  => $member->id,
                    'title'      => $titles[$i % count($titles)],
                ],
                [
                    'description' => 'Structured progressive overload plan targeting compound lifts.',
                    'weeks'       => 8,
                    'status'      => 'active',
                ]
            );
        }
    }
}
