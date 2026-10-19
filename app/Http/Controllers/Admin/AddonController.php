<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\Request;

class AddonController extends Controller
{
    public function index()
    {
        $addons = Addon::latest()->paginate(20);
        return view('admin.addons.index', compact('addons'));
    }

    public function create()
    {
        return view('admin.addons.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'category'    => 'nullable|string|max:80',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        Addon::create($data);
        return redirect()->route('admin.addons.index')->with('success', 'Add-on created.');
    }

    public function show(Addon $addon)
    {
        return view('admin.addons.index'); // no dedicated show page
    }

    public function edit(Addon $addon)
    {
        return view('admin.addons.edit', compact('addon'));
    }

    public function update(Request $request, Addon $addon)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'category'    => 'nullable|string|max:80',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $addon->update($data);
        return redirect()->route('admin.addons.index')->with('success', 'Add-on updated.');
    }

    public function destroy(Addon $addon)
    {
        $addon->delete();
        return redirect()->route('admin.addons.index')->with('success', 'Add-on deleted.');
    }
}
