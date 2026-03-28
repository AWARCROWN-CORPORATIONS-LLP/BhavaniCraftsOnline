<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Shared Category Image Manager
 * Accessible by: Admin, Franchise, Employee roles
 * Only updates image / icon — name/slug managed by Admin
 */
class CategoryImageController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        return view('shared.categories.index', compact('categories'));
    }

    public function edit(Category $category)
    {
        return view('shared.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'icon_url' => 'nullable|url|max:500',
        ]);

        $data = [];

        if ($request->hasFile('image')) {
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }
            $path = $request->file('image')->store('categories', 'public');
            $data['image_path'] = $path;
        }

        if ($request->boolean('clear_image') && $category->image_path) {
            Storage::disk('public')->delete($category->image_path);
            $data['image_path'] = null;
        }

        if ($request->filled('icon_url')) {
            $data['icon_url'] = $request->icon_url;
        }

        if (!empty($data)) {
            $category->update($data);
        }

        return back()->with('success', 'Category image updated successfully.');
    }
}
