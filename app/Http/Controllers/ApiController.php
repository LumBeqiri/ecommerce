<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;



class ApiController extends Controller
{
    use ApiResponser;
    
    /** @phpstan-ignore-next-line */
    public $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }
    
}
