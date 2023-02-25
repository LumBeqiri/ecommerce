<?php

namespace App\Services;

use Stevebauman\Location\Facades\Location;


class LocationService
{
    public function getCountry() : string
    {
        $ip = '185.190.132.204';
        if(auth('sanctum')->user()){
            return auth('sanctum')->user()->country;
        }
        if ($position = Location::get($ip)) {
            return $position->countryName;
        }
 
    }
}
