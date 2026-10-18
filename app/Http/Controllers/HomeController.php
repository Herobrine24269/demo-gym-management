<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\DietPlan;
use App\Models\MembershipPlan;
use App\Models\Trainer;

class HomeController extends Controller
{
    public function index()
    {
        $posts    = Post::published()->with('category', 'author')->latest('published_at')->take(3)->get();
        $diets    = DietPlan::where('is_public', true)->latest()->take(3)->get();
        $plans    = MembershipPlan::where('is_active', true)->orderBy('price')->get();
        $trainers = Trainer::where('is_active', true)->with('user')->take(6)->get();

        return view('home.index', compact('posts', 'diets', 'plans', 'trainers'));
    }
}
