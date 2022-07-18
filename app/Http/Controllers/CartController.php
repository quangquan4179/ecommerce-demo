<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //

    public function getCart(Request $req){

        try{

        $cart = Cart::where('user_id',$req->user()->id)->first();
        $orders=$cart->orders;
        if($cart){

            foreach($orders as $order){
                $order->product;

            }
            return response()->json([
                'status'=>true,
                'cart'=>$cart
            ],200);
        }
        else{
            return response()->json([
                'status'=>true,
                'cart'=>[]
            ],200);
        }
    }catch(\Throwable $e){
        return response()->json([
            'status'=>true,
            'transactions'=>[]
        ],200);
    }
    }

    public function removeProduct(Request $req){
        $cart = Cart::where('user_id',$req->user()->id)->first();
        $cart->orders()->where('id', $req->id)->delete();
        return response()->json([
            'status'=>true,
            'message'=>'Deleted Successfull'
        ]);

    }
}
