<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('orders')->insert([
            'storename' => Str::random(10),
            'cart' => Str::random(50),
            'totalQty' => 2,
            'pay_amount' => 100,
            'order_number' => Str::random(5),
            'payment_status' => 'Pending',
            'customer_email' => 'abcd@xyz.com',
            'customer_name' => Str::random(5),
            'customer_country' => 'United States',
            'customer_phone' => '123456789',
            'currency_sign' => '$',
            'currency_value' => 1,
            'shipping_cost' => 0,
            'tax' => 0,
        ]);
    }
}
