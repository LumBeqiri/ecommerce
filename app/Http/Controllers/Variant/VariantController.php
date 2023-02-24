<?php

namespace App\Http\Controllers\Variant;

use App\Http\Controllers\ApiController;
use App\Http\Resources\VariantResource;
use App\Models\Country;
use App\Models\Variant;
use Illuminate\Http\JsonResponse;
use Stevebauman\Location\Facades\Location;

class VariantController extends ApiController
{
    public function index()
    {
        $ip = '185.190.132.204';
        if ($position = Location::get($ip)) {
            $country_name = $position->countryName;
        }
        $region_id = Country::select('region_id')->where('name', 'LIKE', '%'.$country_name.'%')->value('region_id');

        $variants = Variant::with(['medias', 'variant_prices' => function ($query) use ($region_id) {
            $query->where('region_id', $region_id);
        }])->get();

        return $this->showAll(VariantResource::collection($variants));
    }

    public function show(Variant $variant): JsonResponse
    {
        return $this->showOne(new VariantResource($variant));
    }
}
