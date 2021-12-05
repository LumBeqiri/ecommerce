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

        $buyers = Buyer::has('orders')->orderBy('id')->get();
        return $this->showAll($buyers);
    }

    public function show(Buyer $buyer){
        // echo 'hi';
        return $this->showOne($buyer);
    }

}
