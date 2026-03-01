<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Shopping Experience
Route::get('/cart/data', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/buy-now', [App\Http\Controllers\CartController::class, 'buyNow'])->name('cart.buy-now');
Route::post('/wishlist/toggle', [App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::get('/wishlist/data', [App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
// Product Search
Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search');

// Checkout & Payment
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/create-order', [App\Http\Controllers\CheckoutController::class, 'createOrder'])->name('checkout.create_order');
    Route::post('/checkout/verify-payment', [App\Http\Controllers\CheckoutController::class, 'verifyPayment'])->name('checkout.verify_payment');
    Route::get('/checkout/success/{token}', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/receipt/{token}', [App\Http\Controllers\ReceiptController::class, 'download'])->name('checkout.receipt');
    
    // Coupon Mastery
    Route::post('/checkout/coupon/apply', [App\Http\Controllers\CouponController::class, 'apply'])->name('checkout.coupon.apply');
    Route::delete('/checkout/coupon/remove', [App\Http\Controllers\CouponController::class, 'remove'])->name('checkout.coupon.remove');
});
Route::get('/collection/{token}', [App\Http\Controllers\CollectionController::class, 'show'])->name('collection.show');
Route::get('/artifact/{token}', [App\Http\Controllers\ProductController::class, 'show'])->name('artifact.show');
Route::post('/artifact/{token}/reviews', [App\Http\Controllers\ProductReviewController::class, 'store'])->name('artifact.reviews.store')->middleware('auth');

// Customer Dashboard Mastery
Route::middleware('auth')->prefix('my-account')->name('customer.')->group(function() {
    Route::get('/', [App\Http\Controllers\CustomerController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\CustomerController::class, 'profile'])->name('profile');
    Route::post('/profile', [App\Http\Controllers\CustomerController::class, 'updateProfile'])->name('profile.update');
    Route::get('/orders', [App\Http\Controllers\CustomerController::class, 'orders'])->name('orders');
    Route::get('/addresses', [App\Http\Controllers\CustomerController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [App\Http\Controllers\CustomerController::class, 'storeAddress'])->name('addresses.store');
    Route::post('/addresses/{address}/default', [App\Http\Controllers\CustomerController::class, 'setDefaultAddress'])->name('addresses.default');
    Route::delete('/addresses/{address}', [App\Http\Controllers\CustomerController::class, 'deleteAddress'])->name('addresses.delete');
    Route::post('/wishlist/toggle-sharing', [App\Http\Controllers\CustomerController::class, 'toggleWishlistSharing'])->name('wishlist.toggle_sharing');
    Route::get('/orders/{token}', [App\Http\Controllers\CustomerController::class, 'showOrder'])->name('orders.show');
    Route::patch('/orders/{token}/cancel', [App\Http\Controllers\CustomerController::class, 'cancelOrder'])->name('orders.cancel');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/verify', function() { return view('auth.otp'); })->name('verify');

// Google Auth
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Performant API Routes (Moved to web middleware for session support)
Route::prefix('api/auth')->group(function () {
    Route::post('/login', [App\Http\Controllers\Api\AuthApiController::class, 'login']);
    Route::post('/register', [App\Http\Controllers\Api\AuthApiController::class, 'register']);
});

// Elite Admin Portal (Protected Routes - Only for Admin Tiers)
Route::prefix('admin')->name('admin.')->middleware('role:super_admin,admin,employee')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    
    // User & Franchise Mastery
    Route::get('/franchises', [App\Http\Controllers\Admin\AdminController::class, 'franchiseManagement'])->name('franchises');
    Route::get('/users', [App\Http\Controllers\Admin\AdminController::class, 'userManagement'])->name('users');
    Route::post('/franchises/{user}/approve', [App\Http\Controllers\Admin\AdminController::class, 'approveFranchise'])->name('approve_franchise');
    Route::patch('/users/{user}/toggle-block', [App\Http\Controllers\Admin\AdminController::class, 'toggleBlock'])->name('toggle_block');
    
    // Master Catalog Management
    Route::resource('categories', App\Http\Controllers\Admin\AdminCategoryController::class);
    Route::resource('products', App\Http\Controllers\Admin\AdminProductController::class);
    
    // Master Order Master Hub
    Route::get('/orders/kanban', [App\Http\Controllers\Admin\KanbanOrderController::class, 'index'])->name('orders.kanban');
    Route::patch('/orders/kanban/{token}/status', [App\Http\Controllers\Admin\KanbanOrderController::class, 'updateStatus'])->name('orders.kanban.update');
    Route::get('/orders', [App\Http\Controllers\Admin\AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{token}', [App\Http\Controllers\Admin\AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{token}/status', [App\Http\Controllers\Admin\AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::delete('/orders/{token}', [App\Http\Controllers\Admin\AdminOrderController::class, 'destroy'])->name('orders.destroy');
    
    // Global Broadcast Network
    Route::resource('broadcasts', App\Http\Controllers\Admin\GlobalBroadcastController::class)->except(['show']);
    Route::patch('/broadcasts/{broadcast}/toggle', [App\Http\Controllers\Admin\GlobalBroadcastController::class, 'toggle'])->name('broadcasts.toggle');
    
    // Page Content Management
    Route::get('/page-content', [App\Http\Controllers\Admin\AdminPageContentController::class, 'index'])->name('page-content.index');
    Route::post('/page-content/update', [App\Http\Controllers\Admin\AdminPageContentController::class, 'update'])->name('page-content.update');

    // HQ Restock Management
    Route::get('/restocks', [App\Http\Controllers\Admin\AdminRestockController::class, 'index'])->name('restocks.index');
    Route::patch('/restocks/{restock}', [App\Http\Controllers\Admin\AdminRestockController::class, 'update'])->name('restocks.update');
    
    // Divine Coupon Mastery
    Route::resource('coupons', App\Http\Controllers\Admin\AdminCouponController::class);
    
    // Future Content Modules can be added here
    Route::get('/settings', function() { return "Elite Settings Registry - Coming Soon"; })->name('settings');
});

// Supreme Admin Portal (Protected Routes - Only for Super Admin Tier)
Route::prefix('superadmin')->name('superadmin.')->middleware('role:super_admin')->group(function () {
    // Employee Access Management
    Route::get('/employees', [\App\Http\Controllers\Admin\AdminEmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [\App\Http\Controllers\Admin\AdminEmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [\App\Http\Controllers\Admin\AdminEmployeeController::class, 'store'])->name('employees.store');
    Route::patch('/employees/{employee}/toggle-block', [\App\Http\Controllers\Admin\AdminEmployeeController::class, 'toggleBlock'])->name('employees.toggle_block');
});

// Elite Franchise Partner Portal (Protected Routes - Only for Franchise Tier)
Route::prefix('franchise')->name('franchise.')->middleware('role:franchise')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Franchise\FranchiseController::class, 'dashboard'])->name('dashboard');
    Route::get('/catalog', [\App\Http\Controllers\Franchise\FranchiseController::class, 'catalog'])->name('catalog');
    
    // Partner Inventory Management
    Route::get('/inventory', [\App\Http\Controllers\Franchise\FranchiseController::class, 'inventory'])->name('inventory');
    Route::get('/inventory/create', [\App\Http\Controllers\Franchise\FranchiseController::class, 'createProduct'])->name('inventory.create');
    Route::post('/inventory/store', [\App\Http\Controllers\Franchise\FranchiseController::class, 'storeProduct'])->name('inventory.store');
    Route::get('/inventory/{product}/edit', [\App\Http\Controllers\Franchise\FranchiseController::class, 'editProduct'])->name('inventory.edit');
    Route::put('/inventory/{product}', [\App\Http\Controllers\Franchise\FranchiseController::class, 'updateProduct'])->name('inventory.update');
    Route::delete('/inventory/{product}', [\App\Http\Controllers\Franchise\FranchiseController::class, 'destroyProduct'])->name('inventory.destroy');

    // Partner Restock Flow
    Route::get('/restock', [\App\Http\Controllers\Franchise\RestockRequestController::class, 'index'])->name('restock.index');
    Route::get('/restock/create', [\App\Http\Controllers\Franchise\RestockRequestController::class, 'create'])->name('restock.create');
    Route::post('/restock', [\App\Http\Controllers\Franchise\RestockRequestController::class, 'store'])->name('restock.store');
});

