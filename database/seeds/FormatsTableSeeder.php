<?php

use Illuminate\Database\Seeder;

class FormatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('formats')->insert([
            [
                'name' => 'Cuadro small',
                'description' => 'Cuadro chico vidriado con marco de madera',
                'size' => 'small',
                'type' => 'FRAME',
                'fixed' => 0,
                'enabled' => 1,
                'price' => 700,
                'cost' => 200,
                'frame_price' => 100,
                'frame_cost' => 40,
                'glass_price' => 220,
                'glass_cost' => 80,
                'pack_price' => 250,
                'pack_cost' => 75
            ],
            [
                'name' => 'Cuadro medium',
                'description' => 'Cuadro mediano vidriado con marco de madera',
                'size' => 'medium',
                'type' => 'FRAME',
                'fixed' => 0,
                'enabled' => 1,
                'price' => 700,
                'cost' => 100,
                'frame_price' => 180,
                'frame_cost' => 60,
                'glass_price' => 220,
                'glass_cost' => 80,
                'pack_price' => 450,
                'pack_cost' => 100
            ],
            [
                'name' => 'Cuadro large',
                'description' => 'Cuadro grande vidriado con marco de madera',
                'size' => 'large',
                'type' => 'FRAME',
                'fixed' => 0,
                'enabled' => 1,
                'price' => 440,
                'cost' => 100,
                'frame_price' => 280,
                'frame_cost' => 100,
                'glass_price' => 220,
                'glass_cost' => 80,
                'pack_price' => 500,
                'pack_cost' => 150
            ],
            [
                'name' => 'Poster small',
                'description' => 'Poster chico',
                'size' => 'small',
                'type' => 'POSTER',
                'fixed' => 1,
                'enabled' => 0,
                'price' => 215,
                'cost' => 30,
                'frame_price' => 0,
                'frame_cost' => 0,
                'glass_price' => 0,
                'glass_cost' => 0,
                'pack_price' => 25,
                'pack_cost' => 5
            ],
            [
                'name' => 'Poster medium',
                'description' => 'Poster mediano',
                'size' => 'medium',
                'type' => 'POSTER',
                'fixed' => 1,
                'enabled' => 0,
                'price' => 255,
                'cost' => 35,
                'frame_price' => 0,
                'frame_cost' => 0,
                'glass_price' => 0,
                'glass_cost' => 0,
                'pack_price' => 25,
                'pack_cost' => 5
            ],
            [
                'name' => 'Poster large',
                'description' => 'Poster grande',
                'size' => 'large',
                'type' => 'POSTER',
                'fixed' => 1,
                'enabled' => 0,
                'price' => 295,
                'cost' => 40,
                'frame_price' => 0,
                'frame_cost' => 0,
                'glass_price' => 0,
                'glass_cost' => 0,
                'pack_price' => 25,
                'pack_cost' => 5
            ],
            [
                'name' => 'Cuadro XL',
                'description' => 'Cuadro extra grande vidriado con marco de madera',
                'size' => 'xl',
                'type' => 'FRAME',
                'fixed' => 0,
                'enabled' => 1,
                'price' => 380,
                'cost' => 100,
                'frame_price' => 280,
                'frame_cost' => 80,
                'glass_price' => 220,
                'glass_cost' => 100,
                'pack_price' => 500,
                'pack_cost' => 150
            ],
            [
                'name' => 'Poster XL',
                'description' => 'Poster extra grande',
                'size' => 'xl',
                'type' => 'POSTER',
                'fixed' => 1,
                'enabled' => 0,
                'price' => 455,
                'cost' => 60,
                'frame_price' => 0,
                'frame_cost' => 0,
                'glass_price' => 0,
                'glass_cost' => 0,
                'pack_price' => 25,
                'pack_cost' => 5
            ],
            [
                'name' => 'Disco small',
                'description' => 'Disco small',
                'size' => 'small',
                'type' => 'DISK',
                'fixed' => 1,
                'enabled' => 1,
                'price' => 100,
                'cost' => 50,
                'frame_price' => 0,
                'frame_cost' => 0,
                'glass_price' => 100,
                'glass_cost' => 50,
                'pack_price' => 80,
                'pack_cost' => 50
            ],
            [
                'name' => 'Disco medium',
                'description' => 'Disco medium',
                'size' => 'medium',
                'type' => 'DISK',
                'fixed' => 1,
                'enabled' => 1,
                'price' => 200,
                'cost' => 125,
                'frame_price' => 0,
                'frame_cost' => 0,
                'glass_price' => 200,
                'glass_cost' => 125,
                'pack_price' => 120,
                'pack_cost' => 75
            ],
            [
                'name' => 'Disco large',
                'description' => 'Disco large',
                'size' => 'large',
                'type' => 'DISK',
                'fixed' => 1,
                'enabled' => 1,
                'price' => 300,
                'cost' => 150,
                'frame_price' => 0,
                'frame_cost' => 0,
                'glass_price' => 300,
                'glass_cost' => 150,
                'pack_price' => 200,
                'pack_cost' => 75
            ],
            [
                'name' => 'Disco XL',
                'description' => 'Disco XL',
                'size' => 'xl',
                'type' => 'DISK',
                'fixed' => 1,
                'enabled' => 1,
                'price' => 500,
                'cost' => 200,
                'frame_price' => 0,
                'frame_cost' => 0,
                'glass_price' => 500,
                'glass_cost' => 200,
                'pack_price' => 500,
                'pack_cost' => 50
            ],
            [
                'name' => 'Cut Off small',
                'description' => 'Cut Off small',
                'size' => 'small',
                'type' => 'CUTOFF',
                'fixed' => 1,
                'enabled' => 0,
                'price' => 100,
                'cost' => 50,
                'frame_price' => 0,
                'frame_cost' => 0,
                'glass_price' => 100,
                'glass_cost' => 50,
                'pack_price' => 100,
                'pack_cost' => 50
            ],
            [
                'name' => 'Cut Off medium',
                'description' => 'Cut Off medium',
                'size' => 'medium',
                'type' => 'CUTOFF',
                'fixed' => 1,
                'enabled' => 0,
                'price' => 200,
                'cost' => 100,
                'frame_price' => 0,
                'frame_cost' => 0,
                'glass_price' => 200,
                'glass_cost' => 100,
                'pack_price' => 200,
                'pack_cost' => 100
            ],
            [
                'name' => 'Cut Off large',
                'description' => 'Cut Off large',
                'size' => 'large',
                'type' => 'CUTOFF',
                'fixed' => 1,
                'enabled' => 0,
                'price' => 300,
                'cost' => 150,
                'frame_price' => 0,
                'frame_cost' => 0,
                'glass_price' => 300,
                'glass_cost' => 150,
                'pack_price' => 300,
                'pack_cost' => 150
            ],
            [
                'name' => 'Cut Off XL',
                'description' => 'Cut Off XL',
                'size' => 'xl',
                'type' => 'CUTOFF',
                'fixed' => 1,
                'enabled' => 0,
                'price' => 400,
                'cost' => 200,
                'frame_price' => 0,
                'frame_cost' => 0,
                'glass_price' => 400,
                'glass_cost' => 200,
                'pack_price' => 400,
                'pack_cost' => 200
            ]
        ]);
    }
}
