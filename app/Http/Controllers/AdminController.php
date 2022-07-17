<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller


{
    public function register(Request $request)
    {

    
        $validateUser =  Validator::make($request->all(),
        [
            'name'=> 'required',
            'email'=>'required|email|unique:admins,email',
            'password'=>'required'
        ]);
        if($validateUser->failed()){
            return response()->json([
                'status'=>false,
                'message'=>'validator error',
                'error'=> $validateUser->errors()
            ],401);
        }
            $admin = Admin::where('email', $request->email)->first();
            if($admin){
                return response()->json([
                    "status" => false,
                    "message"=>'Admin already existed!',
                    
                ],200);
            }
    
        $newAdmin  = Admin::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password)
        ]);

        return response()->json([
            "status" => true,
            "message"=>'Admin Created Successfully',
            "token"=> $newAdmin->createToken('API ADMIN TOKEN')->plainTextToken
        ],200);

    }

    public function login( Request $request){
       
            $validateUser =  Validator::make($request->all(),
            [
                
                'email'=>'required|email',
                'password'=>'required'
            ]);
            if($validateUser->failed()){
                return response()->json([
                    'status'=>false,
                    'message'=>'validator error',
                    'error'=> $validateUser->errors()
                ],401);
            }
            if(!Auth::guard('admin')->attempt($request->only(['email','password']))){
                return response()->json([
                    'status'=>false,
                    'message'=>'email & Password  does not match',
                   
                ],401);
            }

            $admin = Admin::where('email', $request->email)->first();
            return response()->json([
                'status'=>true,
                'message'=>' Admin login Successfully',
                'token'=> $admin->createToken("API ADMIN TOKEN")->plainTextToken
            ],200);


    }
}
