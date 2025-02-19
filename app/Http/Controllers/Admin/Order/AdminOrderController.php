<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminOrderController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return $this->showAll(OrderResource::collection(Order::paginate($this->paginate_count)));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): JsonResponse
    {
        return $this->showOne(new OrderResource($order));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order): JsonResponse
    {
        $order_data = $request->validated();

        $order->update($order_data);
        $order->save();

        return $this->showOne(new OrderResource($order));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): JsonResponse
    {
        DB::begintransaction();
        try {
            $order->order_items()->delete();
            $order->vendor_orders()->delete();
            $order->delete();
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            Log::info($ex->getMessage());

            return $this->showError($ex->getMessage(), $ex->getCode());
        }

        return $this->showMessage('Order Deleted Successfully!');
    }
}
