<?php

namespace App\Http\Controllers\Buyer;

use App\Models\Buyer;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Session;

class BuyerOrderController extends ApiController
{
    public function index(Buyer $buyer){
        $orders = $buyer->orders;
        return $this->showAll($orders);
    }


}
