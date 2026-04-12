<?php

namespace App\GraphQL\Queries;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class UniversalSearch
{
    public function __invoke($_, array $args)
    {
        $q = $args['q'];
        $results = [];

        // 1. Search Products
        $products = Product::whereFullText(['product_name', 'product_code', 'slug'], $q)
            ->orWhereFullText(['product_name', 'product_code', 'slug'], $q) // Note: Laravel 9+ whereFullText handles OR with additional whereFullText or manually
            ->take(5)
            ->get();
        
        // Fallback for partial matches if needed (optional, FTS is good)
        if ($products->isEmpty()) {
            $products = Product::where('product_name', 'LIKE', "%{$q}%")
                ->orWhere('product_code', 'LIKE', "%{$q}%")
                ->take(5)
                ->get();
        }

        foreach ($products as $product) {
            $results[] = [
                'title' => $product->product_name,
                'subtitle' => $product->product_code,
                'type' => 'Product',
                'url' => route('artifact.show', ['locale' => app()->getLocale(), 'slug' => $product->slug]),
                'image' => $product->display_image,
            ];
        }

        // 2. Search Categories
        $categories = Category::whereFullText(['name', 'slug'], $q)
            ->take(3)
            ->get();
        
        if ($categories->isEmpty()) {
            $categories = Category::where('name', 'LIKE', "%{$q}%")->take(3)->get();
        }

        foreach ($categories as $category) {
            $results[] = [
                'title' => $category->name,
                'subtitle' => 'Category',
                'type' => 'Category',
                'url' => route('collection.show', ['locale' => app()->getLocale(), 'token' => $category->slug]),
                'image' => $category->display_image,
            ];
        }

        // 3. Search Orders (Administrative access only)
        if (Auth::check()) {
            $user = Auth::user();
            $isAdmin = $user->hasRole('super_admin') || $user->hasRole('admin');
            $isPersonnel = $user->hasRole('employee') || $user->hasRole('franchise') || $user->hasRole('logistics');

            if ($isAdmin || $isPersonnel) {
                // Search Online Orders via Full-Text
                $orders = Order::whereFullText(['order_id_string', 'razorpay_order_id', 'razorpay_payment_id'], $q)
                    ->orWhereHas('user', function($u) use ($q) {
                        $u->where('name', 'LIKE', "%{$q}%")
                          ->orWhere('email', 'LIKE', "%{$q}%")
                          ->orWhere('phone', 'LIKE', "%{$q}%");
                    })
                    ->with('user')
                    ->take(5)
                    ->get();
    
                if ($orders->isEmpty()) {
                     $orders = Order::where('order_id_string', 'LIKE', "%{$q}%")
                        ->orWhereHas('user', function($u) use ($q) {
                            $u->where('name', 'LIKE', "%{$q}%");
                        })
                        ->with('user')
                        ->take(5)
                        ->get();
                }

                foreach ($orders as $order) {
                    $routeNamespace = $isAdmin ? 'admin' : 'employee';
                    $matchedInfo = $order->user->name ?? 'Guest Member';
                    if ($order->razorpay_payment_id && str_contains(strtolower($order->razorpay_payment_id), strtolower($q))) {
                        $matchedInfo = 'TXN: ' . $order->razorpay_payment_id;
                    }

                    $results[] = [
                        'title' => 'Order #' . ($order->order_id_string ?? $order->id),
                        'subtitle' => $matchedInfo . ' (' . $order->status . ')',
                        'type' => 'Online Order',
                        'url' => route($routeNamespace . '.orders.show', ['locale' => app()->getLocale(), 'order' => $order->encryptedId()]),
                        'image' => null,
                    ];
                }

                // Search Quick Bills (Store Sales) Full-Text
                $quickBills = \App\Models\QuickBill::whereFullText(['bill_number', 'customer_name', 'customer_phone'], $q)
                    ->take(3)
                    ->get();
                
                if ($quickBills->isEmpty()) {
                   $quickBills = \App\Models\QuickBill::where('bill_number', 'LIKE', "%{$q}%")
                        ->orWhere('customer_name', 'LIKE', "%{$q}%")
                        ->take(3)
                        ->get();
                }

                foreach ($quickBills as $bill) {
                    $results[] = [
                        'title' => 'Bill #' . $bill->bill_number,
                        'subtitle' => ($bill->customer_name ?? 'Walk-in') . ' (₹' . number_format($bill->total_amount, 2) . ')',
                        'type' => 'Retail Bill',
                        'url' => route('admin.billing.dashboard', ['locale' => app()->getLocale()]),
                        'image' => null,
                    ];
                }
            }
        }

        return $results;
    }
}
