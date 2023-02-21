<?php

namespace App\Http\Controllers\Admin\Region;

use App\Http\Controllers\ApiController;
use App\Http\Requests\RegionRequest;
use App\Http\Requests\UpdateRegionRequest;
use App\Http\Resources\RegionResource;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Http\JsonResponse;

class AdminRegionController extends ApiController
{
    public function index(): JsonResponse
    {
        $regions = Region::all();

        return $this->showAll(RegionResource::collection($regions));
    }

    public function store(RegionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $region = Region::create($request->except('countries'));

        $countries = $data['countries'];

        $countries = Country::findMany($countries);

        $region->countries()->saveMany($countries);

        return $this->showOne(new RegionResource($region));
    }

    public function show(Region $region): JsonResponse
    {
        return $this->showOne(new RegionResource($region));
    }

    public function update(UpdateRegionRequest $request, Region $region): JsonResponse
    {
        $data = $request->validated();

        $region->fill($data);

        $region->save();

        return $this->showOne($region);
    }

    public function updateCountries(UpdateRegionRequest $request, Region $region): JsonResponse
    {
        $data = $request->validated();
        $countries = $data['countries'];

        $countries = Country::findMany($countries);

        $region->countries()->saveMany($countries);

        return $this->showMessage('Region updated successfully');
    }

    public function removeCountries(UpdateRegionRequest $request, Region $region): JsonResponse
    {
        $data = $request->validated();
        $countries = $data['countries'];

        $region->countries()->whereIn('id', $countries)->update(['region_id' => null]);

        return $this->showMessage('Region updated successfully');
    }

    public function destroy(Region $region): JsonResponse
    {
        $region->delete();

        return $this->showOne($region);
    }
}
