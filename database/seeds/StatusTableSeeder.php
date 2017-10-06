<?php

use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status')->insert([
            [
                'name' => 'PENDING_PAYMENT',
                'description' => 'Esperando pago',
                'sequence' => 0
            ],
            [
                'name' => 'PAYMENT_ERROR',
                'description' => 'Error en el pago',
                'sequence' => 1
            ],
            [
                'name' => 'PAID',
                'description' => 'Pagado',
                'sequence' => 2
            ],
            [
                'name' => 'PRINTING',
                'description' => 'En impresion',
                'sequence' => 3
            ],
            [
                'name' => 'FRAMING',
                'description' => 'En enmarcado',
                'sequence' => 4
            ],
            [
                'name' => 'READY',
                'description' => 'Listo para enviar',
                'sequence' => 5
            ],
            [
                'name' => 'SENT',
                'description' => 'Enviado',
                'sequence' => 6
            ],
            [
                'name' => 'RECEIVED',
                'description' => 'Recibido',
                'sequence' => 7
            ]
        ]);
    }
}
