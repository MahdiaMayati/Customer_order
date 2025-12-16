<?php
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use Illuminate\Http\Request;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::apiResource('customers',CustomerController::class);

Route::apiResource('orders',OrderController::class);

Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus']);

Route::post('orders/{order}/items', [OrderItemController::class, 'store']);

Route::patch('order-items/{orderItem}', [OrderItemController::class, 'update']);

Route::delete('order-items/{orderItem}', [OrderItemController::class, 'destroy']);
