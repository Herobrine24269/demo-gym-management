<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TrainerController extends Controller
{
    public function index()
    {
        $trainers = Trainer::with('user')->withCount(['assignments as active_members_count' => fn($q) => $q->whereNull('ended_at')])->paginate(20);
        return view('admin.trainers.index', compact('trainers'));
    }

    public function create()
    {
        return view('admin.trainers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:100',
            'email'          => 'required|email|unique:users,email',
            'phone'          => 'nullable|string|max:20',
            'password'       => 'required|string|min:6',
            'specialization' => 'nullable|string|max:255',
            'bio'            => 'nullable|string',
            'salary'         => 'nullable|numeric|min:0',
            'hired_at'       => 'required|date',
            'avatar'         => 'nullable|image|max:4096|mimes:jpeg,png,jpg,webp',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
            'password' => Hash::make($data['password']),
            'role'     => 'trainer',
            'avatar'   => $avatarPath,
        ]);

        Trainer::create([
            'user_id'        => $user->id,
            'specialization' => $data['specialization'],
            'bio'            => $data['bio'],
            'salary'         => $data['salary'],
            'hired_at'       => $data['hired_at'],
            'is_active'      => true,
        ]);

        return redirect()->route('admin.trainers.index')->with('success', 'Trainer added.');
    }

    public function show(Trainer $trainer)
    {
        $trainer->load('user', 'assignments.member.user', 'ptSessions.member.user', 'workoutPlans.member.user');
        return view('admin.trainers.show', compact('trainer'));
    }

    public function edit(Trainer $trainer)
    {
        $trainer->load('user');
        return view('admin.trainers.edit', compact('trainer'));
    }

    public function update(Request $request, Trainer $trainer)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:100',
            'phone'          => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'bio'            => 'nullable|string',
            'salary'         => 'nullable|numeric|min:0',
            'is_active'      => 'boolean',
            'avatar'         => 'nullable|image|max:4096|mimes:jpeg,png,jpg,webp',
            'remove_avatar'  => 'nullable|boolean',
        ]);

        $userData = ['name' => $data['name'], 'phone' => $data['phone']];

        if ($request->boolean('remove_avatar')) {
            if ($trainer->user->avatar && !str_starts_with($trainer->user->avatar, 'http')) {
                Storage::disk('public')->delete($trainer->user->avatar);
            }
            $userData['avatar'] = null;
        }

        if ($request->hasFile('avatar')) {
            if ($trainer->user->avatar && !str_starts_with($trainer->user->avatar, 'http')) {
                Storage::disk('public')->delete($trainer->user->avatar);
            }
            $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $trainer->user->update($userData);
        $trainer->update([
            'specialization' => $data['specialization'],
            'bio'            => $data['bio'],
            'salary'         => $data['salary'],
            'is_active'      => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.trainers.show', $trainer)->with('success', 'Trainer updated.');
    }

    public function destroy(Trainer $trainer)
    {
        $trainer->user->delete();
        $trainer->delete();
        return redirect()->route('admin.trainers.index')->with('success', 'Trainer removed.');
    }
}
