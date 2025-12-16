<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order_item;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{

    public function index()
    {
        return response()->json(Order_item::all());
    }

    public function create()
    {
        //
    }


    public function store(Request $request ,Order $order)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = $order->items()->create($validated);

        return response()->json(['message' => 'Item added and total recalculated.', 'item' => $item], 201);
    }



    public function show(Order_item $order_item)
    {
        return response()->json($order_item);
    }


    public function edit(Order_item $order_item)
    {
        //
    }


    public function update(Request $request, Order_item $order_item)
    {
        $validated = $request->validate([
        'price' => 'numeric|min:0',
        'quantity' => 'integer|min:1',
    ]);

    DB::transaction(function () use ($order_item, $validated) {
        $order_item->update($validated);
    });

    return response()->json(['message' => 'Order item updated and order total recalculated.']);
}


    public function destroy(Order_item $order_item)
    {
        $order_item->delete();
        return response()->json(['message' => 'Order item deleted and order total recalculated.']);
    }

}
