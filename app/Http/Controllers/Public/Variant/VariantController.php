<?php

namespace App\Http\Controllers\Public\Variant;

use App\Http\Controllers\ApiController;
use App\Http\Resources\VariantResource;
use App\Models\Country;
use App\Models\Variant;
use Illuminate\Http\JsonResponse;

class VariantController extends ApiController
{
    public function index(): JsonResponse
    {
        $region_id = Country::select('region_id')
            ->where('code', 'DE')
            ->value('region_id');

        $variants = Variant::with(['media', 'variant_prices' => function ($query) use ($region_id) {
            $query->where('region_id', $region_id);
        }])->get();

        return $this->showAll(VariantResource::collection($variants));
    }

    public function show(Variant $variant): JsonResponse
    {
        return $this->showOne(new VariantResource($variant));
    }
}
