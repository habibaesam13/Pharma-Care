<?php

namespace App\Policies;

use App\Models\User;
use App\Models\RequestService;
use Illuminate\Auth\Access\Response;

class RequestServicePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RequestService $request_service): bool
    {
        return $user->isAdmin || $user->id === $request_service->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return !$user->isAdmin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user,  RequestService $request_service): bool
    {
        return  $user->id === $request_service->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RequestService $request_service): bool
    {
        return $user->isAdmin || $user->id === $request_service->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user,  RequestService $request_service): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user,  RequestService $request_service): bool
    {
        return false;
    }
}
