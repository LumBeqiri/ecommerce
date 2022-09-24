<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;


class BuyerController extends ApiController
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index(){

        $buyers = Buyer::has('orders')->get();
        return $this->showAll($buyers);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer){
   
        return $this->showOne($buyer);
    }

}
