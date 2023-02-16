<?php

namespace App\Http\Controllers\Discount;

use App\Http\Controllers\ApiController;
use App\Http\Requests\DiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Http\Resources\DiscountResource;
use App\Models\Discount;
use App\Models\DiscountRule;
use App\Models\Product;
use App\Models\Region;
use Illuminate\Support\Facades\DB;

class DiscountController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
                ['value' => $request->percentage ?? $request->amount]
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

            if ($request->has('conditions')) {
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Discount $discount)
    {
        // return discount with conditions
        return $this->showOne(new DiscountResource($discount->load('discount_rule.discount_condition')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDiscountRequest $request, Discount $discount)
    {
        $dataToUpdate = $request->validated();

        if ($request->has('code')) {
            if ($this->validate_code($request->code)) {
                return $this->showError('Code '.$request->code.' is already taken!', 422);
            }
        }

        $discount->fill($dataToUpdate);

        $discount->save();

        return $this->showOne(new DiscountResource($discount));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discount $discount)
    {
        $discount->delete();

        return response()->noContent();
    }

    
    private function validate_code($code)
    {
        $code_availabilty = Discount::where('code', $code)
            ->where('seller_id', auth()->id())
            ->get();

        return count($code_availabilty) > 0;
    }
}
