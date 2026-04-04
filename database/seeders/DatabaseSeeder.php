<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Admin user — akses Filament /admin
        User::firstOrCreate(
            ['email' => 'admin@mbg.test'],
            [
                'name'     => 'Admin MBG',
                'password' => Hash::make('password'),
            ]
        );

        // Customer contoh — akses storefront /login
        Customer::firstOrCreate(
            ['email' => 'customer@mbg.test'],
            [
                'name'     => 'Customer MBG',
                'phone'    => '081234567890',
                'password' => Hash::make('password'),
            ]
        );
    }
}
