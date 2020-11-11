<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_gateways')->insert([
            'storename' => Str::random(10),
            'subtitle' => Str::random(10),
            'title' => Str::random(10),
            'details' => Str::random(50),
        ]);
    }
}
