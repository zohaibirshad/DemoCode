<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'storename' => Str::random(10),
            'product_type' => 'normal',
            'user_id' => 1,
            'category_id' => 1,
            'name' => Str::random(10),
            'slug' => Str::random(10),
            'photo' => '15680269303GYKjODW.png',
            'thumbnail' => '1568026930poclhyxJ.jpg',
            'price' => 100,
        ]);
    }
}
