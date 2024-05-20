<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GenericPolicy
{
    use HandlesAuthorization;
    public  $pageName = "";

    public function before($user, $ability, $model)
    {
        // dd($ability);
        if(gettype($model)!=="string")
        $model = get_class($model);
        $modelNameArray = explode('\\', $model);
        $modelName = end($modelNameArray);
        // dd($modelName);
        $this->pageName = strtolower($modelName);
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return User::permissions($this->pageName, "view")||$user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return User::permissions($this->pageName, "view")||$user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return User::permissions($this->pageName, "create")||$user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {

        return User::permissions($this->pageName, "update")||$user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteAny(User $user): bool
    {
        return User::permissions($this->pageName, "delete")||$user->isAdmin();
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
