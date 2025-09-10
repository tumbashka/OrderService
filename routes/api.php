<?php


use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Support\Facades\Route;


Route::get('/orders/users/{user}', [OrderController::class, 'userOrders']);
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/reserved-dates/{car}', [OrderController::class, 'reservedDates']);
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('order.show');
Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel']);

Route::post('/orders/{order}/payment/create', [PaymentController::class, 'createOrderPayment']);
Route::post('/orders/{order}/payment/refresh', [PaymentController::class, 'refreshPayment']);

Route::post('/payment/webhook/yookassa', [WebhookController::class, 'yookassa']);

Route::get('/payment/types', [PaymentController::class, 'paymentTypes']);
