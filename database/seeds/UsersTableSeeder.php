<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'username' => 'admin',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@example.com',
                'admin' => 1,
                'password' => bcrypt('secret')
            ],
            [
                'username' => 'user_a',
                'first_name' => 'User',
                'last_name' => 'A',
                'email' => 'user_a@example.com',
                'admin' => 0,
                'password' => bcrypt('secret')
            ],
            [
                'username' => 'user_b',
                'first_name' => 'User',
                'last_name' => 'B',
                'email' => 'user_b@example.com',
                'admin' => 0,
                'password' => bcrypt('secret')
            ]
        ]);
    }
}
