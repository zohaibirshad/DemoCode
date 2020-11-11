<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'storename' => Str::random(10),
            'name' => Str::random(10),
            'photo' => '1557677677bouquet_PNG62.png',
            'zip' => Str::random(5),
            'city' => Str::random(5),
            'country' => Str::random(5),
            'address' => Str::random(50),
            'phone' => Str::random(10),
            'fax' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(50),
        ]);
    }
}
