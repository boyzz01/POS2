<?php

namespace App\Filament\Pages;

use App\Models\Gudang;
use App\Models\Product;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class PosKasir extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected string $view = 'filament.pages.pos-kasir';

    protected static ?string $navigationLabel = 'Kasir / POS';

    protected static ?string $title = 'Point of Sale';

    protected static ?int $navigationSort = 0;

    public string $search      = '';
    public string $activeTab   = 'semua';
    public array  $cart        = [];
    public string $totalBayar  = '';
    public string $catatan     = '';

    public function getGudangsProperty()
    {
        return Gudang::where('aktif', true)->orderBy('nama')->get();
    }

    public function getProductsProperty()
    {
        return Product::query()
            ->with('gudang')
            ->when($this->search, fn ($q) => $q
                ->where('merk', 'like', "%{$this->search}%")
                ->orWhere('ukuran', 'like', "%{$this->search}%")
            )
            ->when($this->activeTab !== 'semua', fn ($q) => $q
                ->whereHas('gudang', fn ($q2) => $q2->where('nama', $this->activeTab))
            )
            ->where('harga_karton', '>', 0)
            ->orderBy('merk')
            ->get();
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function addToCart(int $productId): void
    {
        $product = Product::find($productId);
        if (! $product) return;

        $key = (string) $productId;

        if (isset($this->cart[$key])) {
            $this->cart[$key]['jumlah']++;
            $this->cart[$key]['subtotal'] = $this->cart[$key]['jumlah'] * $this->cart[$key]['harga_karton'];
        } else {
            $this->cart[$key] = [
                'product_id'   => $product->id,
                'foto'         => $product->foto,
                'nama_produk'  => $product->merk,
                'ukuran'       => $product->ukuran,
                'harga_karton' => $product->harga_karton,
                'jumlah'       => 1,
                'subtotal'     => $product->harga_karton,
            ];
        }

        $this->cart = $this->cart;
    }

    public function incrementQty(string $key): void
    {
        if (! isset($this->cart[$key])) return;
        $this->cart[$key]['jumlah']++;
        $this->cart[$key]['subtotal'] = $this->cart[$key]['jumlah'] * $this->cart[$key]['harga_karton'];
        $this->cart = $this->cart;
    }

    public function decrementQty(string $key): void
    {
        if (! isset($this->cart[$key])) return;

        if ($this->cart[$key]['jumlah'] <= 1) {
            $this->removeFromCart($key);
            return;
        }

        $this->cart[$key]['jumlah']--;
        $this->cart[$key]['subtotal'] = $this->cart[$key]['jumlah'] * $this->cart[$key]['harga_karton'];
        $this->cart = $this->cart;
    }

    public function removeFromCart(string $key): void
    {
        unset($this->cart[$key]);
        $this->cart = $this->cart;
    }

    public function clearCart(): void
    {
        $this->cart      = [];
        $this->totalBayar = '';
        $this->catatan   = '';
    }

    public function setNominal(int $amount): void
    {
        $this->totalBayar = (string) $amount;
    }

    public function setExact(): void
    {
        $this->totalBayar = (string) $this->totalHarga;
    }

    public function getTotalHargaProperty(): int
    {
        return collect($this->cart)->sum('subtotal');
    }

    public function getTotalItemsProperty(): int
    {
        return collect($this->cart)->sum('jumlah');
    }

    public function getBayarIntProperty(): int
    {
        return (int) $this->totalBayar;
    }

    public function getKembalianProperty(): int
    {
        return $this->bayarInt - $this->totalHarga;
    }

    public function prosesTransaksi(): void
    {
        if (empty($this->cart)) {
            Notification::make()->title('Keranjang kosong!')->warning()->send();
            return;
        }

        if ($this->bayarInt < $this->totalHarga) {
            Notification::make()
                ->title('Pembayaran kurang!')
                ->body('Uang yang diterima kurang dari total harga.')
                ->danger()
                ->send();
            return;
        }

        $transaksi = Transaksi::create([
            'kode_transaksi' => 'TRX-' . strtoupper(Str::random(8)),
            'kasir_id'       => auth()->id(),
            'total_harga'    => $this->totalHarga,
            'total_bayar'    => $this->bayarInt,
            'kembalian'      => $this->kembalian,
            'status'         => 'selesai',
            'catatan'        => $this->catatan ?: null,
        ]);

        foreach ($this->cart as $item) {
            TransaksiItem::create([
                'transaksi_id' => $transaksi->id,
                'product_id'   => $item['product_id'],
                'nama_produk'  => $item['nama_produk'],
                'ukuran'       => $item['ukuran'],
                'harga_satuan' => $item['harga_karton'],
                'jumlah'       => $item['jumlah'],
                'subtotal'     => $item['subtotal'],
            ]);
        }

        Notification::make()
            ->title('Transaksi Berhasil!')
            ->body('Kode: ' . $transaksi->kode_transaksi . ' | Kembalian: Rp ' . number_format($this->kembalian, 0, ',', '.'))
            ->success()
            ->duration(6000)
            ->send();

        $this->clearCart();
    }
}
