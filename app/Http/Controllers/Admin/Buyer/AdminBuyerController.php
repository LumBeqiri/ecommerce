<?php

namespace App\Http\Controllers\Admin\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\UpdateBuyerRequest;
use App\Http\Resources\BuyerResource;
use App\Models\Buyer;
use Illuminate\Http\JsonResponse;

class AdminBuyerController extends ApiController
{
    public function index(): JsonResponse
    {
        return $this->showAll(BuyerResource::collection(Buyer::all()));
    }

    public function show(Buyer $buyer): JsonResponse
    {
        return $this->showOne(new BuyerResource($buyer));
    }

    public function update(UpdateBuyerRequest $request, Buyer $buyer): JsonResponse
    {
        $buyer->update($request->validated());

        return $this->showOne(new BuyerResource($buyer));
    }

    public function destroy(Buyer $buyer): JsonResponse
    {
        $buyer->delete();

        return $this->showMessage('User deleted successfully!');
    }
}
