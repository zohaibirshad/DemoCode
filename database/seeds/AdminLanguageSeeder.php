<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminLanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_languages')->insert([
            'storename' => Str::random(10),
            'language' => 'English',
            'file' => '1567232745AoOcvCtY.json',
            'name' => '1567232745AoOcvCtY',
        ]);
    }
}
