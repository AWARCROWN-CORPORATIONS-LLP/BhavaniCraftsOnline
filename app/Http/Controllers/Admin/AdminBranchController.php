<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class AdminBranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($locale)
    {
        $branches = Branch::orderBy('sort_order')->get();
        return view('admin.branches.list', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($locale)
    {
        return view('admin.branches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $locale)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'map_link' => 'nullable|url',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        Branch::create($data);

        return redirect()->route('admin.branches.index', $locale)->with('success', 'Branch added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($locale, Branch $branch)
    {
        return view('admin.branches.edit', compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $locale, Branch $branch)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'map_link' => 'nullable|url',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $branch->update($data);

        return redirect()->route('admin.branches.index', $locale)->with('success', 'Branch updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($locale, Branch $branch)
    {
        $branch->delete();
        return redirect()->route('admin.branches.index', $locale)->with('success', 'Branch deleted successfully');
    }
}
