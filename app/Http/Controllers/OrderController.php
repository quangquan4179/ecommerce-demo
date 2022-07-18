<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    //

    public function createOrder(Request $req){


        $validateOrder =  Validator::make($req->all(),
        [
            'amount'=>'required',
            'product_id'=>'required',
            'qty'=>'required'
            
        ]);
        if($validateOrder->failed()){
            return response()->json([
                'status'=>false,
                'message'=>'validator error',
                'error'=> $validateOrder->errors()
            ],401);
        }

        $cart = Cart::where('user_id',$req->user_id)->first();
        if($cart){
            $cart->orders()->create([
                        "user_id"=>$req->user()->id,
                        "product_id"=>$req->product_id,
                        "amount"=>$req->amount,
                        "qty"=>$req->qty,
                        "cart_id"=>$cart->id
                    ]);

            return response()->json([
                "status"=>true,
                "message"=>'Order created Successfull',
                "cart"=>$cart
            ]);
        // }
        }
        else{
                 $newCart = Cart::create([
                "user_id"=>$req->user()->id,
               

            ]);
            $newCart->orders()->create([
                "user_id"=>$req->user()->id,
                "product_id"=>$req->product_id,
                "amount"=>$req->amount,
                "qty"=>$req->qty,
                "cart_id"=>$newCart->id
            ]);
            return response()->json([
                "status"=>true,
                "message"=>'Order created Successfull',
                "cart"=>$newCart
            ]);

        }

        

     
    }
}
