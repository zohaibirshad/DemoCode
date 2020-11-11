<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notifications')->insert([
            'storename' => Str::random(10),
            'order_id' => 1,
            'user_id' => 1,
            'conversation_id' => 1,
        ]);
    }
}
