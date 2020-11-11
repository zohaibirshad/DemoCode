<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PageSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pagesettings')->insert([
            'storename' => Str::random(10),
            'contact_success' => 'Success! Thanks for contacting us, we will get back to you shortly.',
            'contact_email' => 'admin@shopypall.com',
            'contact_title' => Str::random(10),
            'contact_text' => Str::random(50),
            'side_title' => '<h4 class="title" style="margin-bottom: 10px; font-weight: 600; line-height: 28px; font-size: 28px;">Lets Connect</h4>',
            'side_text' => '<span style="color: rgb(51, 51, 51);">Get in touch with us</span>',
            'street' => '821 Salisbury Street , Newyork , 10601, USA',
            'phone' => '00 000 000 000',
            'fax' => '00 000 000 000',
            'email' => 'admin@shopypall.com',
            'site' => 'https://shopypall.com/',
        ]);
    }
}
