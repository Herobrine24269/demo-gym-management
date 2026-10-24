<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Trainer;
use App\Models\User;
use App\Models\Payment;
use App\Models\PersonalTrainingSession;
use Illuminate\Database\Seeder;

class PersonalTrainingSessionSeeder extends Seeder
{
    public function run(): void
    {
        $trainers = Trainer::all();
        $members  = Member::take(8)->get();
        $receptionist = User::where('role', 'receptionist')->first();

        if ($trainers->isEmpty() || $members->isEmpty()) {
            return;
        }

        foreach ($members as $i => $member) {
            $trainer = $trainers[$i % $trainers->count()];
            $total = 12;
            $done  = 4;
            $amount = $total * 1500;

            if (!PersonalTrainingSession::where('member_id', $member->id)->exists()) {
                $pay = Payment::create([
                    'member_id'      => $member->id,
                    'type'           => 'pt_session',
                    'amount'         => $amount,
                    'payment_method' => 'cash',
                    'status'         => 'paid',
                    'received_by'    => $receptionist?->id ?? 1,
                    'paid_at'        => now()->subDays(10),
                ]);

                PersonalTrainingSession::create([
                    'member_id'      => $member->id,
                    'trainer_id'     => $trainer->id,
                    'package_name'   => "{$total}-Session PT Pack",
                    'total_sessions' => $total,
                    'sessions_done'  => $done,
                    'price'          => $amount,
                    'payment_id'     => $pay->id,
                    'status'         => 'active',
                    'started_at'     => now()->subDays(10),
                ]);
            }
        }
    }
}
