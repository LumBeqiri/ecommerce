<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class VariantPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Variant $variant)
    {
        if ($user->hasRole('vendor') && $user->id === $variant->product->vendor->user_id) {
            return Response::allow();
        }

        if ($user->hasRole('staff') && $user->staff->vendor_id === $variant->product->vendor_id) {
            return Response::allow();
        }

        return Response::deny('You do not own this variant.');
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Product $product)
    {
        return ($user->hasPermissionTo('create-products') && $user->id === $product->vendor->user_id)
                ? Response::allow()
                : Response::deny('You do not own this variant.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Variant $variant)
    {
        if ($user->hasRole('vendor')) {
            return $user->id === $variant->product->vendor->user_id
                ? Response::allow()
                : Response::deny('You do not own this variant.');
        }

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
            return $user->id === $variant->product->vendor->user_id
                ? Response::allow()
                : Response::deny('You do not own this variant.');
        }

        return $user->hasPermissionTo('delete-products') && $user->staff->vendor_id == $variant->product->vendor_id
        ? Response::allow()
        : Response::deny('You do not own this product.');
    }
}
