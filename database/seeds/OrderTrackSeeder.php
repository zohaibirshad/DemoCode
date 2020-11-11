<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderTrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_tracks')->insert([
            'order_id' => 1,
            'title' => 'Pending',
            'text' => Str::random(50),
        ]);
    }
}
