<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\Payment;
use App\Models\Trainer;
use App\Models\TrainerAssignment;
use App\Models\Streak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with('user', 'activeMembership.plan', 'streak', 'followUps.caller');

        if ($search = $request->get('q')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%"))
                  ->orWhere('member_code', 'like', "%{$search}%");
        }
        if ($status = $request->get('status')) {
            if ($status === 'active') {
                $query->whereHas('memberships', fn($q) => $q->where('status', 'active'));
            } elseif ($status === 'expired') {
                $query->whereDoesntHave('memberships', fn($q) => $q->where('status', 'active'));
            }
        }

        $members = $query->latest()->paginate(25)->withQueryString();
        $totalMembers  = Member::count();
        $activeMembers = Membership::where('status', 'active')->distinct('member_id')->count('member_id');

        return view('admin.members.index', compact('members', 'totalMembers', 'activeMembers'));
    }

    public function create()
    {
        $plans    = MembershipPlan::where('is_active', true)->get();
        $trainers = Trainer::where('is_active', true)->with('user')->get();
        return view('admin.members.create', compact('plans', 'trainers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:100',
            'email'          => 'required|email|unique:users,email',
            'phone'          => 'nullable|string|max:20',
            'password'       => 'required|string|min:6',
            'gender'         => 'nullable|in:male,female,other',
            'dob'            => 'nullable|date',
            'address'        => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:100',
            'health_notes'   => 'nullable|string',
            'joined_at'      => 'required|date',
            'plan_id'        => 'required|exists:membership_plans,id',
            'amount_paid'    => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,bank_transfer,other',
            'trainer_id'     => 'nullable|exists:trainers,id',
            'avatar'         => 'nullable|image|max:4096|mimes:jpeg,png,jpg,webp',
        ]);

        DB::transaction(function () use ($data, $request) {
            $avatarPath = null;
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
            }

            // Create user
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'phone'    => $data['phone'],
                'password' => Hash::make($data['password']),
                'role'     => 'member',
                'avatar'   => $avatarPath,
            ]);

            // Generate member code
            $lastCode = Member::max('id') ?? 0;
            $code     = 'APX-' . str_pad($lastCode + 1001, 4, '0', STR_PAD_LEFT);

            $member = Member::create([
                'user_id'           => $user->id,
                'member_code'       => $code,
                'gender'            => $data['gender'],
                'dob'               => $data['dob'],
                'address'           => $data['address'],
                'emergency_contact' => $data['emergency_contact'],
                'health_notes'      => $data['health_notes'],
                'joined_at'         => $data['joined_at'],
            ]);

            // Create membership
            $plan  = MembershipPlan::findOrFail($data['plan_id']);
            $start = \Carbon\Carbon::parse($data['joined_at']);
            $end   = $start->copy()->addDays($plan->duration_days);

            $ms = Membership::create([
                'member_id'      => $member->id,
                'plan_id'        => $plan->id,
                'start_date'     => $start,
                'end_date'       => $end,
                'status'         => 'active',
                'amount_paid'    => $data['amount_paid'],
                'payment_method' => $data['payment_method'],
                'created_by'     => auth()->id(),
            ]);

            // Create payment record
            Payment::create([
                'member_id'      => $member->id,
                'type'           => 'membership',
                'amount'         => $data['amount_paid'],
                'payment_method' => $data['payment_method'],
                'reference_id'   => $ms->id,
                'reference_type' => 'Membership',
                'status'         => 'paid',
                'received_by'    => auth()->id(),
                'paid_at'        => now(),
            ]);

            // Initialize streak
            Streak::create(['member_id' => $member->id]);

            // Assign trainer if selected
            if (!empty($data['trainer_id'])) {
                TrainerAssignment::create([
                    'trainer_id'  => $data['trainer_id'],
                    'member_id'   => $member->id,
                    'assigned_at' => today(),
                ]);
            }
        });

        return redirect()->route('admin.members.index')->with('success', 'Member created and membership activated.');
    }

    public function show(Member $member)
    {
        $member->load('user', 'memberships.plan', 'activeMembership.plan', 'streak',
                      'trainerAssignments.trainer.user', 'workoutPlans.trainer.user',
                      'ptSessions.trainer.user', 'payments', 'attendance', 'followUps.caller',
                      'dietAssignments.dietPlan');
        return view('admin.members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        $member->load('user');
        $plans    = MembershipPlan::where('is_active', true)->get();
        $trainers = Trainer::where('is_active', true)->with('user')->get();
        return view('admin.members.edit', compact('member', 'plans', 'trainers'));
    }

    public function update(Request $request, Member $member)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:100',
            'email'             => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($member->user_id)],
            'phone'             => 'nullable|string|max:20',
            'avatar'            => 'nullable|image|max:4096|mimes:jpeg,png,jpg,webp',
            'remove_avatar'     => 'nullable|boolean',
            'gender'            => 'nullable|in:male,female,other',
            'dob'               => 'nullable|date',
            'address'           => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:100',
            'health_notes'      => 'nullable|string',
        ]);

        $userData = [
            'name'  => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
        ];

        if ($request->boolean('remove_avatar')) {
            if ($member->user->avatar && !str_starts_with($member->user->avatar, 'http')) {
                Storage::disk('public')->delete($member->user->avatar);
            }
            $userData['avatar'] = null;
        }

        if ($request->hasFile('avatar')) {
            if ($member->user->avatar && !str_starts_with($member->user->avatar, 'http')) {
                Storage::disk('public')->delete($member->user->avatar);
            }
            $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $member->user->update($userData);
        $member->update([
            'gender'            => $data['gender'],
            'dob'               => $data['dob'],
            'address'           => $data['address'],
            'emergency_contact' => $data['emergency_contact'],
            'health_notes'      => $data['health_notes'],
        ]);

        return redirect()->route('admin.members.show', $member)->with('success', 'Member and profile credentials updated.');
    }

    public function destroy(Member $member)
    {
        $memberName = $member->user?->name ?? $member->member_code;
        $memberCode = $member->member_code;

        DB::transaction(function () use ($member) {
            $user = $member->user;
            $member->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.members.index')->with('success', "Member {$memberName} ({$memberCode}) has been deleted successfully.");
    }

    public function payments(Member $member)
    {
        $payments = $member->payments()->latest('paid_at')->paginate(20);
        return view('admin.members.payments', compact('member', 'payments'));
    }

    public function callLog(Request $request, Member $member)
    {
        $request->validate([
            'outcome' => 'required|in:answered,no_answer,will_return,cancelled',
            'notes'   => 'nullable|string|max:500',
        ]);

        \App\Models\FollowUp::create([
            'member_id'      => $member->id,
            'called_by'      => auth()->id(),
            'outcome'        => $request->outcome,
            'notes'          => $request->notes,
            'followed_up_at' => now(),
        ]);

        return back()->with('success', 'Call log recorded for ' . $member->user->name . '.');
    }
}
