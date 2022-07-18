<?php

namespace App\Http\Controllers;

use ApiResponse;
use App\Facades\Providers\ApiResponse as ProvidersApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Password;
use Str;

class AuthController extends Controller
{

    public function response($user){
        $token  = $user->createToken(str()->random(40))->plainTextToken;
        return response()->json([
            'user'=>$user,
            'token'=>$token,
            'token_type'=>'Bearer'
        ]);

    }
    public function register(Request $request)
    {

    
        $validateUser =  Validator::make($request->all(),
        [
            'name'=> 'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required'
        ]);
        if($validateUser->failed()){
            return response()->json([
                'status'=>false,
                'message'=>'validator error',
                'error'=> $validateUser->errors()
            ],401);
        }
        $userExist = User::where('email', $request->email)->first();
        if($userExist){
            return response()->json([
                'status'=>false,
                'message'=>'account already existed',
            ],401);

        }
        $user  = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password)
        ]);

        return $this->response($user);

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
            if(!Auth::attempt($request->only(['email','password']))){
                return response()->json([
                    'status'=>false,
                    'message'=>'email & Password  does not match',
                   
                ],401);
            }

            $user = User::where('email', $request->email)->first();;

            return $this->response(Auth::user());


    }


    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message'=>'You have successfully logged out  and token was successfull deleted'
        ]);

        
    }

    // public function refresh()
    // {
    //     return $this->respondWithToken($this->guard()->refresh());
    // }

    // /**
    //  * @return \PHPOpenSourceSaver\JWTAuth\JWTGuard
    //  */
    // public function guard()
    // {
    //     return Auth::guard();
    // }

    // protected function respondWithToken($token)
    // {
    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => $this->guard()->factory()->getTTL() * 60
    //     ]);
    // }

    // public function verifyEmail(Request $request)
    // {
    //     $user = User::find($request->route('id'));

    //     if ($user->hasVerifiedEmail()) {
    //         return redirect(config('app.frontend_url'));
    //     }

    //     if ($user->markEmailAsVerified()) {
    //         event(new Verified($user));
    //     }

    //     return redirect(env('app.frontend_url'));
    // }

    // public function sendResetPasswordMail(Request $request)
    // {
    //     $status = Password::sendResetLink(
    //         $request->only('email')
    //     );
    //     if ($status === Password::RESET_LINK_SENT) {
    //         return ApiResponse::withMessage(__($status))
    //             ->makeResponse();
    //     }
    //     return ApiResponse::withStatusCode(400)
    //         ->withMessage(__($status))
    //         ->makeResponse();
    // }

    // public function resetPassword(Request $request)
    // {
    //     $status = Password::reset(
    //         $request->only('email', 'password', 'token'),
    //         function ($user, $password) {
    //             $user->forceFill([
    //                 'password' => Hash::make($password)
    //             ])->setRememberToken(Str::random(60));

    //             $user->save();

    //             event(new PasswordReset($user));
    //         }
    //     );

    //     if ($status === Password::PASSWORD_RESET) {
    //         return ApiResponse::withMessage(__($status))
    //             ->makeResponse();
    //     }
    //     return ApiResponse::withStatusCode(400)
    //         ->withMessage(__($status))
    //         ->makeResponse();
    // }
}
