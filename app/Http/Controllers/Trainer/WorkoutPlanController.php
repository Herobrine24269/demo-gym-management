<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\WorkoutPlan;
use Illuminate\Http\Request;

class WorkoutPlanController extends Controller
{
    public function index()
    {
        $trainer = auth()->user()->trainer;
        $plans   = WorkoutPlan::where('trainer_id', $trainer->id)->with('member.user')->latest()->paginate(20);
        return view('trainer.workout-plans.index', compact('plans'));
    }

    public function create() { return view('trainer.workout-plans.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'member_id'   => 'required|exists:members,id',
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string',
            'weeks'       => 'required|integer|min:1|max:52',
        ]);
        $data['trainer_id'] = auth()->user()->trainer->id;
        WorkoutPlan::create($data);
        return redirect()->route('trainer.workout-plans.index')->with('success', 'Workout plan created.');
    }

    public function show(WorkoutPlan $workoutPlan) { return view('trainer.workout-plans.show', compact('workoutPlan')); }
    public function edit(WorkoutPlan $workoutPlan) { return view('trainer.workout-plans.edit', compact('workoutPlan')); }

    public function update(Request $request, WorkoutPlan $workoutPlan)
    {
        $data = $request->validate(['title'=>'required|string|max:150','status'=>'required|in:active,completed,paused']);
        $workoutPlan->update($data);
        return redirect()->route('trainer.workout-plans.index')->with('success', 'Workout plan updated.');
    }

    public function destroy(WorkoutPlan $workoutPlan)
    {
        $workoutPlan->delete();
        return redirect()->route('trainer.workout-plans.index')->with('success', 'Workout plan deleted.');
    }
}
