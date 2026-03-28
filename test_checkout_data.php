<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

$users = User::all();
echo "Total Users: " . $users->count() . "\n";
foreach ($users as $u) {
    $count = Address::where('user_id', $u->id)->count();
    echo "User ID: {$u->id}, Email: {$u->email}, Addresses: $count\n";
    if ($count > 0) {
        $addrs = Address::where('user_id', $u->id)->get();
        foreach ($addrs as $a) {
            echo "  - Addr ID: {$a->id}, Default: " . ($a->is_default ? 'Yes' : 'No') . "\n";
        }
    }
}
