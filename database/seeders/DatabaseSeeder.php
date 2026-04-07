<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Gudang;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Admin user — super admin, akses semua gudang
        User::firstOrCreate(
            ['email' => 'admin@mbg.test'],
            [
                'name'     => 'Admin MBG',
                'password' => Hash::make('password'),
                'role'     => 'super_admin',
            ]
        );

        // Admin Gudang Medan
        $gudangMedan = Gudang::where('nama', 'Gudang Medan')->first();
        if ($gudangMedan) {
            User::firstOrCreate(
                ['email' => 'admin.medan@mbg.test'],
                [
                    'name'      => 'Admin Medan',
                    'password'  => Hash::make('password'),
                    'role'      => 'admin',
                    'gudang_id' => $gudangMedan->id,
                ]
            );
        }

        // Kasir Gudang Medan
        if ($gudangMedan) {
            User::firstOrCreate(
                ['email' => 'kasir.medan@mbg.test'],
                [
                    'name'      => 'Kasir Medan',
                    'password'  => Hash::make('password'),
                    'role'      => 'kasir',
                    'gudang_id' => $gudangMedan->id,
                ]
            );
        }

        // Admin Gudang Stabat
        $gudangStabat = Gudang::where('nama', 'Gudang Stabat')->first();
        if ($gudangStabat) {
            User::firstOrCreate(
                ['email' => 'admin.stabat@mbg.test'],
                [
                    'name'      => 'Admin Stabat',
                    'password'  => Hash::make('password'),
                    'role'      => 'admin',
                    'gudang_id' => $gudangStabat->id,
                ]
            );
        }

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
