<?php

namespace App\Http\Controllers\Discount;

use App\Http\Controllers\ApiController;
use App\Models\Variant;
use App\Services\DiscountService;
use Illuminate\Http\Request;

class DiscountVariantController extends ApiController
{
    public function apply_discount(Request $request, Variant $variant)
    {
        $request->validate([
            'code' => 'required|string',
        ], [$request->code]);

        $cart = $this->authUser()->cart;
        DiscountService::applyDiscount($cart, $variant, $request->code);
    }
}
