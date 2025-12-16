<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create(['name' => 'أحمد', 'phone' => '0912345678']);
        Customer::create(['name' => 'محمد', 'phone' => '0915554433']);
        Customer::create(['name' => 'مايا', 'phone' => '0917890123']);
        Customer::create(['name' => 'مريم ', 'phone' => '0919876543']);
        Customer::create(['name' => 'يوسف ', 'phone' => '0911122334']);

    }
}
