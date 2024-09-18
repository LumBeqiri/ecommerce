<?php

namespace App\Http\Controllers\Admin\Discount;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Discount\DiscountRequest;
use App\Http\Requests\Discount\UpdateDiscountRequest;
use App\Http\Resources\DiscountResource;
use App\Models\Discount;
use App\Models\DiscountRule;
use App\Models\Product;
use App\Models\Region;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DiscountController extends ApiController
{
    public function index(): JsonResponse
    {
        $discounts = Discount::with(['discount_rule.products', 'discount_rule.customer_groups'])->get();

        return $this->showAll(DiscountResource::collection($discounts));
    }

    public function store(DiscountRequest $request): JsonResponse
    {
        $request->validated();

        if ($this->validate_code($request->code)) {
            return $this->showError('Code '.$request->code.' is already taken!', 422);
        }

        $newDiscount = DB::transaction(function () use ($request) {
            $region = Region::where('ulid', $request->region)->firstOrFail();
            $discountRule = DiscountRule::create(
                ['value' => $request->value, 'region_id' => $region->id]
                +
                $request->only([
                    'description',
                    'discount_type',
                    'allocation',
                    'metadata',
                    'value',
                ])
            );

            $discount = $discountRule->discount()->create(
                [
                    'seller_id' => auth()->id(),
                    'starts_at' => now(),
                ]
                +
                $request->only([
                    'code',
                    'is_dynamic',
                    'is_disabled',
                    'parent_id',
                    'ends_at',
                    'usage_limit',
                    'usage_count',
                ])
            );

            if ($request->conditions) {

                if ($request->has('products')) {
                    $products = Product::whereIn('ulid', $request->products)->pluck('id');
                }
                if ($request->has('customer_group')) {
                    $products = Product::whereIn('ulid', $request->products)->pluck('id');
                }
            }

            return $discount;
        });

        return $this->showOne(new DiscountResource($newDiscount->load('discount_rule')));
    }

    public function show(Discount $discount): JsonResponse
    {
        return $this->showOne(new DiscountResource($discount->load('discount_rule')));
    }

    public function update(UpdateDiscountRequest $request, Discount $discount): JsonResponse
    {
        $request->validated();

        DB::transaction(function () use ($request, $discount) {
            $region = Region::where('ulid', $request->region)->firstOrFail();
            if ($request->has('code')) {
                if ($this->validate_code($request->code)) {
                    return $this->showError('Code '.$request->code.' is already taken!', 422);
                }
            }

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
        $discount->delete();

        return $this->showMessage('Discount deleted successfully!');
    }

    private function validate_code(string $code): bool
    {
        $code_availabilty = Discount::where('code', $code)
            ->where('seller_id', auth()->id())
            ->get();

        return count($code_availabilty) > 0;
    }
}
