<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RitualKit;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminRitualKitController extends Controller
{
    public function index()
    {
        $kits = RitualKit::withCount('products')->latest()->paginate(10);
        return view('admin.ritual-kits.index', compact('kits'));
    }

    public function create()
    {
        $products = Product::where('listed_status', 'Listed')->get();
        return view('admin.ritual-kits.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'required|boolean',
            'display_image' => 'nullable|image|max:2048',
            'products' => 'required|array|min:1',
        ]);

        $data = $request->only(['name', 'description', 'price', 'is_active']);
        $data['slug'] = Str::slug($request->name) . '-' . uniqid();

        if ($request->hasFile('display_image')) {
            $data['display_image'] = $request->file('display_image')->store('ritual-kits', 'public');
        }

        $kit = RitualKit::create($data);
        $kit->products()->attach($request->products);

        return redirect()->route('admin.ritual-kits.index')->with('success', 'Ritual Kit created successfully.');
    }

    public function edit(RitualKit $ritualKit)
    {
        $products = Product::where('listed_status', 'Listed')->get();
        return view('admin.ritual-kits.edit', compact('ritualKit', 'products'));
    }

    public function update(Request $request, RitualKit $ritualKit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'required|boolean',
            'display_image' => 'nullable|image|max:2048',
            'products' => 'required|array|min:1',
        ]);

        $data = $request->only(['name', 'description', 'price', 'is_active']);
        
        if ($request->hasFile('display_image')) {
            if ($ritualKit->display_image) {
                Storage::disk('public')->delete($ritualKit->display_image);
            }
            $data['display_image'] = $request->file('display_image')->store('ritual-kits', 'public');
        }

        $ritualKit->update($data);
        $ritualKit->products()->sync($request->products);

        return redirect()->route('admin.ritual-kits.index')->with('success', 'Ritual Kit updated.');
    }

    public function destroy(RitualKit $ritualKit)
    {
        if ($ritualKit->display_image) {
            Storage::disk('public')->delete($ritualKit->display_image);
        }
        $ritualKit->delete();
        return redirect()->route('admin.ritual-kits.index')->with('success', 'Ritual Kit deleted.');
    }
}
