<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    /**
     * Display a listing of the core products.
     */
    public function index()
    {
        $products = Product::with(['category', 'user'])->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.products.list', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'telugu_name' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'mrp' => 'nullable|numeric',
            'category_id' => 'required|exists:categories,id',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'short_description' => 'required|string',
            'full_description' => 'nullable|string',
            'stock' => 'required|integer',
            'listed_status' => 'required|in:Listed,Unlisted,Draft',
            'images' => 'required|array|size:3',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $product = Product::create(array_merge($request->all(), [
            'product_code' => 'BCM-' . strtoupper(Str::random(6)),
            'user_id' => null, // Admin created
        ]));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $path,
                    'is_main' => ($index === 0)
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Master artifact added with Sacred Trinity of images.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'telugu_name' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'mrp' => 'nullable|numeric',
            'category_id' => 'required|exists:categories,id',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'short_description' => 'required|string',
            'full_description' => 'nullable|string',
            'stock' => 'required|integer',
            'listed_status' => 'required|in:Listed,Unlisted,Draft',
            'images' => 'nullable|array|size:3',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $product->update($request->except('images'));

        if ($request->hasFile('images')) {
            // Remove old Trinity from Registry & Storage
            foreach ($product->images as $oldImage) {
                Storage::disk('public')->delete($oldImage->image_url);
                $oldImage->delete();
            }

            // Register New Trinity
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $path,
                    'is_main' => ($index === 0)
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Master catalog updated & visual suite synchronized.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Artifact removed from catalog.');
    }
}
