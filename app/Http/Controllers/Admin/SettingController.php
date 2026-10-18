<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GymSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = GymSetting::pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'gym_name'                 => 'required|string|max:100',
            'gym_phone'                => 'nullable|string|max:30',
            'gym_email'                => 'nullable|email',
            'gym_address'              => 'nullable|string',
            'inactive_threshold_days'  => 'required|integer|min:1|max:60',
            'renewal_alert_days'       => 'required|integer|min:1|max:30',
        ]);

        foreach ($data as $key => $value) {
            GymSetting::set($key, $value);
        }

        return back()->with('success', 'Settings saved.');
    }
}
