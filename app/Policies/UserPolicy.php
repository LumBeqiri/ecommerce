<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $authenticatedUser, User $targetUser): Response
    {
        if ($authenticatedUser->hasRole('admin')) {
            return Response::allow();
        }

        // Users can view their own profile
        if ($authenticatedUser->id === $targetUser->id) {
            return Response::allow();
        }

        return $authenticatedUser->hasPermissionTo('view-users')
            ? Response::allow()
            : Response::deny('You do not have permission to view this user.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $authenticatedUser): Response
    {
        return $authenticatedUser->hasRole('admin') || $authenticatedUser->hasPermissionTo('create-users')
            ? Response::allow()
            : Response::deny('You do not have permission to create users.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $authenticatedUser, User $targetUser): Response
    {
        if ($authenticatedUser->hasRole('admin')) {
            return Response::allow();
        }

        // Users can update their own profile
        if ($authenticatedUser->id === $targetUser->id) {
            return Response::allow();
        }

        return $authenticatedUser->hasPermissionTo('update-users')
            ? Response::allow()
            : Response::deny('You do not have permission to update this user.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $authenticatedUser, User $targetUser): Response
    {
        if ($authenticatedUser->hasRole('admin')) {
            return Response::allow();
        }

        // Prevent users from deleting themselves
        if ($authenticatedUser->id === $targetUser->id) {
            return Response::deny('You cannot delete your own account.');
        }

        return $authenticatedUser->hasPermissionTo('delete-users')
            ? Response::allow()
            : Response::deny('You do not have permission to delete users.');
    }

    public function viewAny(User $authenticatedUser): Response
    {
        return $authenticatedUser->hasRole('admin') || $authenticatedUser->hasPermissionTo('view-users')
            ? Response::allow()
            : Response::deny('You do not have permission to view users.');
    }
} 