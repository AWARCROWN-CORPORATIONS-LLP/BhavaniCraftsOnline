<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    Schema::table('orders', function (Blueprint $table) {
        $table->dropForeign(['coupon_id']);
        $table->dropColumn(['coupon_id', 'discount_amount']);
    });
} catch (\Exception $e) {}
Schema::dropIfExists('coupons');
echo "Cleaned up.\n";

try {
    Schema::create('coupons', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique()->index();
        $table->enum('type', ['percentage', 'fixed'])->default('percentage');
        $table->decimal('value', 10, 2);
        $table->decimal('min_order_amount', 10, 2)->default(0);
        $table->integer('usage_limit')->nullable();
        $table->integer('used_count')->default(0);
        $table->dateTime('expires_at')->nullable();
        $table->boolean('status')->default(true);
        $table->timestamps();
    });
    echo "Coupons table created.\n";

    Schema::table('orders', function (Blueprint $table) {
        $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
        $table->decimal('discount_amount', 10, 2)->default(0);
    });
    echo "Orders table updated.\n";
} catch (\Exception $e) {
    echo $e->getMessage();
}
