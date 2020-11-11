<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->insert([
            'storename' => Str::random(10),
            'user_id' => 1,
            'title' => Str::random(10),
            'details' => Str::random(50),
            'photo' => '1561348133service1.png',
        ]);
    }
}
