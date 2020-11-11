<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('banners')->insert([
            'storename' => Str::random(10),
            'photo' => '1557677677bouquet_PNG62.png',
            'link' => Str::random(15),
            'type' => 'Large',
        ]);
    }
}
