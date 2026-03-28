<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EmployeeProductController extends Controller
{
    /**
     * Display a listing of all products.
     */
    public function index($locale)
    {
        $products = Product::with(['category', 'user'])->orderBy('created_at', 'desc')->paginate(15);
        return view('employee.products.list', compact('products'));
    }

    /**
     * Show form for creating a new product.
     */
    public function create($locale)
    {
        $categories = Category::all();
        return view('employee.products.create', compact('categories'));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request, $locale)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'telugu_name' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'mrp' => 'nullable|numeric',
            'category_id' => 'required|exists:categories,id',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'short_description' => 'required|string',
            'stock' => 'required|integer',
            'listed_status' => 'required|in:Listed,Unlisted,Draft',
            'images' => 'required|array|size:3',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'model_3d' => 'nullable|file|max:10240', // Max 10MB GLB
            'model_usdz' => 'nullable|file|max:10240'  // Max 10MB USDZ
        ]);

        $data = $request->all();
        $data['price'] = $data['price'] ?? 0;
        $data['mrp'] = $data['mrp'] ?? 0;
        $data['discount_percent'] = $data['discount_percent'] ?? 0;

        if ($request->hasFile('model_3d')) {
            $data['model_3d'] = $request->file('model_3d')->store('models', 'public');
        }
        if ($request->hasFile('model_usdz')) {
            $data['model_usdz'] = $request->file('model_usdz')->store('models', 'public');
        }

        $product = Product::create(array_merge($data, [
            'product_code' => 'BCM-' . strtoupper(Str::random(6)),
            'user_id' => null, // Employee/Admin created
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

        return redirect()->route('employee.products.index')->with('success', 'Artifact added for review or catalog.');
    }

    /**
     * Show form for editing the product.
     */
    public function edit($locale, Product $product)
    {
        $categories = Category::all();
        return view('employee.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the product details.
     */
    public function update(Request $request, $locale, Product $product)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'telugu_name' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'mrp' => 'nullable|numeric',
            'category_id' => 'required|exists:categories,id',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'short_description' => 'required|string',
            'stock' => 'required|integer',
            'listed_status' => 'required|in:Listed,Unlisted,Draft',
            'images' => 'nullable|array|size:3',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'model_3d' => 'nullable|file|max:10240',
        ]);

        $data = $request->except(['images', 'model_3d', 'model_usdz']);
        if ($request->hasFile('model_3d')) {
            if ($product->model_3d) Storage::disk('public')->delete($product->model_3d);
            $data['model_3d'] = $request->file('model_3d')->store('models', 'public');
        }
        if ($request->hasFile('model_usdz')) {
            if ($product->model_usdz) Storage::disk('public')->delete($product->model_usdz);
            $data['model_usdz'] = $request->file('model_usdz')->store('models', 'public');
        }
        
        $product->update($data);

        if ($request->hasFile('images')) {
            foreach ($product->images as $oldImage) {
                Storage::disk('public')->delete($oldImage->image_url);
                $oldImage->delete();
            }

            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $path,
                    'is_main' => ($index === 0)
                ]);
            }
        }

        return redirect()->route('employee.products.index')->with('success', 'Product synchronized in catalog.');
    }
}
