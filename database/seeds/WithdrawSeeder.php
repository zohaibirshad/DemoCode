<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WithdrawSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('withdraws')->insert([
            'storename' => Str::random(10),
            'user_id' => 1,
            'method' => 'Paypal',
            'acc_email' => Str::random(5).'@gmail.com',
            'reference' => Str::random(15),
            'amount' => '61.5',
            'fee' => '8.5',
            'type' => 'user',
        ]);
    }
}
