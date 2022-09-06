<?php

namespace App\Http\Controllers\Auth;


use App\Http\Requests\LoginRequest;

use App\Http\Controllers\ApiController;


class TryController extends ApiController
{

    public function __invoke(){
        dd(auth()->user()->id);
    }
}
