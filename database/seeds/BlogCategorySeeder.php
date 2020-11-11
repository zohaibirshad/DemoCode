<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('blog_categories')->insert([
            'storename' => Str::random(10),
            'name' => Str::random(10),
            'slug' => Str::random(5),
        ]);
    }
}
