<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Favourite;

class FavouritePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(User $user, Favourite $favourite): bool
    {
        return $user->isAdmin || $user->id === $favourite->user_id;
    }

    public function create(User $user): bool
    {
        return !$user->isAdmin;
    }


    public function viewAll(User $user): bool
    {
        return $user->isAdmin;
    }
}
