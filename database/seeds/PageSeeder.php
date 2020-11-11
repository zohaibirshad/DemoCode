<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pages')->insert([
            'storename' => Str::random(10),
            'title' => 'About Us',
            'slug' => 'about',
            'details' => Str::random(50),
        ]);
    }
}
