<?php

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

if (! function_exists('is_added')) {
    function is_added($productVariantId)
    {
        if (! session()->has('cart')) {
            if (Auth::check()) {
                $user = Auth::id();
                $cart = Cart::where('user_id', $user)->pluck('product_variant_id')->toArray();
                session()->put('cart', $cart);
            } else {
                $sessionId = session()->getId();
                $cart = Cart::where('session_id', $sessionId)->pluck('product_variant_id')->toArray();
                session()->put('cart', $cart);
            }
        }

        return session()->has('cart') ? in_array($productVariantId, session()->get('cart')) : false;
    }
}

if (! function_exists('getInitials')) {
    function getInitials($name)
    {
        $name = trim($name);
        $nameParts = explode(' ', $name);

        if (count($nameParts) >= 2) {
            // First letter of first name + first letter of last name
            return strtoupper(
                substr($nameParts[0], 0, 1).
                substr($nameParts[count($nameParts) - 1], 0, 1)
            );
        }

        // If single name, return first two letters
        return strtoupper(substr($name, 0, 2));
    }
}
