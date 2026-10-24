<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('member.profile', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'phone'         => 'nullable|string|max:20',
            'password'      => 'nullable|string|min:8|confirmed',
            'avatar'        => 'nullable|image|max:4096|mimes:jpeg,png,jpg,webp',
            'remove_avatar' => 'nullable|boolean',
        ]);

        $user->name  = $data['name'];
        $user->phone = $data['phone'];

        // Remove avatar
        if ($request->boolean('remove_avatar')) {
            if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = null;
        }

        // Upload new avatar
        if ($request->hasFile('avatar')) {
            if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return back()->with('success', 'Profile and profile picture updated successfully.');
    }
}
