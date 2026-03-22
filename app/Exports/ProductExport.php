<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function query()
    {
        return Product::query()->with('gudang')->orderBy('merk');
    }

    public function headings(): array
    {
        return [
            'No',
            'Merk',
            'Ukuran',
            'Pcs/Karton',
            'Harga Karton (Rp)',
            'Stok Karton',
            'Total Pcs',
            'Gudang',
            'Supplier',
        ];
    }

    public function map($product): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $product->merk,
            $product->ukuran,
            $product->pcs_per_karton,
            $product->harga_karton,
            $product->stok_karton,
            $product->total_pcs,
            $product->gudang?->nama ?? '-',
            $product->supplier ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF4F46E5']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
