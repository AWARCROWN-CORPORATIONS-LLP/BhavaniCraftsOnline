<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        try {
            $request->validate([
                'coupon_code' => 'required|string|max:50',
            ]);

            $coupon = \App\Models\Coupon::where('code', $request->coupon_code)->first();

            if (!$coupon) {
                return response()->json(['error' => 'Invalid coupon code. Please try again.'], 404);
            }

            $userId = \Auth::id();

            // Rule: Check if user has already used this coupon
            if (!$coupon->isUserEligible($userId)) {
                return response()->json(['error' => 'You have already used this sacred coupon.'], 400);
            }

            // Calculate current subtotal to validate min_order
            $subtotal = \App\Models\CartItem::where('cart_items.user_id', $userId)
                ->join('products', 'cart_items.product_id', '=', 'products.id')
                ->sum(\DB::raw('products.price * cart_items.quantity')) ?: 0;

            if (!$coupon->isValid($subtotal)) {
                $msg = 'This coupon is not valid for your order.';
                if ($subtotal < $coupon->min_order_amount) {
                    $msg = 'Minimum order amount for this blessing is ₹' . number_format($coupon->min_order_amount);
                } elseif ($coupon->expires_at && now()->isAfter($coupon->expires_at)) {
                    $msg = 'This coupon has faded from the universe (expired).';
                } elseif ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
                    $msg = 'This blessing has reached its maximum potency (usage limit).';
                }
                return response()->json(['error' => $msg], 400);
            }

            // Store in session
            session(['applied_coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'discount_amount' => $coupon->calculateDiscount($subtotal)
            ]]);

            return response()->json([
                'success' => 'Divine blessing applied successfully.',
                'discount_amount' => session('applied_coupon.discount_amount')
            ]);
        } catch (\Exception $e) {
            // For debugging 500 errors in development, returning the error
            return response()->json(['error' => 'Ritual failed: ' . $e->getMessage()], 500);
        }
    }

    public function remove()
    {
        session()->forget('applied_coupon');
        return response()->json(['success' => 'Divine blessing retracted.']);
    }
}
