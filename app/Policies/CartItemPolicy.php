<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;
use App\Models\CartItem;

class CartItemPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
        public function create(User $user, Cart $cart): bool
    {
        return $user->id === $cart->user_id ;
    }

    public function view(User $user, CartItem $cartItem): bool
    {
        return $cartItem->cart->user_id === $user->id || $user->isAdmin;
    }

    public function update(User $user, CartItem $cartItem): bool
    {
        return $cartItem->cart->user_id === $user->id;
    }

    public function delete(User $user, CartItem $cartItem): bool
    {
        return $cartItem->cart->user_id === $user->id;
    }
}
