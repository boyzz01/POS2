<?php

namespace App\Filament\Resources\BarangKeluarResource\Pages;

use App\Filament\Resources\BarangKeluarResource;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateBarangKeluar extends CreateRecord
{
    protected static string $resource = BarangKeluarResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }

    protected function afterCreate(): void
    {
        $barangKeluar = $this->record;
        $items        = $barangKeluar->items;

        // Hitung total harga dari items
        $totalHarga = $items->sum('subtotal');

        // Buat Transaksi
        $transaksi = Transaksi::create([
            'kode_transaksi'    => 'BK-' . strtoupper(Str::random(8)),
            'kasir_id'          => auth()->id(),
            'gudang_id'         => $barangKeluar->gudang_id,
            'nama_pembeli'      => $barangKeluar->nama_penerima,
            'total_harga'       => $totalHarga,
            'total_bayar'       => $totalHarga,
            'kembalian'         => 0,
            'metode_pembayaran' => $barangKeluar->metode_pembayaran,
            'status'            => 'selesai',
            'catatan'           => 'Barang Keluar: ' . ($barangKeluar->tujuan ?? '-'),
        ]);

        // Buat TransaksiItem
        foreach ($items as $item) {
            TransaksiItem::create([
                'transaksi_id' => $transaksi->id,
                'product_id'   => $item->product_id,
                'nama_produk'  => $item->nama_produk,
                'ukuran'       => $item->product?->ukuran ?? '-',
                'harga_satuan' => $item->harga_satuan,
                'jumlah'       => $item->jumlah,
                'subtotal'     => $item->subtotal,
            ]);
        }

        // Update total_harga dan transaksi_id di barang keluar
        $barangKeluar->update([
            'total_harga'  => $totalHarga,
            'transaksi_id' => $transaksi->id,
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
