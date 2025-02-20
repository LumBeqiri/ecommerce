<?php

namespace App\Http\Controllers\Admin\Discount;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Discount\UpdateDiscountRequest;
use App\Http\Resources\DiscountResource;
use App\Models\Discount;
use App\Models\Region;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AdminDiscountController extends ApiController
{
    public function index(): JsonResponse
    {
        $discounts = Discount::with(['discount_rule'])->get();
        $discounts = Discount::all();

        return $this->showAll(DiscountResource::collection($discounts));
    }

    public function show(Discount $discount): JsonResponse
    {
        return $this->showOne(new DiscountResource($discount->load('discount_rule')));
    }

    public function update(UpdateDiscountRequest $request, Discount $discount): JsonResponse
    {
        $request->validated();

        DB::transaction(function () use ($request, $discount) {
            $region = Region::where('ulid', $request->region_id)->firstOrFail();

            if ($request->has('description')) {
                $discount->discount_rule()->update([
                    'description' => $request->description,
                    'value' => $request->value,
                    'region_id' => $region->id,
                ]);
            }

            $discount->fill($request->only([
                'code',
                'is_dynamic',
                'is_disabled',
                'parent_id',
                'starts_at',
                'ends_at',
                'usage_limit',
                'usage_count',
            ]));
            $discount->save();
        });

        return $this->showOne(new DiscountResource($discount));
    }

    public function destroy(Discount $discount): JsonResponse
    {
        $discount->discount_rule->delete();
        $discount->delete();
        
        return $this->showMessage('Discount deleted successfully!');
    }

}
