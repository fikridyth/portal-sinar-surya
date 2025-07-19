<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $collections = [
            [ 'name' => 'LO HARYANTO', 'username' => 'EDP', 'email' => 'tes@gmail.com', 'password' => bcrypt('ADMIN'), 'role' => 'SUPER_USER' ],
            [ 'name' => 'LISTI', 'username' => 'ADMIN', 'email' => 'tes2@gmail.com', 'password' => bcrypt('ADMIN'), 'role' => 'ADMIN' ],
        ];

        collect($collections)->each(function ($data) {
            User::create($data);
        });
    }
}
