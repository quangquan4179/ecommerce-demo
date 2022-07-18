<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    //

    public function getTransaction(Request $req){

        try{

        $transtion = Transaction::where('status',false)->where('user_id',$req->user()->id)->first();
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
    public function createTransaction(Request $req){

        $orders=[];
        foreach($req->orderIds as $id){
            $ord = Order::where('id',$id)->first();

            array_push($orders,$ord);
        }

        $transtion = Transaction::create([
            "user_id"=>$req->user()->id,
            "amount"=>$req->amount,

        ]);
        $transtion->orders=$orders;
        $transtion->save();
        return response()->json([
            'status'=>true,
            'transactions'=>$transtion
        ],200);


    }
}
