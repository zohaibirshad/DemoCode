<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('attributes')->insert([
            'storename' => Str::random(10),
            'attributable_id' => 1,
            'name' => Str::random(5),
            'input_name' => Str::random(10),
        ]);
    }
}
