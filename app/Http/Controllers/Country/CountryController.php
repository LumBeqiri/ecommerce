<?php

namespace App\Http\Controllers\Country;

use App\Http\Controllers\ApiController;
use App\Models\Country;
use Spatie\QueryBuilder\QueryBuilder;

class CountryController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries = QueryBuilder::for(Country::class)
            ->allowedIncludes('region')
            ->get();

        return $this->showAll($countries, paginate: false);
    }
}
