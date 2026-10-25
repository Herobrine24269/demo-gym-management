<?php

namespace App\Http\Controllers;

use App\Models\DietPlan;
use Illuminate\Http\Request;

class DietPlanController extends Controller
{
    /**
     * Display a listing of public diet plans.
     */
    public function index()
    {
        $diets = DietPlan::where('is_public', true)->latest()->get();
        return view('diet-plans.index', compact('diets'));
    }

    /**
     * Show the details of a single diet plan.
     */
    public function show(DietPlan $dietPlan)
    {
        return view('diet-plans.show', compact('dietPlan'));
    }
}
