<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Product $product)
    {

        return $user->hasPermissionTo('view-products') && $user->staff->vendor_id == $product->vendor->user_id
        ? Response::allow()
        : Response::deny('You do not own this product.');
    }

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
    public function update(User $user, Product $product)
    {

        if ($user->hasRole('vendor')) {
            // Vendor can update own variants
            return $user->id === $product->vendor->user_id
                ? Response::allow()
                : Response::deny('You do not own this variant.');
        }

        // For staff members
        return $user->hasPermissionTo('update-products') && $user->staff->vendor_id === $product->vendor_id
            ? Response::allow()
            : Response::deny('You do not have permission to update this product.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Product $product)
    {
        if ($user->hasRole('vendor')) {
            // Vendor can update own variants
            return $user->id === $product->vendor->user_id
                ? Response::allow()
                : Response::deny('You do not own this product.');
        }

        // For staff members
        return $user->hasPermissionTo('delete-products') && $user->staff->vendor_id === $product->vendor_id
            ? Response::allow()
            : Response::deny('You do not have permission to delete this product.');
    }
}
