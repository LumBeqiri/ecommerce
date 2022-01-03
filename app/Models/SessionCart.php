<?php

namespace App\Models;

use App\Models\Product;



class SessionCart
{
    public $items=null;
    public $totalQty = 0;
    public $totalPrice =0;
    public $product_ids = array();


    public function __construct($oldCart){
        if($oldCart){
            $this->items = $oldCart->items;
            $this->totalQty = $oldCart->totalQty;
            $this->totalPrice = $oldCart->totalPrice;
            $this->product_ids = $oldCart->product_ids;

        }
    }

    public function addProductId($id){
        //$this->product_ids[] = $id;
        array_push($this->product_ids, $id);

    }

    public function add($item, $id){ 
        $price =$item->getSellingPrice();
        $discount = $item->getDiscountPercent();
      
    
        //store this array that represent an item with given attrs
        $storedItem = ['item' => $id, 'qty' => 0, 'price' => $price, 'discount' =>$discount] ;
        //check if user has items
        if($this->items){ 
            //if item exists on the cart already, then do nothing
            if(array_key_exists($id,$this->items)){
                $storedItem = $this->items[$id];
            }
        }

        array_push($this->product_ids, $id);
        

        $storedItem['qty']++;
        //this sets the total price for product group
        $storedItem['price'] = $price * $storedItem['qty']; 
        $this->items[$id] = $storedItem;
        $this->totalQty++;
        $this->totalPrice+= (int) $price;
    }

    public function remove($item, $id){
        $price =$item->getSellingPrice();
        //check if user has items
        if($this->items){

            if(array_key_exists($id,$this->items)){
                //check if there's only one product added
                $temp = $this->items[$id];
                if($temp['qty'] == 1){
                    $this->totalQty--;
                    $this->totalPrice-=$price;
                    unset($this->items[$id]);
                }
                else{
                    $this->items[$id]['qty']--;
                    $this->items[$id]['price'] = number_format($price,2) * $this->items[$id]['qty']; 
                    $this->totalQty--;
                    $tot = $this->totalPrice;
                    $tot-=number_format($price,2);
                    $this->totalPrice = number_format($tot,2);
                   // $this->totalPrice = (int) $this->totalPrice;
                }
            }
        }
    }
}
