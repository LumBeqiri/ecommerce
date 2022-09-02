<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;


class BuyerController extends ApiController
{
    public function index(){

        $buyers = Buyer::has('orders')->orderBy('id')->get();
        return $this->showAll($buyers);
    }

    public function show(Buyer $buyer){
        // echo 'hi';
        return $this->showOne($buyer);
    }

}
