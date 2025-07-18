<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Policies\CartItemPolicy;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class CartItemController extends Controller
{
    use  AuthorizesRequests;
    // List all items in the user's cart
    public function index()
    {
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();
        if (!$cart || $cart->items->isEmpty()) {
            return ApiResponse::notFound('Your cart is empty.');
        }
        return ApiResponse::success($cart->items, 'Cart items retrieved.');
    }

    // Add or update cart item
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);

        // Find or create user's cart
        $cart = Cart::firstOrCreate(['user_id' => $user->id],['subtotal' => 0]);
        $this->authorize('create',  $cart);
        // Check if item already exists in the cart
        $existingItem = $cart->items()->where('product_id', $product->id)->first();
        $newQuantity = $existingItem
            ? $existingItem->quantity + $request->quantity
            : $request->quantity;

        // Check product stock
        if ($product->stock < $newQuantity) {
            return ApiResponse::error("Only {$product->stock} unit(s) available in stock.", 400);
        }

        // Create or update cart item
        $cartItem = $cart->items()->updateOrCreate(
            ['product_id' => $product->id],
            [
                'quantity' => $newQuantity,
                'price' => $product->price_after_discount,
            ]
        );

        // Reduce stock accordingly
        $product->decrement('stock', $request->quantity);

        // Refresh cart subtotal if you calculate it in a model accessor
        $cart->refresh();

        return ApiResponse::created([
            'item' => $cartItem->load('product'),
            'cart_subtotal' => $cart->subtotal(),
        ], 'Item added or updated in cart.');
    }

    // Show a specific cart item
    public function show(CartItem $cartItem)
    {
        $this->authorize('view', $cartItem);
        if ($cartItem->cart->user_id !== Auth::id()) {
            return ApiResponse::forbidden();
        }

        return ApiResponse::success($cartItem->load('product'), 'Cart item retrieved.');
    }

    // Update cart item quantity
    public function update(Request $request, CartItem $cartItem)
    {
        //dd($cartItem);
        $this->authorize('update', $cartItem);
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = $cartItem->product;
        $currentQuantity = $cartItem->quantity;
        $newQuantity = $request->quantity;

        // If increasing, check stock
        if ($newQuantity > $currentQuantity) {
            $increase = $newQuantity - $currentQuantity;

            if ($product->stock < $increase) {
                return ApiResponse::error("Only {$product->stock} more unit(s) available.", 400);
            }

            $product->decrement('stock', $increase);
        } else {
            // If decreasing, increase stock
            $product->increment('stock', $currentQuantity - $newQuantity);
        }

        $cartItem->update(['quantity' => $newQuantity]);

        $cartItem->cart->refresh(); // for updated subtotal

        return ApiResponse::success([
            'item' => $cartItem->load('product'),
            'cart_subtotal' => $cartItem->cart->subtotal(),
        ], 'Cart item updated.');
    }



public function updateByProduct(Request $request, $product_id)
{
    $request->validate([
        'quantity' => 'required|integer|min:1',
    ]);

    $user = Auth::user();


    $cart = Cart::where('user_id', $user->id)->first();

    if (!$cart) {
        return ApiResponse::notFound('Cart not found.');
    }

    $cartItem = $cart->items()->where('product_id', $product_id)->first();

    if (!$cartItem) {
        return ApiResponse::notFound("Item with product ID $product_id not found in your cart.");
    }

    $this->authorize('update', $cartItem); 

    $product = $cartItem->product;
    $currentQuantity = $cartItem->quantity;
    $newQuantity = $request->quantity;

    if ($newQuantity > $currentQuantity) {
        $increase = $newQuantity - $currentQuantity;

        if ($product->stock < $increase) {
            return ApiResponse::error("Only {$product->stock} more unit(s) available.", 400);
        }

        $product->decrement('stock', $increase);
    } else {
        $product->increment('stock', $currentQuantity - $newQuantity);
    }

    $cartItem->update(['quantity' => $newQuantity]);
    $cart->refresh();

    return ApiResponse::success([
        'item' => $cartItem->load('product'),
        'cart_subtotal' => $cart->subtotal(),
    ], 'Cart item updated by product.');
}



    // Remove item from cart
    public function destroy(CartItem $cartItem)
    {
        //dd($cartItem);
        $this->authorize('delete', $cartItem);
        $product = $cartItem->product;
        // Restore stock before deleting
        $product->increment('stock', $cartItem->quantity);
        $cartItem->delete();
        return ApiResponse::deleted('Cart item removed and stock restored.');
    }

    public function destroyByProduct($product_id)
{
    $cart = Cart::where('user_id', Auth::id())->first();

    $cartItem = $cart?->items()->where('product_id', $product_id)->first();

    if (!$cartItem) {
        return ApiResponse::notFound("Item with product ID $product_id not found in your cart.");
    }

    $this->authorize('delete', $cartItem);

    $cartItem->product->increment('stock', $cartItem->quantity);
    $cartItem->delete();

    return ApiResponse::deleted('Cart item removed.');
}

}
