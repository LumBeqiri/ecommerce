<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use App\Models\Seller;
use Illuminate\Http\Request;

class BuyerController extends ApiController
{
    public function index(){

        $buyers = Buyer::has('orders')->get();
        return $this->showAll($buyers);
    }


}
