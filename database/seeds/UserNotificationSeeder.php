<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_notifications')->insert([
            'storename' => Str::random(10),
            'user_id' => 1,
            'order_number' => Str::random(10),
        ]);
    }
}
