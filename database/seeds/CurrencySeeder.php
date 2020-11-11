<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->insert([
            'storename' => Str::random(10),
            'name' => 'USD',
            'sign' => '$',
            'value' => 1,
            'is_default' => 1,
        ]);
    }
}
