<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DietPlan;
use Illuminate\Http\Request;

class DietPlanController extends Controller
{
    public function index()
    {
        $plans = DietPlan::latest()->paginate(20);
        return view('admin.diet-plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.diet-plans.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:150',
            'goal'           => 'nullable|string|max:150',
            'total_calories' => 'nullable|integer|min:0',
            'protein_g'      => 'nullable|integer|min:0',
            'carbs_g'        => 'nullable|integer|min:0',
            'fat_g'          => 'nullable|integer|min:0',
            'description'    => 'nullable|string',
        ]);
        DietPlan::create($data);
        return redirect()->route('admin.diet-plans.index')->with('success', 'Diet plan created.');
    }

    public function show(DietPlan $dietPlan)
    {
        $plan = $dietPlan;
        return view('admin.diet-plans.show', compact('plan'));
    }

    public function edit(DietPlan $dietPlan)
    {
        $plan = $dietPlan;
        return view('admin.diet-plans.edit', compact('plan'));
    }

    public function update(Request $request, DietPlan $dietPlan)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:150',
            'goal'           => 'nullable|string|max:150',
            'total_calories' => 'nullable|integer|min:0',
            'protein_g'      => 'nullable|integer|min:0',
            'carbs_g'        => 'nullable|integer|min:0',
            'fat_g'          => 'nullable|integer|min:0',
            'description'    => 'nullable|string',
        ]);
        $dietPlan->update($data);
        return redirect()->route('admin.diet-plans.index')->with('success', 'Diet plan updated.');
    }

    public function destroy(DietPlan $dietPlan)
    {
        $dietPlan->delete();
        return redirect()->route('admin.diet-plans.index')->with('success', 'Diet plan deleted.');
    }
}
