<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(ArcadeTableSeeder::class);
        $this->call(GrabberTableSeeder::class);
    }
}
