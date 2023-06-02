<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Orders\OrderPaymentController;
use App\Http\Controllers\Api\Payment\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::group([
    'prefix' => 'auth',
    'as'     => 'auth.',
], static function () {
    Route::post('login', [AuthController::class, 'login'])
        ->name('login');
});

Route::group([
    'middleware' => 'auth:sanctum',
], function () {
    /*
     * orders
     */
    Route::prefix('orders')
        ->name('orders.')
        ->group(function () {
            /*
             * order payment
             */
            Route::prefix('payment')
                ->name('payment.')
                ->group(function () {
                    Route::get(
                        'secret',
                        [OrderPaymentController::class, 'secret']
                    )
                        ->name('secret');
                    Route::get(
                        'checkout',
                        [OrderPaymentController::class, 'checkout']
                    )
                        ->name('checkout');
                });
        });
});

Route::prefix('payment')
    ->name('payment.')
    ->group(function () {
        Route::post(
            'webhook',
            [PaymentController::class, 'webhook']
        )
            ->name('webhook');
    });
