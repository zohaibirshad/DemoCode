<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('generalsettings')->insert([
            'storename' => Str::random(10),
            'logo' => '1598999712Shopypall_logo.png',
            'favicon' => '1595889761noun_Shopping Cart_106006.png',
            'title' => 'Shopypall',
            'footer' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae',
            'copyright' => 'COPYRIGHT © 2020. All Rights Reserved By <a href="http://shopypall.com/"><u>shopypall.com</u></a>',
            'loader' => '1564224328loading3.gif',
            'is_report' => 1,
        ]);
    }
}
