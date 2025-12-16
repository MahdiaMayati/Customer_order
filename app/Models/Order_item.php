<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_item extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'product_name', 'price', 'quantity', 'subtotal'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    protected static function boot()
{
    parent::boot();

    static::saving(function ($item) {

        $item->subtotal = $item->price * $item->quantity;
    });
}


}
