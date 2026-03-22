<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class DummyProductSeeder extends Seeder
{
    public function run(): void
    {
        $foto1 = 'products/01KKX36MFHQJ1GEP04FHF0GQ3W.png';
        $foto2 = 'products/01KKYA1QXXXX5VMD1B59FRHM1Q.png';
        $gudangId = 1;

        $products = [
            ['merk' => 'Aqua', 'ukuran' => '600ml', 'harga_karton' => 45000, 'pcs_per_karton' => 24, 'foto' => $foto1],
            ['merk' => 'Aqua', 'ukuran' => '1500ml', 'harga_karton' => 55000, 'pcs_per_karton' => 12, 'foto' => $foto2],
            ['merk' => 'Le Minerale', 'ukuran' => '600ml', 'harga_karton' => 42000, 'pcs_per_karton' => 24, 'foto' => $foto1],
            ['merk' => 'Le Minerale', 'ukuran' => '1500ml', 'harga_karton' => 52000, 'pcs_per_karton' => 12, 'foto' => $foto2],
            ['merk' => 'Club', 'ukuran' => '600ml', 'harga_karton' => 40000, 'pcs_per_karton' => 24, 'foto' => $foto1],
            ['merk' => 'Club', 'ukuran' => '1500ml', 'harga_karton' => 48000, 'pcs_per_karton' => 12, 'foto' => $foto2],
            ['merk' => 'Pristine', 'ukuran' => '600ml', 'harga_karton' => 50000, 'pcs_per_karton' => 24, 'foto' => $foto1],
            ['merk' => 'Pristine', 'ukuran' => '1500ml', 'harga_karton' => 60000, 'pcs_per_karton' => 12, 'foto' => $foto2],
            ['merk' => 'Vit', 'ukuran' => '600ml', 'harga_karton' => 38000, 'pcs_per_karton' => 24, 'foto' => $foto1],
            ['merk' => 'Vit', 'ukuran' => '1500ml', 'harga_karton' => 46000, 'pcs_per_karton' => 12, 'foto' => $foto2],
        ];

        foreach ($products as $data) {
            Product::create([
                'merk'           => $data['merk'],
                'ukuran'         => $data['ukuran'],
                'harga_karton'   => $data['harga_karton'],
                'pcs_per_karton' => $data['pcs_per_karton'],
                'foto'           => $data['foto'],
                'gudang_id'      => $gudangId,
                'supplier'       => 'Supplier Default',
                'keterangan'     => null,
            ]);
        }
    }
}
