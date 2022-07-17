<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    //

    public function getTransaction(Request $req){

        try{
        $transtion = Transaction::where('status',false)->first();
        $orders=$transtion->orders;
        if($transtion){

            foreach($orders as $order){
                $order->product;

            }
            return response()->json([
                'status'=>true,
                'transactions'=>$transtion
            ],200);
        }
        else{
            return response()->json([
                'status'=>true,
                'transactions'=>$transtion
            ],200);
        }
    }catch(\Throwable $e){
        return response()->json([
            'status'=>true,
            'transactions'=>[]
        ],200);
    }
    }
}
