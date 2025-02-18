<?php

namespace App\Http\Controllers\Order;

use Exception;
use App\Models\User;
use App\Models\Order;
use App\values\OrderStatusTypes;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\OrderResource;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Request;

class OrderController extends ApiController
{
    protected User $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Order::class);

        $orders = Order::with(['order_items.variant', 'currency'])
            ->when($this->user->hasRole('buyer'), function ($query) {
                $query->where('buyer_id', $this->user->buyer->id);
            })
            ->when($this->user->hasRole('vendor'), function ($query) {
                $query->whereHas('order_items.variant.product', function ($q) {
                    $q->where('vendor_id', $this->user->vendor->id);
                });
            })
            ->latest('ordered_at')
            ->get();

        return $this->showAll(OrderResource::collection($orders));
    }

    public function show(Order $order): JsonResponse
    {
        $this->authorize('view', $order);

        return $this->showOne(
            new OrderResource($order->load(['order_items.variant', 'currency']))
        );
    }

    public function update(Request $request, Order $order): JsonResponse
    {
        $this->authorize('update', $order);

        $request->validate([
            'status' => 'required|string|in:'.OrderStatusTypes::cases(),
        ]);

        DB::beginTransaction();
        try{
            $order->update([
                'status' => $request->input('status'),
            ]);
    
            $order->vendor_orders()->update([
                'status' => $request->input('status'),
            ]);
            
            DB::commit();
        }catch(Exception $ex){
            DB::rollBack();
            return $this->showMessage($ex->getMessage());
        }

        // TODO: 
        // send email to customer with new order status
        // make sure its not send if transaction above fails

        return $this->showOne(new OrderResource($order));
    }

    public function destroy(Order $order): JsonResponse
    {
        $this->authorize('delete', $order);


        DB::beginTransaction();
        try{
            $order->vendor_orders()->delete();
            $order->delete();
            
            DB::commit();
        }catch(Exception $ex){
            DB::rollBack();
            return $this->showMessage($ex->getMessage());
        }

        return $this->showMessage('Order deleted successfully');
    }
} 