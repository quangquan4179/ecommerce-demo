<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Resources\Catalog;
use App\Models\Catalog as ModelsCatalog;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Route::get('/test',function(){
//   return 'test';
// });
Route::prefix('auth')->group(function () {
//     Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
//         ->middleware(['signed', 'throttle:6,1'])
//         ->name('verification.verify');
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    // Route::post('/forgot-password', [AuthController::class, 'sendResetPasswordMail'])
    //     ->name('password.email.user');
    // Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    //     ->middleware('guest')->name('password.reset.user');
    // Route::middleware(['auth:user'])->group(function () {
    //     Route::post('logout', [AuthController::class, 'logout']);
    //     Route::post('refresh', [AuthController::class, 'refresh']);
    //     Route::get('me', [AuthController::class, 'me']);
    // });



});
Route::prefix('product')->group(function () {
    Route::get('all',[ProductController::class, 'getAllProducts']);
    Route::post('create', [ProductController::class, 'createProduct']);
    Route::get('{id}', [ProductController::class, 'getProductsById']);
    Route::delete('{id}', [ProductController::class,'deleteProduct']);
    

});
Route::prefix('order')->group(function () {
  
    Route::post('create', [OrderController::class, 'createOrder']);
});


Route::prefix('transaction')->group(function () {
  
    Route::get('user_id/{id}', [TransactionController::class, 'getTransaction']);
});
Route::prefix('catalog')->group(function(){
    Route::get('all',[CatalogController::class,'getAllProducts']);
    Route::get('{name}', function($name){
        return new Catalog(ModelsCatalog::findOrFail($name));
    });

});

Route::prefix('admin')->group(function () {
    Route::post('register', [AdminController::class, 'register']);
    Route::post('login', [AdminController::class, 'login']);
});

Route::get('healthy', function () {
    return ApiResponse::withMessage('healthy!')->makeResponse();
});
