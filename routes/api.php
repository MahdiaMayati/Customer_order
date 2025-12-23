<?php
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::apiResource('customers',CustomerController::class);

Route::apiResource('orders',OrderController::class);

Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus']);

Route::post('orders/{order}/items', [OrderItemController::class, 'store']);

Route::patch('order-items/{orderItem}', [OrderItemController::class, 'update']);

Route::delete('order-items/{orderItem}', [OrderItemController::class, 'destroy']);

//////////? الاشعارات
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']);
    Route::get('/notifications', [UserController::class, 'getNotifications']);
    Route::post('/notifications/{id}/read', [UserController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [UserController::class, 'markAllAsRead']);
    Route::post('/payment/process', [OrderController::class, 'processPayment']);
});
// Route::get('/test-notify', function () {
//     $user = App\Models\User::first();
//     $order = App\Models\Order::first();
//     $user->notify(new App\Notifications\OrderStatusChangedNotification($order));
//     $user->notify(new App\Notifications\WelcomeNewUserNotification());
//    return "تم إرسال نوعين مختلفين من الإشعارات! اذهب الآن لجدول ";
//     // return "تم إضافة إشعار بنجاح! اذهب وافحص الجدول الآن.";
// });
