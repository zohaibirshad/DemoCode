<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChildCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'storename' => Str::random(10),
            'subcategory_id' => 1,
            'name' => Str::random(10),
            'slug' => Str::random(10),
        ]);
    }
}
