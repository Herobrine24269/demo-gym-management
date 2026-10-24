<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DietPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'cover_image'    => 'nullable|image|max:5120|mimes:jpeg,png,jpg,webp',
        ]);

        $imagePath = null;
        if ($request->hasFile('cover_image')) {
            $imagePath = $request->file('cover_image')->store('diet-plans', 'public');
        }

        DietPlan::create([
            'name'           => $data['name'],
            'goal'           => $data['goal'] ?? null,
            'total_calories' => $data['total_calories'] ?? null,
            'total_protein_g'=> $data['protein_g'] ?? null,
            'total_carbs_g'  => $data['carbs_g'] ?? null,
            'total_fat_g'    => $data['fat_g'] ?? null,
            'description'    => $data['description'] ?? null,
            'cover_image'    => $imagePath,
            'created_by'     => auth()->id(),
        ]);

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
            'cover_image'    => 'nullable|image|max:5120|mimes:jpeg,png,jpg,webp',
            'remove_image'   => 'nullable|boolean',
        ]);

        $imagePath = $dietPlan->cover_image;

        // Remove image
        if ($request->boolean('remove_image')) {
            if ($dietPlan->cover_image && !str_starts_with($dietPlan->cover_image, 'http')) {
                Storage::disk('public')->delete($dietPlan->cover_image);
            }
            $imagePath = null;
        }

        // Upload new image
        if ($request->hasFile('cover_image')) {
            if ($dietPlan->cover_image && !str_starts_with($dietPlan->cover_image, 'http')) {
                Storage::disk('public')->delete($dietPlan->cover_image);
            }
            $imagePath = $request->file('cover_image')->store('diet-plans', 'public');
        }

        $dietPlan->update([
            'name'           => $data['name'],
            'goal'           => $data['goal'] ?? null,
            'total_calories' => $data['total_calories'] ?? null,
            'total_protein_g'=> $data['protein_g'] ?? null,
            'total_carbs_g'  => $data['carbs_g'] ?? null,
            'total_fat_g'    => $data['fat_g'] ?? null,
            'description'    => $data['description'] ?? null,
            'cover_image'    => $imagePath,
        ]);

        return redirect()->route('admin.diet-plans.index')->with('success', 'Diet plan updated.');
    }

    public function destroy(DietPlan $dietPlan)
    {
        if ($dietPlan->cover_image && !str_starts_with($dietPlan->cover_image, 'http')) {
            Storage::disk('public')->delete($dietPlan->cover_image);
        }
        $dietPlan->delete();
        return redirect()->route('admin.diet-plans.index')->with('success', 'Diet plan deleted.');
    }
}
