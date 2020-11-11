<?php

use Illuminate\Database\Seeder;

class AdminUserConversationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_user_conversations')->insert([
            'storename' => Str::random(10),
            'subject' => Str::random(10),
            'user_id' => 1,
            'message' => Str::random(50),
            'type' => 'Ticket',
        ]);
    }
}
