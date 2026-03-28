<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\ProductImage;

$products = Product::where('listed_status', 'Listed')->get();
echo "Found " . $products->count() . " listed products.\n";

foreach ($products as $p) {
    echo "ID: {$p->id}, Name: {$p->product_name}, Status: {$p->listed_status}, Slug: {$p->slug}\n";
    $images = $p->images;
    echo "  Images: " . $images->count() . "\n";
    foreach ($images as $img) {
        echo "    - " . $img->image_url . " (Main: " . ($img->is_main ? 'Yes' : 'No') . ")\n";
    }
}
