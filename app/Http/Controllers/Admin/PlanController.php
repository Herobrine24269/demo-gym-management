<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = MembershipPlan::withCount('memberships')->latest()->paginate(20);
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'duration_days' => 'required|integer|min:1',
            'price'         => 'required|numeric|min:0',
            'features'      => 'nullable|string',
        ]);

        $features = array_filter(array_map('trim', explode("\n", $data['features'] ?? '')));

        MembershipPlan::create([
            'name'          => $data['name'],
            'duration_days' => $data['duration_days'],
            'price'         => $data['price'],
            'features'      => array_values($features),
            'is_active'     => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.plans.index')->with('success', 'Plan created.');
    }

    public function show(MembershipPlan $plan)
    {
        return view('admin.plans.show', compact('plan'));
    }

    public function edit(MembershipPlan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, MembershipPlan $plan)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'duration_days' => 'required|integer|min:1',
            'price'         => 'required|numeric|min:0',
            'features'      => 'nullable|string',
        ]);

        $features = array_filter(array_map('trim', explode("\n", $data['features'] ?? '')));

        $plan->update([
            'name'          => $data['name'],
            'duration_days' => $data['duration_days'],
            'price'         => $data['price'],
            'features'      => array_values($features),
            'is_active'     => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.plans.index')->with('success', 'Plan updated.');
    }

    public function destroy(MembershipPlan $plan)
    {
        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted.');
    }
}
