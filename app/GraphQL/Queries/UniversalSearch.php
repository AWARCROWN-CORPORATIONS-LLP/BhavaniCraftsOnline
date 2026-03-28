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
        $products = Product::where('product_name', 'LIKE', "%{$q}%")
            ->orWhere('product_code', 'LIKE', "%{$q}%")
            ->orWhere('slug', 'LIKE', "%{$q}%")
            ->take(5)
            ->get();

        foreach ($products as $product) {
            $results[] = [
                'title' => $product->product_name,
                'subtitle' => $product->product_code,
                'type' => 'Product',
                'url' => route('public.products.show', $product->slug),
                'image' => $product->display_image,
            ];
        }

        // 2. Search Categories
        $categories = Category::where('name', 'LIKE', "%{$q}%")
            ->orWhere('slug', 'LIKE', "%{$q}%")
            ->take(3)
            ->get();

        foreach ($categories as $category) {
            $results[] = [
                'title' => $category->name,
                'subtitle' => 'Category',
                'type' => 'Category',
                'url' => route('public.categories.show', $category->slug),
                'image' => $category->display_image,
            ];
        }

        // 3. Search Orders (Staff only)
        if (Auth::check() && in_array(Auth::user()->user_type, ['superadmin', 'admin', 'employee', 'franchise'])) {
            $orders = Order::where('order_id_string', 'LIKE', "%{$q}%")
                ->orWhere('razorpay_order_id', 'LIKE', "%{$q}%")
                ->orWhereHas('user', function($u) use ($q) {
                    $u->where('email', 'LIKE', "%{$q}%")->orWhere('phone', 'LIKE', "%{$q}%");
                })
                ->with('user')
                ->take(5)
                ->get();

            foreach ($orders as $order) {
                // Determine redirect route based on user role
                $routeNamespace = 'employee';
                if (Auth::user()->user_type === 'superadmin' || Auth::user()->user_type === 'admin') {
                    $routeNamespace = 'admin';
                } elseif (Auth::user()->user_type === 'franchise') {
                    $routeNamespace = 'franchise';
                }
                
                // Note: Check if the route exists for the namespace. 
                // Currently: admin.orders.show, employee.orders.show exist.
                // Franchise does not have a dedicated orders.show yet, but could be added.
                $routeName = $routeNamespace . '.orders.show';
                
                $results[] = [
                    'title' => 'Order #' . ($order->order_id_string ?? $order->id),
                    'subtitle' => $order->user->email ?? 'Guest Order',
                    'type' => 'Order',
                    'url' => route($routeName, $order->encryptedId()),
                    'image' => null,
                ];
            }
        }

        return $results;
    }
}
