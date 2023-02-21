<?php

namespace App\Http\Controllers\Variant;

use App\Http\Controllers\ApiController;
use App\Http\Resources\VariantResource;
use App\Models\Variant;
use Illuminate\Http\JsonResponse;

class VariantController extends ApiController
{
    public function index(): JsonResponse
    {
        $variants = Variant::all();

        return $this->showAll(VariantResource::collection($variants));
    }

    public function show(Variant $variant): JsonResponse
    {
        return $this->showOne(new VariantResource($variant));
    }
}
