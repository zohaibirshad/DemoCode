<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('email_templates')->insert([
            'storename' => Str::random(10),
            'email_type' => Str::random(10),
            'email_subject' => Str::random(15),
            'email_body' => Str::random(100),
        ]);
    }
}
