<?php

use Illuminate\Database\Seeder;

class SizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sizes')->insert([
            [
                'name' => 'small',
                'max_frame' => 45,
                'max_poster' => 45,
                'max_disc' => 30,
                'max_cutoff' => 30,
                'minimum_pixels' => 1276
            ],
            [
                'name' => 'medium',
                'max_frame' => 60,
                'max_poster' => 60,
                'max_disc' => 45,
                'max_cutoff' => 45,
                'minimum_pixels' => 1701
            ],
            [
                'name' => 'large',
                'max_frame' => 85,
                'max_poster' => 85,
                'max_disc' => 65,
                'max_cutoff' => 65,
                'minimum_pixels' => 2409
            ],
            [
                'name' => 'xl',
                'max_frame' => 120,
                'max_poster' => 120,
                'max_disc' => 100,
                'max_cutoff' => 100,
                'minimum_pixels' => 3118
            ]
        ]);
    }
}
