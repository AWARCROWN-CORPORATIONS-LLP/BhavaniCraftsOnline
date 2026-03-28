<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

$updated = 0;
foreach (Product::all() as $p) {
    if ($p->listed_status != 'Listed') {
        $p->listed_status = 'Listed';
        $p->save();
        $updated++;
    }
}

echo "Successfully updated $updated products to 'Listed' status.\n";
echo "Total products: " . Product::count() . "\n";
