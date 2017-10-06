<?php

use Illuminate\Database\Seeder;

class ShippingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('shippings')->insert([
            [
                'name' => 'Retiro en Sucursal Belgrano',
                'price' => 0,
                'description' => 'Soldado de la Independencia 1115 de Lunes a Viernes de 12 a 20 hs.',
                'pickup' => 1,
                'enabled' => 1
            ],
            [
                'name' => 'Envio en Capital Federal',
                'price' => 160,
                'description' => '',
                'pickup' => 0,
                'enabled' => 1
            ],
            [
                'name' => 'Envio por OCA',
                'price' => 160,
                'description' => '',
                'pickup' => 0,
                'enabled' => 0
            ],
            [
                'name' => 'Retiro en Sucursal Mataderos',
                'price' => 0,
                'description' => 'Larrazabal 1336 de Lunes a Viernes de 9 a 19 hs.',
                'pickup' => 1,
                'enabled' => 1
            ],
            [
                'name' => 'Retiro en Sucursal Chacarita',
                'price' => 0,
                'description' => 'Santos Dumont 4819 de Lunes a Viernes de 9 a 17 hs.',
                'pickup' => 1,
                'enabled' => 1
            ],
            [
                'name' => 'Envio gratuito',
                'price' => 0,
                'description' => 'Envio gratuito para combos',
                'pickup' => 0,
                'enabled' => 0
            ]
        ]);
    }
}
