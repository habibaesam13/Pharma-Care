<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CartPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, Cart $cart): bool
    {
        return $cart->user_id === $user->id || $user->isAdmin;
    }
        public function create(User $user, Cart $cart): bool
{
    return $user->id === $cart->user_id ;
}

    public function update(User $user, Cart $cart): bool
    {
        return $cart->user_id === $user->id ;
    }

    public function delete(User $user, Cart $cart): bool
    {
        return $cart->user_id === $user->id ;
    }
}
