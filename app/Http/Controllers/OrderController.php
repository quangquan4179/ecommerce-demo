<?php

namespace App\Http\Controllers;

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
            'user_id'=>'required',
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

        $transaction = Transaction::where('user_id',$req->user_id)->where('status',false)->first();
        if($transaction){
            $transaction->amount=$transaction->amount+$req->amount;
            $transaction->orders()->create([
                "user_id"=>$req->user_id,
                "product_id"=>$req->product_id,
                "amount"=>$req->amount,
                "qty"=>$req->qty,
                "transaction_id"=>$transaction->id
            ]);

            $transaction->save();
        
            return response()->json([
                "status"=>true,
                "message"=>'Order created Successfull',
                "transaction"=>$transaction
            ]);

        }else{
            $newTransaction = Transaction::create([
                "user_id"=>$req->user_id,
                "amount"=>$req->amount,

            ]);
            $newTransaction->orders()->create([
                "user_id"=>$req->user_id,
                "product_id"=>$req->product_id,
                "amount"=>$req->amount,
                "qty"=>$req->qty,
                "transaction_id"=>$newTransaction->id
            ]);
            // $order = Order::create([
            //     "user_id"=>$req->user_id,
            //     "product_id"=>$req->product_id,
            //     "amount"=>$req->amount,
            //     "qty"=>$req->qty,
            //     "transaction_id"=>$newTransaction->id
            // ]);
            return response()->json([
                "status"=>true,
                "message"=>'Order created Successfull',
                "transaction"=>$newTransaction
            ]);
        }

        

     
    }
}
