<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'cart_total' => 'required|numeric'
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return response()->json(['error' => 'Cupón inválido o inactivo'], 404);
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return response()->json(['error' => 'Este cupón ha expirado'], 400);
        }

        $discount = 0;
        if ($coupon->discount_type === 'percentage') {
            $discount = $request->cart_total * ($coupon->discount_value / 100);
        } else {
            $discount = $coupon->discount_value;
        }

        // Avoid negative totals
        $discount = min($discount, $request->cart_total);
        $newTotal = $request->cart_total - $discount;

        return response()->json([
            'valid' => true,
            'code' => $coupon->code,
            'discount' => round($discount),
            'new_total' => round($newTotal),
            'message' => 'Cupón aplicado con éxito'
        ]);
    }
}
