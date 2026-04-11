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
    public function index($locale)
    {
        $products = Product::with(['category', 'user'])->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.products.list', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create($locale)
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
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
            'full_description' => 'nullable|string',
            'youtube_url' => 'nullable|url',
            'stock' => 'required|integer',
            'listed_status' => 'required|in:Listed,Unlisted,Draft',
            'images' => 'required|array|size:3',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'model_3d' => 'nullable|file|max:10240', // Max 10MB GLB
            'model_usdz' => 'nullable|file|max:10240'  // Max 10MB USDZ
        ]);

        $data = $request->all();
        if ($request->hasFile('model_3d')) {
            $data['model_3d'] = $request->file('model_3d')->store('models', 'public');
        }
        if ($request->hasFile('model_usdz')) {
            $data['model_usdz'] = $request->file('model_usdz')->store('models', 'public');
        }

        $product = Product::create(array_merge($data, [
            'product_code' => 'BCM-' . strtoupper(Str::random(6)),
            'user_id' => null, // Admin created
        ]));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $this->uploadAndOptimize($image, 'products');
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
    public function edit($locale, Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
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
            'full_description' => 'nullable|string',
            'youtube_url' => 'nullable|url',
            'stock' => 'required|integer',
            'listed_status' => 'required|in:Listed,Unlisted,Draft',
            'images' => 'nullable|array|size:3',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'model_3d' => 'nullable|file|max:10240',
            'model_usdz' => 'nullable|file|max:10240'
        ]);

        $data = $request->except('images');
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
            // Remove old Trinity from Registry & Storage
            foreach ($product->images as $oldImage) {
                Storage::disk('public')->delete($oldImage->image_url);
                $oldImage->delete();
            }

            // Register New Trinity
            foreach ($request->file('images') as $index => $image) {
                $path = $this->uploadAndOptimize($image, 'products');
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
    public function destroy($locale, Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Artifact removed from catalog.');
    }
    /**
     * Upload and optimize an image, converting to WebP if GD is available.
     * Otherwise falls back to standard storage.
     */
    private function uploadAndOptimize($file, $folder)
    {
        if (function_exists('imagewebp')) {
            try {
                $filename = Str::random(40) . '.webp';
                $tempPath = tempnam(sys_get_temp_dir(), 'webp');
                
                $info = getimagesize($file->getRealPath());
                $image = match($info[2]) {
                    IMAGETYPE_JPEG => imagecreatefromjpeg($file->getRealPath()),
                    IMAGETYPE_PNG  => imagecreatefrompng($file->getRealPath()),
                    IMAGETYPE_WEBP => imagecreatefromwebp($file->getRealPath()),
                    default        => null
                };

                if ($image) {
                    imagewebp($image, $tempPath, 80);
                    $path = Storage::disk('public')->putFileAs($folder, new \Illuminate\Http\File($tempPath), $filename);
                    imagedestroy($image);
                    @unlink($tempPath);
                    return $folder . '/' . $filename;
                }
            } catch (\Exception $e) {
                \Log::error('Sacred WebP conversion failed: ' . $e->getMessage());
            }
        }
        
        return $file->store($folder, 'public');
    }
}
