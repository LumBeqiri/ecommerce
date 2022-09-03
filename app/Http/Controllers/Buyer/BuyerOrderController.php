<?php

namespace App\Http\Controllers\Buyer;

use App\Models\Buyer;
use App\Http\Controllers\ApiController;


class BuyerOrderController extends ApiController
{

    /**
     * @param Buyer $buyer
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer){
        $orders = $buyer->orders;
        return $this->showAll($orders);
    }


}
