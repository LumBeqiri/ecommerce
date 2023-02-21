<?php

namespace App\Http\Controllers\Discount;

use App\Models\Region;
use App\Models\Product;
use App\Models\Discount;
use App\Models\DiscountRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\DiscountRequest;
use App\Http\Controllers\ApiController;
use App\Http\Resources\DiscountResource;
use App\Http\Requests\UpdateDiscountRequest;

class DiscountController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discounts = Discount::with(['discount_rule.discount_conditions.products', 'discount_rule.discount_conditions.customer_groups'])->get();

        return $this->showAll(DiscountResource::collection($discounts));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DiscountRequest $request)
    {
        $request->validated();

        if ($this->validate_code($request->code)) {
            return $this->showError('Code '.$request->code.' is already taken!', 422);
        }

        $newDiscount = DB::transaction(function () use ($request) {
            $discountRule = DiscountRule::create(
                ['value' => $request->value]
                +
                $request->only([
                    'description',
                    'discount_type',
                    'allocation',
                    'metadata',
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

            $regions = Region::whereIn('uuid', $request->regions)->pluck('id');
            $discount->regions()->attach($regions);

            if ($request->conditions) {
                $discountCondition = $discountRule->discount_conditions()->create([
                    'model_type' => $request->model_type,
                    'operator' => $request->operator,
                    'metadata' => $request->metadata ?? null,
                ]);

                if ($request->has('products')) {
                    $products = Product::whereIn('uuid', $request->products)->pluck('id');
                    $discountCondition->products()->attach($products);
                }
                if ($request->has('customer_group')) {
                    $products = Product::whereIn('uuid', $request->products)->pluck('id');
                    $discountCondition->products()->attach($products);
                }
            }

            return $discount;
        });

        return $this->showOne(new DiscountResource($newDiscount->load('discount_rule')));
    }

    public function show(Discount $discount) : JsonResponse
    {
        return $this->showOne(new DiscountResource($discount->load('discount_rule.discount_condition')));
    }


    public function update(UpdateDiscountRequest $request, Discount $discount) : JsonResponse
    {
        $request->validated();

        DB::transaction(function () use ($request, $discount) {
            if ($request->has('code')) {
                if ($this->validate_code($request->code)) {
                    return $this->showError('Code '.$request->code.' is already taken!', 422);
                }
            }

            $discount->discount_rule()->update([
                'description' => $request->description,
                'value' => $request->value,
            ]);

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

            $regions = Region::whereIn('uuid', $request->regions)->pluck('id');
            $discount->regions()->sync($regions);
        });

        return $this->showOne(new DiscountResource($discount));
    }

    public function destroy(Discount $discount) : JsonResponse
    {
        $discount->delete();

        return $this->showMessage('Discount deleted successfully!');
    }

    private function validate_code($code) : bool
    {
        $code_availabilty = Discount::where('code', $code)
            ->where('seller_id', auth()->id())
            ->get();

        return count($code_availabilty) > 0;
    }
}
