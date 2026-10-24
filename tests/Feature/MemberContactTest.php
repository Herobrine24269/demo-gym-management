<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Member;
use App\Models\FollowUp;
use App\Models\Trainer;
use App\Models\TrainerAssignment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MemberContactTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }
    public function test_admin_members_page_displays_call_log_and_email_buttons(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get(route('admin.members.index'));

        $response->assertStatus(200);
        $response->assertSee('Call Log');
        $response->assertSee('Email');
        $response->assertSee('id="call-modal"', false);
    }

    public function test_admin_can_log_a_call_for_a_member(): void
    {
        $admin = User::where('role', 'admin')->first();
        $member = Member::first();
        $this->assertNotNull($member);

        $response = $this->actingAs($admin)->post(route('admin.members.call-log', $member), [
            'outcome' => 'answered',
            'notes'   => 'Discussed membership renewal with admin.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('follow_ups', [
            'member_id' => $member->id,
            'called_by' => $admin->id,
            'outcome'   => 'answered',
            'notes'     => 'Discussed membership renewal with admin.',
        ]);
    }

    public function test_trainer_members_page_displays_call_log_and_email_buttons(): void
    {
        $trainerUser = User::where('role', 'trainer')->first();
        $this->assertNotNull($trainerUser);

        $response = $this->actingAs($trainerUser)->get(route('trainer.members.index'));

        $response->assertStatus(200);
        $response->assertSee('Call Log');
        $response->assertSee('Email');
        $response->assertSee('id="call-modal"', false);
    }

    public function test_trainer_can_log_a_call_for_assigned_member(): void
    {
        $trainerUser = User::where('role', 'trainer')->first();
        $trainer = $trainerUser->trainer;
        $assignment = TrainerAssignment::where('trainer_id', $trainer->id)->whereNull('ended_at')->first();
        $this->assertNotNull($assignment);
        $member = $assignment->member;

        $response = $this->actingAs($trainerUser)->post(route('trainer.members.call-log', $member), [
            'outcome' => 'will_return',
            'notes'   => 'Trainer check-in: Member confirmed next workout session.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('follow_ups', [
            'member_id' => $member->id,
            'called_by' => $trainerUser->id,
            'outcome'   => 'will_return',
            'notes'     => 'Trainer check-in: Member confirmed next workout session.',
        ]);
    }

    public function test_call_log_requires_valid_outcome(): void
    {
        $admin = User::where('role', 'admin')->first();
        $member = Member::first();

        $response = $this->actingAs($admin)->post(route('admin.members.call-log', $member), [
            'outcome' => 'invalid_outcome_value',
            'notes'   => 'Some notes',
        ]);

        $response->assertSessionHasErrors('outcome');
    }
}
