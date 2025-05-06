<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'national_id' => '12345674796321',
            'age' => 28,
        ]);

        User::create([
            'name' => 'agent User',
            'email' => 'agent@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'agent',
            'national_id' => '17445678996321',
            'age' => 28,
            'code'=> 1,

        ]);
        User::create([
            'name' => ' abdo',
            'email' => 'abdo@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'agent',
            'national_id' => '174456784596321',
            'age' => 28,
            'code'=> 2,
        ]);
        User::create([
            'name' => 'provider User',
            'email' => 'provider@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'provider',
            'national_id' => '123456775296321',
            'age' => 28,
            'code'=>1,

        ]);
        User::create([
            'name' => 'mazen',
            'email' => 'mazen@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'provider',
            'national_id' => '1234567451296321',
            'age' => 28,
            'code'=>2,
        ]);
    }
}
