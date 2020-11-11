<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('blogs')->insert([
            'storename' => Str::random(10),
            'category_id' => 1,
            'photo' => '1557677677bouquet_PNG62.png',
            'title' => Str::random(10),
            'source' => Str::random(10),
            'details' => Str::random(50),
        ]);
    }
}
