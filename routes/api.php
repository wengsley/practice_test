<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\WalletController;

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

Route::middleware('api')->group(function () {
   
    // Route::middleware('guest')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('api.login');
        Route::post('/register', [AuthController::class, 'register'])->name('api.register');
        Route::post('/reset_password', [AuthController::class, 'resetPassword'])->name('api.resetPassword');
    //});

    Route::group(['middleware' => ['auth:sanctum']], function() {

        Route::get('/transactions/{id}', [TransactionController::class, 'show']);
        Route::post('/transfer', [TransferController::class, 'transfer']);
    });

});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


