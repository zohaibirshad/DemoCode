<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sliders')->insert([
            'storename' => Str::random(10),
            'subtitle_text' => Str::random(10),
            'subtitle_size' => Str::random(10),
            'subtitle_color' => '#ffffff',
            'subtitle_anime' => 'slideInUp',
            'title_text' => 'Get Up to 40% Off',
        ]);
    }
}
