<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SocialSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('socialsettings')->insert([
            'storename' => Str::random(10),
            'facebook' => '//www.facebook.com/',
            'gplus' => '//plus.google.com/',
            'twitter' => '//twitter.com/',
            'linkedin' => '//www.linkedin.com/',
            'dribble' => '//dribbble.com/',
            'f_check' => 1,
            'g_check' => 1,
        ]);
    }
}
