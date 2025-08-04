<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Prescription;

class PrescriptionPolicy
{
    /**
     * Determine whether the user can view any prescriptions.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin;
    }

    /**
     * Determine whether the user can view a specific prescription.
     */
    public function view(User $user, Prescription $prescription): bool
    {
        return $user->isAdmin || $user->id === $prescription->patient_id;
    }

    /**
     * Determine whether the user can create a prescription.
     */
    public function create(User $user): bool
    {
        return !$user->isAdmin;
    }

    /**
     * Determine whether the user can update the prescription.
     */
    public function update(User $user, Prescription $prescription): bool
    {
        return $user->isAdmin || $user->id === $prescription->patient_id;
    }

    /**
     * Determine whether the user can delete the prescription.
     */
    public function delete(User $user, Prescription $prescription): bool
    {
        return $user->isAdmin || $user->id === $prescription->patient_id;
    }
}