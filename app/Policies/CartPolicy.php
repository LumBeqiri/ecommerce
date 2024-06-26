<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CartPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function manageCart(User $user, Cart $cart)
    {
        if ($user->isVendor() && $user->vendor->id == $cart->vendor_id) {
            return Response::allow();
        }

        if ($user->isStaff() && $user->hasPermissionTo('view-cart') && $user->staff->vendor->id == $cart->vendor_id) {
            return Response::allow();
        }

        if ($user->isBuyer() && $user->buyer->id == $cart->buyer_id) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view this cart.');
    }
}
