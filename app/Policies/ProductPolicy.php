<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {

    }
        public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Product $product): bool
    {
        return true;
    }

    /**
     * Only admin can create.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin ;
    }

    /**
     * Only admin can update.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->isAdmin;
    }

    /**
     * Only admin can delete.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->isAdmin ;
    }

    public function restore(User $user, Product $product): bool
    {
        return false;
    }

    public function forceDelete(User $user, Product $product): bool
    {
        return false;
    }
    
}
