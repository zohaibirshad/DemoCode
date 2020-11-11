<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('faqs')->insert([
            'storename' => Str::random(10),
            'title' => 'Right my front it wound cause fully',
            'details' => '<span style="color: rgb(70, 85, 65); font-family: &quot;Open Sans&quot;, sans-serif; font-size: 16px;">Nam enim risus, molestie et, porta ac, aliquam ac, risus. Quisque lobortis. Phasellus pellentesque purus in massa. Aenean in pede. Phasellus ac libero ac tellus pellentesque semper. Sed ac felis. Sed commodo, magna quis lacinia ornare, quam ante aliquam nisi, eu iaculis leo purus venenatis dui.</span><br>',
            'status' => 1,
        ]);
    }
}
