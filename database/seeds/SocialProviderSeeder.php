<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SocialProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('social_providers')->insert([
            'user_id' => 1,
            'provider_id' => 1,
            'provider' => Str::random(10),
        ]);
    }
}
