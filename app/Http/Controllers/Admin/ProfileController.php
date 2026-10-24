<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'email'         => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'         => 'nullable|string|max:20',
            'password'      => 'nullable|string|min:8|confirmed',
            'avatar'        => 'nullable|image|max:4096|mimes:jpeg,png,jpg,webp',
            'remove_avatar' => 'nullable|boolean',
        ]);

        $user->name  = $data['name'];
        $user->email = $data['email'];
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

        return back()->with('success', 'Admin profile and credentials updated successfully.');
    }
}
