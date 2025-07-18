<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class CartController extends Controller
{
    use ApiResponse, AuthorizesRequests;
    public function index()
{
    $user = Auth::user();
    // Admin can view all carts
    if ($user->isAdmin) {
        $carts = Cart::with('items.product')->get();

        return ApiResponse::success([
            'carts' => $carts,
        ]);
    }

    // Regular user: show only their cart
    $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

    if (!$cart) {
        return self::notFound('Cart not found.');
    }

    $this->authorize('view', $cart);

    return ApiResponse::success([
        'cart' => $cart,
        'subtotal' => $cart->subtotal(),
    ]);
}


    // Create or retrieve cart for authenticated user
    public function store()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()], ['subtotal' => 0]);

        return self::success($cart, 'Cart created or retrieved.');
    }

    public function show(Cart $cart)
    {
        
        $user = Auth::user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

    if (!$cart) {
        return self::success([
            'cart' => [],
            'subtotal' => 0,
        ]);
    }

    $this->authorize('view', $cart);

    return self::success([
        'cart' => $cart,
        'subtotal' => $cart->subtotal(),
    ]);
    }


    public function destroy(Cart $cart)
    {
    $this->authorize('delete', $cart);
    if ($cart->user_id !== Auth::id() && !Auth::user()->isAdmin) {
        return self::forbidden();
    }
    // Restore product stock
    foreach ($cart->items as $item) {
        $item->product->increment('stock', $item->quantity);
    }

    $cart->items()->delete();   
    $cart->subtotal = 0;
    $cart->save();

    // Now delete the cart
    $cart->delete();

    return self::deleted('Cart deleted successfully and product stock restored.');
}

}
