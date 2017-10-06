<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'name' => 'Viajes',
                'thumbnail' => '743c373818a0eb2b58f1cfdebe3712a1.jpg',
                'cover' => '774a5a6660208edb7d51c1f270eab24e.jpg'
            ],
            [
                'name' => 'Animales',
                'thumbnail' => 'a64fe72cd96376f0b7a56498fcd90c54.jpg',
                'cover' => 'b308619b89b4a7c698200d438e836d7a.png'
            ],
            [
                'name' => 'Retratos',
                'thumbnail' => '6c4cef6c03ec7482bd65062047155a06.jpg',
                'cover' => '820bf9cc097a8727c7e142f90f185f1e.jpg'
            ],
            [
                'name' => 'Text Art',
                'thumbnail' => '9ea5c7d4027d258a97cb6d889a3ac97e.jpg',
                'cover' => '58b6918a8f066b088d3dbabf6f3826f0.png'
            ],
            [
                'name' => 'Humor',
                'thumbnail' => '5b25bfb634a3e9cc13a0ee834eb058ce.jpg',
                'cover' => ''
            ],
            [
                'name' => 'Películas & TV',
                'thumbnail' => 'fb36051d3a633d0e442c9bc3ec195736.jpg',
                'cover' => 'd7c64047b85c23a54a37401a03180c33.png'
            ],
            [
                'name' => 'Ilustraciones',
                'thumbnail' => '39ef8d037d95c10a1c2607788eeb6f4c.jpg',
                'cover' => '26e2231a25cb13062416da60ed5267b9.png'
            ],
            [
                'name' => 'Vintage',
                'thumbnail' => '3709ef28850cca7a6147cf87f7ea906f.jpg',
                'cover' => ''
            ],
            [
                'name' => 'Blanco & Negro',
                'thumbnail' => '96ac8d9c482179529a8db1d4508bdfef.jpg',
                'cover' => '5d98f8e2d70aba6bdab344ac6ae121a2.png'
            ],
            [
                'name' => 'Deportes',
                'thumbnail' => '4db6b87ff2106040f511e4d3adbf9a21.jpg',
                'cover' => '5c2d6edd771fd55d190e7e0fbd414238.png'
            ],
            [
                'name' => 'Paisajes',
                'thumbnail' => 'cc84427f91e861bbc409876004058965.jpg',
                'cover' => '6820480317d4adb5d4518af63319167a.png'
            ],
            [
                'name' => 'Música',
                'thumbnail' => '7192acc10b0fab312328deedb4108242.jpg',
                'cover' => '34964cc545d997269d713a62d09daa60.png'
            ],
            [
                'name' => 'Abstracto',
                'thumbnail' => 'b2dc596f84c024516388d8d8ef328850.jpg',
                'cover' => 'c1d62f2e8177fdc642ef6e76d4678362.png'
            ],
            [
                'name' => 'Comida',
                'thumbnail' => '0279dcd164b6c9d90babb7722671d1f4.jpg',
                'cover' => '1371dd803fee3b2e504e5d14913615f9.png'
            ]
        ]);
    }
}
