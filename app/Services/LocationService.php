<?php

namespace App\Services;

use Stevebauman\Location\Facades\Location;

class LocationService
{
    public function getCountry(): string
    {
        $ip = '185.190.132.204';
        if (auth('sanctum')->user()) {
            /** @var \App\Models\User $user */
            $user = auth('sanctum')->user();

            return $user->country;
        }
        if ($position = Location::get($ip)) {
            // @phpstan-ignore-next-line
            return $position->countryName;
        }

        return '';
    }
}
