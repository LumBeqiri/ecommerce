<?php

namespace App\Http\Controllers\Discount;

use App\Http\Controllers\ApiController;
use App\Http\Requests\DiscountRequest;
use App\Http\Resources\DiscountResource;
use App\Models\Discount;
use App\Models\DiscountRule;
use App\Models\Product;
use App\Models\Region;
use Illuminate\Http\Request;
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

        $code_availabilty = Discount::where('code', $request->code)
            ->where('seller_id', auth()->id())
            ->get();

        if (count($code_availabilty)) {
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
                if ($request->has('products')) {
                    $products = Product::whereIn('uuid', $request->products)->pluck('id');
                    $discountCondition = $discountRule->discount_condition()->create([
                        'model_type' => $request->model_type,
                        'operator' => $request->operator,
                        'metadata' => $request->metadata ?? null,
                    ]);

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
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
