<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// orders table
echo "=== orders ===\n";
$cols = DB::select('DESCRIBE orders');
foreach ($cols as $c) {
    echo $c->Field . ' -> ' . $c->Type . "\n";
}

echo "\n=== order_items ===\n";
$cols = DB::select('DESCRIBE order_items');
foreach ($cols as $c) {
    echo $c->Field . ' -> ' . $c->Type . "\n";
}
