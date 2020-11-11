<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_subscriptions')->insert([
            'storename' => Str::random(10),
            'user_id' => 1,
            'subscription_id' => 1,
            'title' => Str::random(10),
            'currency' => '$',
            'currency_code' => 'USD',
            'price' => '10.00',
            'days' => '10',
            'allowed_products' => '10',
            'details' => Str::random(20),
            'days' => '10',
        ]);
    }
}
