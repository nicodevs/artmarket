<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(FramesTableSeeder::class);
        $this->call(ShippingsTableSeeder::class);
        $this->call(SizesTableSeeder::class);
        $this->call(StatusTableSeeder::class);
    }
}
