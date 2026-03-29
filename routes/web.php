<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::pattern('locale', '[a-z]{2}(-[a-z]{2})?');

Route::get('/', function (Illuminate\Http\Request $request) {
    $locationService = new \App\Services\LocationService();
    $locale = $locationService->detectLocale($request->ip());
    return redirect()->to('/' . $locale);
});

// Root-level Redirection for Super Admin Accessibility (Handles missing locale prefixes)
Route::redirect('/superadmin/employees', '/en/superadmin/employees');
Route::redirect('/superadmin/dashboard', '/en/superadmin/dashboard');

Route::prefix('{locale}')->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Static Pages & Policies
    Route::get('/privacy-policy', [App\Http\Controllers\PageController::class, 'privacy'])->name('pages.privacy');
    Route::get('/terms-of-service', [App\Http\Controllers\PageController::class, 'terms'])->name('pages.terms');
    Route::get('/cookie-policy', [App\Http\Controllers\PageController::class, 'cookie'])->name('pages.cookie');
    Route::get('/shipping-policy', [App\Http\Controllers\PageController::class, 'shipping'])->name('pages.shipping');
    Route::get('/faq', [App\Http\Controllers\PageController::class, 'faq'])->name('pages.faq');
    Route::get('/sacred-kit', [App\Http\Controllers\PageController::class, 'bundleBuilder'])->name('pages.sacred-kit');


    Route::get('/cart/data', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/buy-now', [App\Http\Controllers\CartController::class, 'buyNow'])->name('cart.buy-now');
    Route::post('/cart/buy-kit', [App\Http\Controllers\CartController::class, 'buyKit'])->name('cart.buy-kit');
    Route::post('/wishlist/toggle', [App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist/data', [App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::get('/sync/verify', [App\Http\Controllers\SyncController::class, 'verify'])->name('sync.verify');
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

    // Logistics Dispatch & Verification Registry
    Route::get('/logistics/verify/{token}', [\App\Http\Controllers\Shared\LogisticsController::class, 'verifyForm'])->name('logistics.verify');
    Route::post('/logistics/verify/{token}/submit', [\App\Http\Controllers\Shared\LogisticsController::class, 'processVerification'])->name('logistics.verify.submit');
    Route::get('/logistics/verify/{token}/failed', [\App\Http\Controllers\Shared\LogisticsController::class, 'markFailed'])->name('logistics.verify.failed');

    Route::get('/collection/{token}', [App\Http\Controllers\CollectionController::class, 'show'])->name('collection.show');
    Route::post('/collection/contribute/{registry_id}', [App\Http\Controllers\CollectionController::class, 'contribute'])->name('collection.contribute');
    Route::get('/artifact/{slug}', [App\Http\Controllers\ProductController::class, 'show'])->name('artifact.show');
    Route::post('/artifact/{slug}/reviews', [App\Http\Controllers\ProductReviewController::class, 'store'])->name('artifact.reviews.store')->middleware('auth');

    // Customer Dashboard Mastery (Shared Auth space)
    Route::middleware(['auth'])->prefix('my-account')->name('customer.')->group(function() {
        // Address Management (Essential for Checkout, allowed for unverified)
        Route::get('/addresses', [App\Http\Controllers\CustomerController::class, 'addresses'])->name('addresses');
        Route::post('/addresses', [App\Http\Controllers\CustomerController::class, 'storeAddress'])->name('addresses.store');
        Route::post('/addresses/{address}/default', [App\Http\Controllers\CustomerController::class, 'setDefaultAddress'])->name('addresses.default');
        Route::delete('/addresses/{address}', [App\Http\Controllers\CustomerController::class, 'deleteAddress'])->name('addresses.delete');

        // Verified-only High Privilege Actions
        Route::middleware(['verified'])->group(function() {
            Route::get('/', [App\Http\Controllers\CustomerController::class, 'dashboard'])->name('dashboard');
            Route::get('/profile', [App\Http\Controllers\CustomerController::class, 'profile'])->name('profile');
            Route::post('/profile', [App\Http\Controllers\CustomerController::class, 'updateProfile'])->name('profile.update');
            Route::get('/orders', [App\Http\Controllers\CustomerController::class, 'orders'])->name('orders');
            Route::post('/wishlist/toggle-sharing', [App\Http\Controllers\CustomerController::class, 'toggleWishlistSharing'])->name('wishlist.toggle_sharing');
            Route::get('/orders/{token}', [App\Http\Controllers\CustomerController::class, 'showOrder'])->name('orders.show');
            Route::post('/orders/{token}/generate-pin', [App\Http\Controllers\CustomerController::class, 'generateDeliveryPin'])->name('orders.generate_pin');
            Route::post('/orders/{token}/rate', [App\Http\Controllers\CustomerController::class, 'rateOrder'])->name('orders.rate');
            Route::post('/orders/{token}/return', [App\Http\Controllers\CustomerController::class, 'requestReturn'])->name('orders.return');
            Route::post('/orders/{token}/safety-complaint', [App\Http\Controllers\CustomerController::class, 'storeSafetyComplaint'])->name('orders.safety_complaint');
            Route::patch('/orders/{token}/cancel', [App\Http\Controllers\CustomerController::class, 'cancelOrder'])->name('orders.cancel');
        });
    });

    // Auth Routes
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Email Verification Core
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->middleware('auth')->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->middleware(['auth', 'signed'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])
        ->middleware(['auth', 'throttle:6,1'])
        ->name('verification.send');

    Route::get('/verify', function() { return view('auth.otp'); })->name('verify');

    // Google Auth
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

    // Performant API Routes (Moved to web middleware for session support)
    Route::prefix('api/auth')->group(function () {
        Route::get('/check-username', [App\Http\Controllers\Api\AuthApiController::class, 'checkUsername']);
        Route::post('/login', [App\Http\Controllers\Api\AuthApiController::class, 'login']);
        Route::post('/register', [App\Http\Controllers\Api\AuthApiController::class, 'register']);
        Route::post('/send-otp', [App\Http\Controllers\Api\AuthApiController::class, 'sendOtp']);
        Route::post('/verify-otp', [App\Http\Controllers\Api\AuthApiController::class, 'verifyOtp']);
        Route::post('/login-with-otp', [App\Http\Controllers\Api\AuthApiController::class, 'loginWithOtp']);
        
        // Sacred Specialist (AI Chatbot Backend)
        Route::post('/chat/ask', [App\Http\Controllers\Api\ChatController::class, 'ask'])->name('api.chat.ask');
    });

    // Poojari (Ritual Specialists) Ecosystem
    Route::prefix('poojari')->name('poojari.')->group(function () {
        Route::get('/', [App\Http\Controllers\PublicPoojariController::class, 'index'])->name('index');
        Route::get('/{slug}', [App\Http\Controllers\PublicPoojariController::class, 'show'])->name('show');
        Route::post('/book', [App\Http\Controllers\PublicPoojariController::class, 'book'])->name('book')->middleware('auth');
    });

    // Poojari Dedicated Dashboard
    Route::middleware(['auth', 'role:poojari'])->prefix('poojari-portal')->name('poojari.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Poojari\PoojariDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile/edit', [App\Http\Controllers\Poojari\PoojariDashboardController::class, 'editProfile'])->name('profile.edit');
        Route::post('/profile/update', [App\Http\Controllers\Poojari\PoojariDashboardController::class, 'updateProfile'])->name('profile.update');
    });

    // Elite Admin Portal (Protected Routes - Only for Admin Tiers)
    Route::prefix('admin')->name('admin.')->middleware('role:super_admin,admin,employee')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
        
        // System Telemetry & Auditing
        Route::get('/audit-registry', [App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit.index');
        
        // Financial & Inventory Compliance Extraction
        Route::get('/export/financials', [App\Http\Controllers\Admin\AdminExportController::class, 'exportOrders'])->name('export.orders');
        Route::get('/export/inventory', [App\Http\Controllers\Admin\AdminExportController::class, 'exportProducts'])->name('export.products');
        
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
        
        // Sacred Ritual Kits
        Route::resource('ritual-kits', App\Http\Controllers\Admin\AdminRitualKitController::class);
        
        // Category Image Management (Admin can do full CRUD via resource, but also update images via shared controller)
        Route::get('/category-images', [App\Http\Controllers\CategoryImageController::class, 'index'])->name('category-images.index');
        Route::get('/category-images/{category}/edit', [App\Http\Controllers\CategoryImageController::class, 'edit'])->name('category-images.edit');
        Route::post('/category-images/{category}', [App\Http\Controllers\CategoryImageController::class, 'update'])->name('category-images.update');

        // Future Content Modules can be added here
        Route::get('/settings', function() { return "Elite Settings Registry - Coming Soon"; })->name('settings');

        // AI Generation & Multilingual Services
        Route::post('/ai/generate-description', [App\Http\Controllers\Admin\AdminAIController::class, 'generateDescription'])->name('ai.generate');


        // Payment Verification Mastery
        Route::get('/payment/verify', [App\Http\Controllers\Shared\PaymentVerificationController::class, 'index'])->name('payment.verify.index');
        Route::get('/payment/verify/search', [App\Http\Controllers\Shared\PaymentVerificationController::class, 'search'])->name('payment.verify.search');
        Route::post('/payment/verify/{order}', [App\Http\Controllers\Shared\PaymentVerificationController::class, 'verify'])->name('payment.verify.verify');

        // Billing Dashboard & Point of Sale (PoS)
        Route::get('/billing', [App\Http\Controllers\Admin\QuickBillingController::class, 'index'])->name('billing.index');
        Route::post('/billing/store', [App\Http\Controllers\Admin\QuickBillingController::class, 'store'])->name('billing.store');
        Route::get('/billing/verify/{bill_id}', [App\Http\Controllers\Admin\QuickBillingController::class, 'verifyPayment'])->name('billing.verify');
        Route::get('/billing/print/{id}', [App\Http\Controllers\Admin\QuickBillingController::class, 'print'])->name('billing.print');
    });


    // Supreme Admin Portal (Protected Routes - Only for Super Admin Tier)
    Route::get('/superadmin/check', function() { return "Superadmin prefix reachable"; });
    Route::prefix('superadmin')->name('superadmin.')->middleware('role:super_admin')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
        
        // Employee Access Management
        Route::get('/employees', [\App\Http\Controllers\Admin\AdminEmployeeController::class, 'index'])->name('employees.index');
        Route::get('/employees/create', [\App\Http\Controllers\Admin\AdminEmployeeController::class, 'create'])->name('employees.create');
        Route::post('/employees', [\App\Http\Controllers\Admin\AdminEmployeeController::class, 'store'])->name('employees.store');
        Route::patch('/employees/{employee}/toggle-block', [\App\Http\Controllers\Admin\AdminEmployeeController::class, 'toggleBlock'])->name('employees.toggle_block');
    });

    // Logistics Operations Portal (For the actual logistics personnel in the field)
    Route::prefix('logistics')->name('logistics.')->middleware('role:logistics')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Shared\LogisticsDashboardController::class, 'dashboard'])->name('dashboard');
        Route::patch('/orders/{token}/status', [\App\Http\Controllers\Shared\LogisticsDashboardController::class, 'updateDeliveryStatus'])->name('orders.update-status');
        
        // QR Secure Delivery Verification
        Route::get('/verify/{token}', [\App\Http\Controllers\Shared\LogisticsDashboardController::class, 'showVerifyDelivery'])->name('verify.show');
        Route::post('/verify/{token}', [\App\Http\Controllers\Shared\LogisticsDashboardController::class, 'processVerifyDelivery'])->name('verify.process');
        
        // Real-Time Geospatial Broadcast
        Route::post('/orders/{token}/location', [\App\Http\Controllers\Shared\LogisticsDashboardController::class, 'updateLocation'])->name('orders.update-location');
    });

    // Shared Portal (For Admins & Employees)
    Route::prefix('shared')->name('shared.')->middleware('role:super_admin,admin,employee')->group(function () {
        Route::get('/logistics/personnel', [\App\Http\Controllers\Shared\LogisticsPersonnelController::class, 'index'])->name('logistics.personnel.index');
        Route::get('/logistics/personnel/create', [\App\Http\Controllers\Shared\LogisticsPersonnelController::class, 'create'])->name('logistics.personnel.create');
        Route::post('/logistics/personnel', [\App\Http\Controllers\Shared\LogisticsPersonnelController::class, 'store'])->name('logistics.personnel.store');
        Route::patch('/logistics/personnel/{personnel}/toggle-block', [\App\Http\Controllers\Shared\LogisticsPersonnelController::class, 'toggleBlock'])->name('logistics.personnel.toggle_block');
    });

    // Employee Portal (Empowered Management Tier)
    Route::prefix('employee')->name('employee.')->middleware('role:admin,employee')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Employee\EmployeeDashboardController::class, 'dashboard'])->name('dashboard');
        
        // Payment Verification Hub (Elevated Priority)
        Route::get('/payment/verify/index', [App\Http\Controllers\Shared\PaymentVerificationController::class, 'index'])->name('payment.verify.index');
        Route::get('/payment/verify/search', [App\Http\Controllers\Shared\PaymentVerificationController::class, 'search'])->name('payment.verify.search');
        Route::post('/payment/verify/{order}', [App\Http\Controllers\Shared\PaymentVerificationController::class, 'verify'])->name('payment.verify.verify');
        Route::get('/payment/verify', function() { return redirect()->route('employee.payment.verify.index'); });

        // Dispatch Mastery (Logistics Core)
        Route::get('/dispatch', [\App\Http\Controllers\Employee\DispatchController::class, 'index'])->name('dispatch.index');
        Route::get('/dispatch/history', [\App\Http\Controllers\Employee\DispatchController::class, 'history'])->name('dispatch.history');
        Route::get('/dispatch/label/{order}', [\App\Http\Controllers\Employee\DispatchController::class, 'generateLabel'])->name('dispatch.label');

        // Core Sacred Catalogs
        Route::resource('categories', App\Http\Controllers\Employee\EmployeeCategoryController::class);
        Route::resource('products', App\Http\Controllers\Employee\EmployeeProductController::class);
        Route::resource('ritual-kits', App\Http\Controllers\Admin\AdminRitualKitController::class);
        
        // Order Master Hub
        Route::get('/orders', [App\Http\Controllers\Employee\EmployeeOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{token}', [App\Http\Controllers\Employee\EmployeeOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{token}/status', [App\Http\Controllers\Employee\EmployeeOrderController::class, 'updateStatus'])->name('orders.update-status');
        
        // Global Broadcast Network
        Route::get('/broadcasts', [App\Http\Controllers\Employee\EmployeeBroadcastController::class, 'index'])->name('broadcasts.index');
        Route::get('/broadcasts/create', [App\Http\Controllers\Employee\EmployeeBroadcastController::class, 'create'])->name('broadcasts.create');
        Route::post('/broadcasts', [App\Http\Controllers\Employee\EmployeeBroadcastController::class, 'store'])->name('broadcasts.store');
        Route::patch('/broadcasts/{broadcast}/toggle', [App\Http\Controllers\Employee\EmployeeBroadcastController::class, 'toggle'])->name('broadcasts.toggle');
        
        // HQ Restock Management
        Route::get('/restocks', [App\Http\Controllers\Employee\EmployeeRestockController::class, 'index'])->name('restocks.index');
        Route::patch('/restocks/{restock}', [App\Http\Controllers\Employee\EmployeeRestockController::class, 'update'])->name('restocks.update');
        
        // Category Image Management
        Route::get('/category-images', [App\Http\Controllers\CategoryImageController::class, 'index'])->name('category-images.index');
        Route::get('/category-images/{category}/edit', [App\Http\Controllers\CategoryImageController::class, 'edit'])->name('category-images.edit');
        Route::post('/category-images/{category}', [App\Http\Controllers\CategoryImageController::class, 'update'])->name('category-images.update');
    });

    // Elite Franchise Partner Portal (Protected Routes - Only for Franchise Tier)
    Route::prefix('franchise')->name('franchise.')->middleware('role:franchise')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Franchise\FranchiseController::class, 'dashboard'])->name('dashboard');
        Route::get('/catalog', [\App\Http\Controllers\Franchise\FranchiseController::class, 'catalog'])->name('catalog');
        Route::get('/orders/{token}', [\App\Http\Controllers\Franchise\FranchiseController::class, 'showOrder'])->name('orders.show');
        
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

        // Category Image Management
        Route::get('/category-images', [App\Http\Controllers\CategoryImageController::class, 'index'])->name('category-images.index');
        Route::get('/category-images/{category}/edit', [App\Http\Controllers\CategoryImageController::class, 'edit'])->name('category-images.edit');
        Route::post('/category-images/{category}', [App\Http\Controllers\CategoryImageController::class, 'update'])->name('category-images.update');

        // Partner Payment Verification
        Route::get('/payment/verify', [App\Http\Controllers\Shared\PaymentVerificationController::class, 'index'])->name('payment.verify.index');
        Route::get('/payment/verify/search', [App\Http\Controllers\Shared\PaymentVerificationController::class, 'search'])->name('payment.verify.search');
        Route::post('/payment/verify/{order}', [App\Http\Controllers\Shared\PaymentVerificationController::class, 'verify'])->name('payment.verify.verify');
    });

    // Shared Trade Artifact Registry
    Route::middleware(['auth'])->group(function () {
        Route::get('/orders/{token}/invoice', [\App\Http\Controllers\InvoiceController::class, 'download'])->name('orders.invoice');
    });
});

