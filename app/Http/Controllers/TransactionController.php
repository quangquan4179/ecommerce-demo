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

        $transactions = Transaction::all()->where('status',false)->where('user_id',$req->user()->id);
        // return response()->json([
        //     'status'=>true,
        //     'transactions'=>$transtions
        // ],200);
        // $orders=$transtion->orders;
        if($transactions){

            foreach($transactions as $transaction){
                foreach($transaction->orders as $order){
                    $order->product;
                }

            }
            return response()->json([
                'status'=>true,
                'transactions'=>$transactions
            ],200);
        }
        else{
            return response()->json([
                'status'=>true,
                'transactions'=>[]
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
   
        foreach($orders as $order){
           $transtion->orders()->attach($order->id);
        }

        return response()->json([
            'status'=>true,
            'transactions'=>$transtion
        ],200);


    }
}
