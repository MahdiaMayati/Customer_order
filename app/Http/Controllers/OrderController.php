<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::with('customer', 'items')->get());
    }

      public function create()
    {

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

      public function edit()
    {

    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'sometimes|required|in:pending,paid,canceled',
        ]);

        $order->update($validated);
        return response()->json(['message' => 'Order updated.', 'order' => $order]);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['message' => 'Order deleted successfully']);
    }
}
