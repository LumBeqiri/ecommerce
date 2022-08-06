<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;


class ApiController extends Controller
{
    use ApiResponser;

    public $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }
    
}
