<?php

namespace App\Http\Controllers\Product;

use App\Models\Buyer;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ProductBuyerController extends ApiController
{
    public function index(Product $product)
    {

        // $orders = $product->orders()->get()
        // ->pluck('buyer_id') ;
        // $buyers = User::findMany($orders);
        // return $this->showAll($buyers);

        $buyers = $product->orders()
        ->with('buyer')
        ->get()
        ->pluck('buyer')
        ->unique('id')
        ->values();
        return $this->showAll($buyers);
    
    }
}
