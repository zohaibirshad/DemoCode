<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('coupons')->insert([
            'storename' => Str::random(10),
            'code' => Str::random(10),
            'type' => 1,
            'price' => 10.25,
            
        ]);
    }
}
