<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;



class ApiController extends Controller
{
    use ApiResponser;
    
    
    public $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }
    
}
