<?php
namespace App\Http\Controllers;

use App\Events\OrderStatusChanged;
use App\Events\PaymentProcessed;
use App\Models\Order;
use App\Notifications\ActionFailedNotification;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;
use App\Notifications\OrderStatusChangedNotification;
use App\Notifications\PaymentSuccessNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $notificationService;

    public function index()
    {
        return response()->json(Order::with('customer', 'items')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.price' => 'required|numeric|min:0.01',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = DB::transaction(function () use ($validated) {

            $order = Order::create([
                'customer_id' => $validated['customer_id'],
                'status' => 'pending',
            ]);

            foreach ($validated['items'] as $itemData) {
                $order->items()->create($itemData);
            }

            return $order;
        });

        return response()->json(['order' => $order->load('items')], 201);
    }

    public function show(Order $order)
    {
        return response()->json($order->load('customer', 'items'));
    }
//////? الاشعارات
       public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function processPayment(Request $request)
{
    try {
        
        if ($request->amount > auth()->user()->balance) {
            throw new \Exception("رصيد الحساب غير كافٍ لإتمام العملية");
        }
        $user = auth()->user();
        $user->decrement('balance', $request->amount);
        //  $this->notificationService->send(
        //     $user,
        //     new PaymentSuccessNotification($request->amount),
        //     ['database']
        // );
        // return response()->json([
        //     'status'  => 'success',
        //     'message' => 'تمت عملية الدفع بنجاح بقيمة ' . $request->amount,
        //     'balance_remaining' => $user->balance
        // ], 200);
        //?? استخدام event & listener
        event(new PaymentProcessed($user, $request->amount));
        return response()->json(['message' => 'تم الدفع بنجاح'], 200);

    } catch (\Exception $e) {
        $this->notificationService->send(
            auth()->user(),
            new ActionFailedNotification($e->getMessage()),
            ['database']
        );
        return response()->json([
            'error' => 'فشلت العملية: ' . $e->getMessage()
        ], 500);
    }
}


//    public function updateStatus(Request $request, Order $order)
//     {
//     $validated = $request->validate([
//         'status' => 'sometimes|required|in:pending,paid,canceled',
//     ]);
//     $order->update($validated);
//     if (isset($validated['status'])) {
//         $this->notificationService->send(
//             $order->customer,
//             // $order->user,
//             new OrderStatusChangedNotification($order),
//             ['database']
//         );
//     }
//     return response()->json([
//         'message' => 'Order updated and notification sent.',
//         'order' => $order
//     ]);
// }
    //???? باستخدام event & listener
      public function updateStatus(Request $request, $id){
     $order = Order::findOrFail($id);
       $order->update(['status' => $request->status]);
      if ($order->user) {
         event(new OrderStatusChanged($order));
    } else {
       Log::warning("Order #{$id} has no associated user for notifications.");
    }
        return response()->json(['message' => 'تم تحديث الحالة بنجاح']);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['message' => 'Order deleted successfully']);
    }
}
