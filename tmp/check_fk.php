<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$constraints = DB::select(
    'SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE 
     WHERE TABLE_NAME="order_items" 
     AND REFERENCED_TABLE_NAME IS NOT NULL 
     AND TABLE_SCHEMA="bhavanicrafts"'
);

foreach ($constraints as $c) {
    echo $c->CONSTRAINT_NAME . "\n";
}
