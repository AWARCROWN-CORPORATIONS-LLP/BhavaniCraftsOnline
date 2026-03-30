<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentWebhookController;

Route::post('/payment/webhook', [PaymentWebhookController::class, 'handle']);
