<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): Response
    {
        if ($user->hasRole('admin')) {
            return Response::allow();
        }

        return $user->hasAnyRole(['buyer', 'vendor', 'staff'])
            ? Response::allow()
            : Response::deny('You do not have permission to view orders.');
    }

    public function view(User $user, Order $order): Response
    {
        if ($user->hasRole('admin')) {
            return Response::allow();
        }

        if ($user->hasRole('buyer') && $order->buyer_id === $user->buyer->id) {
            return Response::allow();
        }

        if ($user->hasRole('vendor')) {
            $hasOrderItems = $order->order_items()
                ->whereHas('variant.product', function ($query) use ($user) {
                    $query->where('vendor_id', $user->vendor->id);
                })
                ->exists();

            return $hasOrderItems
                ? Response::allow()
                : Response::deny('This order does not contain any of your products.');
        }

        return $user->hasPermissionTo('view-orders')
            ? Response::allow()
            : Response::deny('You do not have permission to view this order.');
    }

    public function update(User $user, Order $order): Response
    {
        if ($user->hasRole('admin')) {
            return Response::allow();
        }

        if ($user->hasRole('staff') && $user->hasPermissionTo('update-orders')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to update this order.');
    }

    public function delete(User $user, Order $order): Response
    {
        if ($user->hasRole('admin')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to delete orders.');
    }
} 