<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Member;
use App\Models\MembershipPlan;
use App\Models\Membership;
use App\Models\Payment;
use App\Models\Attendance;
use App\Models\Streak;
use App\Models\InactiveCase;
use App\Models\Trainer;
use App\Models\TrainerAssignment;
use App\Models\FollowUp;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $receptionist = User::where('role', 'receptionist')->first();
        $trainers = Trainer::with('user')->get();
        $plans = MembershipPlan::all();

        if ($plans->isEmpty() || $trainers->isEmpty()) {
            return;
        }

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
            $email = strtolower(str_replace(' ', '.', $name)) . '@demo.com';
            $code  = 'APX-' . str_pad(1001 + $i, 4, '0', STR_PAD_LEFT);

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'     => $name,
                    'password' => Hash::make('password'),
                    'role'     => 'member',
                    'phone'    => '+92-300-' . (3000000 + $i * 111111),
                ]
            );

            $joinedAt = now()->subMonths(($i % 12) + 1);
            $member = Member::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'member_code' => $code,
                    'gender'      => $i % 3 === 0 ? 'female' : 'male',
                    'dob'         => now()->subYears(22 + ($i % 15))->subDays($i * 10),
                    'joined_at'   => $joinedAt,
                ]
            );
            $members[] = $member;

            // Membership
            if (!Membership::where('member_id', $member->id)->exists()) {
                $plan      = $plans[$i % $plans->count()];
                $startDate = $joinedAt->copy();
                $endDate   = $startDate->copy()->addDays($plan->duration_days);

                // Make first 5 members expire soon
                if ($i < 5) {
                    $endDate   = now()->addDays($i + 2);
                    $startDate = $endDate->copy()->subDays($plan->duration_days);
                }

                $ms = Membership::create([
                    'member_id'      => $member->id,
                    'plan_id'        => $plan->id,
                    'start_date'     => $startDate,
                    'end_date'       => $endDate,
                    'status'         => $endDate->gte(now()) ? 'active' : 'expired',
                    'amount_paid'    => $plan->price,
                    'payment_method' => 'cash',
                    'created_by'     => $admin?->id ?? 1,
                ]);

                Payment::firstOrCreate(
                    [
                        'member_id'      => $member->id,
                        'reference_id'   => $ms->id,
                        'reference_type' => 'Membership',
                    ],
                    [
                        'type'           => 'membership',
                        'amount'         => $plan->price,
                        'payment_method' => 'cash',
                        'status'         => 'paid',
                        'received_by'    => $receptionist?->id ?? 1,
                        'paid_at'        => $startDate,
                    ]
                );
            }
        }

        // Attendance & Streaks for regular members (first 24)
        $regularMembers = array_slice($members, 0, 24);
        foreach ($regularMembers as $member) {
            if (!Attendance::where('member_id', $member->id)->exists()) {
                for ($d = 45; $d >= 1; $d--) {
                    if (($d % 5) !== 0) { // regular attendance
                        $date = now()->subDays($d);
                        if ($date->isWeekday()) {
                            Attendance::create([
                                'member_id'     => $member->id,
                                'date'          => $date->toDateString(),
                                'checked_in_at' => sprintf('%02d:%02d:00', rand(7, 20), rand(0, 59)),
                                'marked_by'     => $receptionist?->id ?? 1,
                            ]);
                        }
                    }
                }
            }

            $lastAtt = $member->attendance()->latest('date')->first();
            Streak::firstOrCreate(
                ['member_id' => $member->id],
                [
                    'current_streak' => rand(2, 18),
                    'longest_streak' => rand(15, 40),
                    'last_seen_date' => $lastAtt?->date ?? now()->subDays(1),
                ]
            );
        }

        // Inactive members (last 6 members)
        $inactiveMembers = array_slice($members, 24, 6);
        foreach ($inactiveMembers as $idx => $member) {
            $daysAbsent = 12 + $idx * 2;
            $lastDate   = now()->subDays($daysAbsent);
            $priority   = InactiveCase::priorityFor($daysAbsent);

            if (!Attendance::where('member_id', $member->id)->exists()) {
                for ($d = 50; $d > $daysAbsent; $d -= 4) {
                    Attendance::create([
                        'member_id'     => $member->id,
                        'date'          => now()->subDays($d)->toDateString(),
                        'checked_in_at' => '08:00:00',
                        'marked_by'     => $receptionist?->id ?? 1,
                    ]);
                }
            }

            InactiveCase::firstOrCreate(
                ['member_id' => $member->id],
                [
                    'days_absent'          => $daysAbsent,
                    'last_attendance_date' => $lastDate,
                    'priority'             => $priority,
                    'status'               => 'open',
                ]
            );

            Streak::firstOrCreate(
                ['member_id' => $member->id],
                [
                    'current_streak' => 0,
                    'longest_streak' => rand(5, 12),
                    'last_seen_date' => $lastDate,
                ]
            );
        }

        // Trainer assignments
        foreach ($members as $i => $member) {
            $trainer = $trainers[$i % $trainers->count()];
            TrainerAssignment::firstOrCreate(
                [
                    'trainer_id' => $trainer->id,
                    'member_id'  => $member->id,
                ],
                [
                    'assigned_at' => $member->joined_at,
                ]
            );
        }

        // Sample follow-up / call logs
        foreach (array_slice($members, 0, 8) as $idx => $member) {
            if (!FollowUp::where('member_id', $member->id)->exists()) {
                FollowUp::create([
                    'member_id'      => $member->id,
                    'called_by'      => $idx % 2 === 0 ? ($admin?->id ?? 1) : $trainers[0]->user_id,
                    'outcome'        => ['answered', 'no_answer', 'will_return', 'answered'][$idx % 4],
                    'notes'          => [
                        'Discussed morning workout routine with trainer.',
                        'No answer, left voicemail.',
                        'Confirmed renewal for next month.',
                        'Inquired about personal training packages.'
                    ][$idx % 4],
                    'followed_up_at' => now()->subDays($idx * 2 + 1)->subHours(rand(1, 5)),
                ]);
            }
        }
    }
}
