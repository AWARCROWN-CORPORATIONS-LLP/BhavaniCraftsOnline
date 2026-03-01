<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "Dropping...\n";
try {
    Schema::table('orders', function (Blueprint $table) {
        $table->dropForeign(['coupon_id']);
    });
} catch (\Exception $e) { echo $e->getMessage() . "\n"; }

try {
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['coupon_id', 'discount_amount']);
    });
} catch (\Exception $e) { echo $e->getMessage() . "\n"; }

Schema::dropIfExists('coupons');
echo "Dropped.\n";
