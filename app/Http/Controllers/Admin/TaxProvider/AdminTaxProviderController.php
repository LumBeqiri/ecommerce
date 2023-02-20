<?php

namespace App\Http\Controllers\Admin\TaxProvider;

use App\Http\Controllers\ApiController;
use App\Http\Requests\TaxProviderRequest;
use App\Http\Resources\TaxProviderResource;
use App\Models\TaxProvider;
use Illuminate\Http\JsonResponse;

class AdminTaxProviderController extends ApiController
{

    public function index() : JsonResponse
    {
        $taxProviders = TaxProvider::all();

        return $this->showAll(TaxProviderResource::collection($taxProviders));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaxProviderRequest $request)
    {
        $taxProvider = TaxProvider::create($request->validated());

        return $this->showOne(new TaxProviderResource($taxProvider));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TaxProvider $taxProvider)
    {
        return $this->showOne(new TaxProviderResource($taxProvider));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TaxProviderRequest $request, TaxProvider $taxProvider)
    {
        $taxProvider->fill($request->validated());
        $taxProvider->save();

        return $this->showOne(new TaxProviderResource($taxProvider));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaxProvider $taxProvider)
    {
        $taxProvider->delete();

        return $this->showOne(new TaxProviderResource($taxProvider));
    }
}
