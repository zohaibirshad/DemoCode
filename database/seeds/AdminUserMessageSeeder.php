<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_user_messages')->insert([
            'storename' => Str::random(10),
            'conversation_id' => 1,
            'message' => Str::random(50),
        ]);
    }
}
