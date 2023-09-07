<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;

class AdminOrderController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->showAll(new OrderResource(Order::all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return $this->showOne(new OrderResource($order));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order_data = $request->validated();

        $order->update($order_data);
        $order->save();

        return $this->showOne(new OrderResource($order));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return $this->showMessage('Order Deleted Successfully!');
    }
}
