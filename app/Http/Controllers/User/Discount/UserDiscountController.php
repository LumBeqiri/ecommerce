<?php

namespace App\Http\Controllers\User\Discount;

use App\Models\User;
use App\Models\Region;
use App\Models\Product;
use App\Models\Discount;
use App\Models\DiscountRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use App\Http\Resources\DiscountResource;
use App\Http\Requests\Discount\DiscountRequest;
use App\Http\Requests\Discount\UpdateDiscountRequest;

class UserDiscountController extends ApiController
{
    protected User $user;
    
    public function __construct()
    {
        $user = auth()->user();
    }
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Discount::class);
        $discounts = Discount::with(['discount_rule.products'])->get();

        return $this->showAll(DiscountResource::collection($discounts));
    }

    public function store(DiscountRequest $request): JsonResponse
    {
        $this->authorize('create', Discount::class);
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
                    'vendor_id' => $this->user->vendor->id,
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
                    Product::whereIn('id', $products)->update(['discount_id' => $discount->id]);
                }
                // customer groups
            }

            return $discount;
        });

        return $this->showOne(new DiscountResource($newDiscount->load('discount_rule')));
    }

    public function show(Discount $discount): JsonResponse
    {
        $this->authorize('view', $discount);
        return $this->showOne(new DiscountResource($discount->load('discount_rule')));
    }

    public function update(UpdateDiscountRequest $request, Discount $discount): JsonResponse
    {
        $this->authorize('update', $discount);
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
        $this->authorize('delete', $discount);
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
