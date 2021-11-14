<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerOrderController extends ApiController
{
    public function index(Buyer $buyer){
        $orders = $buyer->orders;
        return $this->showAll($orders);
    }
}
