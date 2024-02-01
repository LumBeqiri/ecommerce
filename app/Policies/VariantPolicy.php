<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Variant;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class VariantPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if ($user->hasRole('vendor')) {
            return Response::allow();
        }

        return $user->hasPermissionTo('create-products')
        ? Response::allow()
        : Response::deny('You do not have permission to update this variant.');

    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Variant $variant)
    {

        if ($user->hasRole('vendor')) {
            // Vendor can update own variants
            return $user->id === $variant->product->vendor->user_id
                ? Response::allow()
                : Response::deny('You do not own this variant.');
        }

        // For staff members
        return $user->hasPermissionTo('update-products') && $user->staff->vendor_id === $variant->product->vendor_id
            ? Response::allow()
            : Response::deny('You do not have permission to update this variant.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Variant $variant)
    {

        if ($user->hasRole('vendor')) {
            // Vendor can update own variants
            return $user->id === $variant->product->vendor->user_id
                ? Response::allow()
                : Response::deny('You do not own this variant.');
        }

        return $user->hasPermissionTo('delete-products') && $user->staff->vendor_id == $variant->product->vendor_id
        ? Response::allow()
        : Response::deny('You do not own this product.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Variant $variant)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Variant $variant)
    {
        //
    }
}
