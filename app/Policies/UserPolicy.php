<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    protected const PAGE_NAME=("User");

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return User::permissions(self::PAGE_NAME,"view");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return User::permissions(self::PAGE_NAME,"view");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return User::permissions(self::PAGE_NAME,"create");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return User::permissions(self::PAGE_NAME,"update");

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteAny(User $user): bool
    {
        return User::permissions(self::PAGE_NAME,"delete");

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restoreAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
