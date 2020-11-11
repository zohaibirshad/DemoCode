<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subcategories')->insert([
            'storename' => Str::random(10),
            'category_id' => 1,
            'name' => Str::random(10),
            'slug' => Str::random(5),
        ]);
    }
}
