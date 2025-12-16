<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Customer;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customerIds = Customer::pluck('id')->all();
        if (empty($customerIds)) {
            $this->call(CustomerSeeder::class);
            $customerIds = Customer::pluck('id')->all();
        }


        $order1 = Order::create([
            'customer_id' => $customerIds[0],
            'status' => 'paid',
        ]);

        $order1->items()->createMany([
            [
                'product_name' => 'هاتف ذكي (256GB)',
                'price' => 750.00,
                'quantity' => 1,
            ],
            [
                'product_name' => 'شاحن سريع',
                'price' => 30.00,
                'quantity' => 2,
            ]
        ]);

        $order2 = Order::create([
            'customer_id' => $customerIds[1],
            'status' => 'pending',
        ]);

        $order2->items()->createMany([
            [
                'product_name' => 'لابتوب Core i7',
                'price' => 1200.00,
                'quantity' => 1,
            ]
        ]);
    }
    }

