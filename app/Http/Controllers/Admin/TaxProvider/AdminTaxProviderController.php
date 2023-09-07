<?php

namespace App\Http\Controllers\Admin\TaxProvider;

use App\Http\Controllers\ApiController;
use App\Http\Requests\TaxProvider\TaxProviderRequest;
use App\Http\Resources\TaxProviderResource;
use App\Models\TaxProvider;
use Illuminate\Http\JsonResponse;

class AdminTaxProviderController extends ApiController
{
    public function index(): JsonResponse
    {
        $taxProviders = TaxProvider::all();

        return $this->showAll(TaxProviderResource::collection($taxProviders));
    }

    public function store(TaxProviderRequest $request): JsonResponse
    {
        $taxProvider = TaxProvider::create($request->validated());

        return $this->showOne(new TaxProviderResource($taxProvider));
    }

    public function show(TaxProvider $taxProvider): JsonResponse
    {
        return $this->showOne(new TaxProviderResource($taxProvider));
    }

    public function update(TaxProviderRequest $request, TaxProvider $taxProvider): JsonResponse
    {
        $taxProvider->fill($request->validated());
        $taxProvider->save();

        return $this->showOne(new TaxProviderResource($taxProvider));
    }

    public function destroy(TaxProvider $taxProvider): JsonResponse
    {
        $taxProvider->delete();

        return $this->showOne(new TaxProviderResource($taxProvider));
    }
}
