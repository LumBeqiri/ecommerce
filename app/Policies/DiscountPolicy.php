<?php

namespace App\Policies;

use App\Models\Discount;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DiscountPolicy
{
    public function viewAny(User $user): Response
    {
        if ($user->hasRole('admin')) {
            return Response::allow();
        }

        if ($user->hasRole('vendor')) {
            return Response::allow();
        }

        if ($user->hasRole('staff') && $user->hasPermissionTo('view-discounts')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view discounts.');
    }

    public function view(User $user, Discount $discount): Response
    {
        if ($user->hasRole('admin')) {
            return Response::allow();
        }

        if ($user->hasRole('vendor') && $discount->vendor_id === $user->vendor->id) {
            return Response::allow();
        }

        if ($user->hasRole('staff') && 
            $user->hasPermissionTo('view-discounts') && 
            $user->staff->vendor_id === $discount->vendor_id
        ) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view this discount.');
    }

    public function create(User $user): Response
    {
        if ($user->hasRole('admin')) {
            return Response::allow();
        }

        if ($user->hasRole('vendor')) {
            return Response::allow();
        }

        if ($user->hasRole('staff') && $user->hasPermissionTo('create-discounts')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to create discounts.');
    }

    public function update(User $user, Discount $discount): Response
    {
        if ($user->hasRole('admin')) {
            return Response::allow();
        }

        if ($user->hasRole('vendor') && $discount->vendor_id === $user->vendor->id) {
            return Response::allow();
        }

        if ($user->hasRole('staff') && 
            $user->hasPermissionTo('update-discounts') && 
            $user->staff->vendor_id === $discount->vendor_id
        ) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to update this discount.');
    }

    public function delete(User $user, Discount $discount): Response
    {
        if ($user->hasRole('admin')) {
            return Response::allow();
        }

        if ($user->hasRole('vendor') && $discount->vendor_id === $user->vendor->id) {
            return Response::allow();
        }

        if ($user->hasRole('staff') && 
            $user->hasPermissionTo('delete-discounts') && 
            $user->staff->vendor_id === $discount->vendor_id
        ) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to delete this discount.');
    }
} 