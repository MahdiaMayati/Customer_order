<?php

namespace App\Observers;
use App\Models\Order_item;


class OrderItemObserver
{

    public function recalculateTotal(Order_item $orderItem)
    {
        if (!$orderItem->order) return;

        $order = $orderItem->order;

        $newTotal = $order->items()->sum('subtotal');

        $order->total = $newTotal;
        $order->saveQuietly();
    }

    public function created(Order_item $orderItem) {
        $this->recalculateTotal($orderItem);
    }

    public function updated(Order_item $orderItem) {

        if ($orderItem->isDirty(['price', 'quantity'])) {
            $this->recalculateTotal($orderItem);
        }
    }

    public function deleted(Order_item $orderItem) {
        $this->recalculateTotal($orderItem);
    }
}

