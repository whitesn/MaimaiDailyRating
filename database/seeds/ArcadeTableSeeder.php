<?php

use Illuminate\Database\Seeder;

class ArcadeTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('Arcade')->insert([
            'name' => 'TIMEZONE(GALAXY MALL)'
        ]);
    }
}
