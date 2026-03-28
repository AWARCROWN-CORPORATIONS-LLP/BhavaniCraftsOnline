<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$admins = User::all();
foreach ($admins as $admin) {
    echo "ID: {$admin->id}, Username: {$admin->username}, Email: {$admin->email}, Type: {$admin->user_type}\n";
}
