<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with('recorder')->latest('incurred_at');

        if ($cat = $request->get('category')) {
            $query->where('category', $cat);
        }

        $expenses     = $query->paginate(25)->withQueryString();
        $totalMonth   = Expense::thisMonth()->sum('amount');
        $categories   = Expense::distinct()->pluck('category');

        return view('admin.expenses.index', compact('expenses', 'totalMonth', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category'    => 'required|string|max:100',
            'amount'      => 'required|numeric|min:1',
            'description' => 'nullable|string',
            'incurred_at' => 'required|date',
        ]);
        $data['recorded_by'] = auth()->id();
        Expense::create($data);
        return back()->with('success', 'Expense recorded.');
    }
}
