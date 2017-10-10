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
                'description' => '',
                'thumbnail' => 'white.jpg',
                'border' => 'white_border.png',
                'border_mobile' => 'white_border.png'
            ],
            [
                'name' => 'Marco madera',
                'description' => '',
                'thumbnail' => 'wood.jpg',
                'border' => 'wood_border.png',
                'border_mobile' => 'wood_border.png'
            ],
            [
                'name' => 'Marco blanco',
                'description' => '',
                'thumbnail' => 'black.jpg',
                'border' => 'black_border.png',
                'border_mobile' => 'black_border.png'
            ]
        ]);
    }
}
