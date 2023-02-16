<?php

namespace App\Http\Controllers\Admin\Region;

use App\Http\Controllers\ApiController;
use App\Http\Requests\RegionRequest;
use App\Http\Requests\UpdateRegionRequest;
use App\Http\Resources\RegionResource;
use App\Models\Country;
use App\Models\Region;

class AdminRegionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $regions = Region::all();

        return $this->showAll(RegionResource::collection($regions));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegionRequest $request)
    {
        $data = $request->validated();
        $region = Region::create($request->except('countries'));

        $countries = $data['countries'];

        $countries = Country::findMany($countries);

        $region->countries()->saveMany($countries);

        return $this->showOne(new RegionResource($region));
    }

    /**
     * @param  Region  $region
     * @return \Illuminate\Http\Response
     */
    public function show(Region $region)
    {
        return $this->showOne(new RegionResource($region));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRegionRequest $request, Region $region)
    {
        $data = $request->validated();

        $region->fill($data);

        $region->save();

        return $this->showOne($region);
    }

    public function updateCountries(UpdateRegionRequest $request, Region $region)
    {
        $data = $request->validated();
        $countries = $data['countries'];

        $countries = Country::findMany($countries);

        $region->countries()->saveMany($countries);

        return $this->showMessage('Region updated successfully');
    }

    public function removeCountries(UpdateRegionRequest $request, Region $region)
    {
        $data = $request->validated();
        $countries = $data['countries'];

        $region->countries()->whereIn('id', $countries)->update(['region_id' => null]);

        return $this->showMessage('Region updated successfully');
    }

    /**
     * @param  Region  $region
     * @return \Illuminate\Http\Response
     */
    public function destroy(Region $region)
    {
        $region->delete();

        return $this->showOne($region);
    }
}
