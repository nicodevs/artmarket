<?php

use Illuminate\Database\Seeder;

class FramesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('frames')->insert([
            [
                'name' => 'Marco negro',
                'thumbnail' => 'white.jpg',
                'border' => 'white_border.png'
            ],
            [
                'name' => 'Marco madera',
                'thumbnail' => 'wood.jpg',
                'border' => 'wood_border.png'
            ],
            [
                'name' => 'Marco blanco',
                'thumbnail' => 'black.jpg',
                'border' => 'black_border.png'
            ]
        ]);
    }
}
