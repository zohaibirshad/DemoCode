<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SeoToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('seotools')->insert([
            'storename' => Str::random(10),
            'google_analytics' => '<script>//Google Analytics Scriptfffffffffffffffffffffffssssfffffs</script>',
            'meta_keys' => 'shopypall, shopypall store,multivendor',
            'pixel' => '<script>//Facebook Pixel Script</script>',
        ]);
    }
}
