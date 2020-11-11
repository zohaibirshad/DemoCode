<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('wishlists')->insert([
            'storename' => Str::random(10),
            'category_id' => 1,
            'product_id' => 1,
        ]);
    }
}
