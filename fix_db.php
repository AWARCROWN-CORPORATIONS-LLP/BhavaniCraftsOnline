<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Product;

// Fix categories
$mapping = [
    'Brass Pooja Items' => 'categories/cat_pooja_sets.png',
    'Wooden Pooja Items' => 'categories/cat_mandirs.png',
    'Pooja Thalis' => 'categories/cat_pooja_sets.png',
    'Traditional Diyas' => 'categories/cat_diyas_lamps.png',
    'Spiritual Accessories' => 'categories/cat_incense.png',
    'Pooja Samagri & Decor' => 'categories/cat_incense.png',
    'Divine Brass Idols' => 'categories/cat_brass_idols.png',
    'Pooja Mandir Essentials' => 'categories/cat_mandirs.png',
    'Festive Decoration Artifacts' => 'categories/cat_diyas_lamps.png',
    'Spiritual Incense & Aromas' => 'categories/cat_incense.png',
    'Custom Handcrafted Gifts' => 'categories/cat_pooja_sets.png',
    'Wall Hanging Artifacts' => 'categories/cat_wall_hanging.png',
    'Handwoven Silk Accessories' => 'categories/cat_silk_accessories.png'
];

foreach ($mapping as $name => $path) {
    $cat = Category::where('name', $name)->first();
    if ($cat) {
        $cat->image_path = $path;
        $cat->save();
        echo "Updated category $name with image $path\n";
    }
}

// Ensure all products are listed
$updated = Product::where('listed_status', '!=', 'Listed')->update(['listed_status' => 'Listed']);
echo "Ensured $updated products are set to 'Listed' status.\n";

echo "Database cleanup complete.\n";
