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

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user?->isKasir() || $user?->isSuperAdmin() ?? false;
    }

    public string $search            = '';
    public string $activeTab         = 'semua';
    public array  $cart              = [];
    public string $totalBayar        = '';
    public string $catatan           = '';
    public string $metodePembayaran  = 'transfer';
    public string $namaPembeli       = '';
    public ?int   $gudangId          = null;
    public bool   $showResi          = false;
    public ?int   $transaksiResiId   = null;

    public function getTransaksiResiProperty(): ?Transaksi
    {
        if (! $this->transaksiResiId) return null;
        return Transaksi::with(['kasir', 'gudang', 'items'])->find($this->transaksiResiId);
    }

    public function tutupResi(): void
    {
        $this->showResi = false;
        $this->transaksiResiId = null;
        $this->clearCart();
    }

    public function updatedGudangId(): void
    {
        $this->resetErrorBag('gudangId');
    }

    public function updatedNamaPembeli(): void
    {
        $this->resetErrorBag('namaPembeli');
    }

    public function updatedTotalBayar(): void
    {
        $this->resetErrorBag('totalBayar');
    }

    public function mount(): void
    {
        $user = auth()->user();
        if ($user && $user->gudang_id) {
            $this->gudangId  = $user->gudang_id;
            $this->activeTab = $user->gudang->nama ?? 'semua';
        }
    }

    public function getGudangsProperty()
    {
        $user = auth()->user();
        if ($user && $user->gudang_id) {
            return Gudang::where('id', $user->gudang_id)->get();
        }
        return Gudang::where('aktif', true)->orderBy('nama')->get();
    }

    public function getProductsProperty()
    {
        $user = auth()->user();

        return Product::query()
            ->with('gudang')
            ->when($user?->gudang_id, fn ($q) => $q->where('gudang_id', $user->gudang_id))
            ->when(!$user?->gudang_id && $this->activeTab !== 'semua', fn ($q) => $q
                ->whereHas('gudang', fn ($q2) => $q2->where('nama', $this->activeTab))
            )
            ->when($this->search, fn ($q) => $q
                ->where('merk', 'like', "%{$this->search}%")
                ->orWhere('ukuran', 'like', "%{$this->search}%")
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

        $key           = (string) $productId;
        $jumlahDiCart  = isset($this->cart[$key]) ? $this->cart[$key]['jumlah'] : 0;
        $stok          = $product->stok_karton;

        if ($jumlahDiCart >= $stok) {
            Notification::make()
                ->title('Stok tidak cukup!')
                ->body("Stok {$product->merk} hanya {$stok} karton.")
                ->warning()
                ->send();
            return;
        }

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
                'stok'         => $stok,
                'jumlah'       => 1,
                'subtotal'     => $product->harga_karton,
            ];
        }

        $this->cart = $this->cart;
    }

    public function incrementQty(string $key): void
    {
        if (! isset($this->cart[$key])) return;

        $stok = $this->cart[$key]['stok'] ?? PHP_INT_MAX;
        if ($this->cart[$key]['jumlah'] >= $stok) {
            Notification::make()
                ->title('Stok tidak cukup!')
                ->body('Jumlah melebihi stok tersedia.')
                ->warning()
                ->send();
            return;
        }

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

    public function setQty(string $key, int $qty): void
    {
        if (! isset($this->cart[$key])) return;

        if ($qty <= 0) {
            $this->removeFromCart($key);
            return;
        }

        $stok = $this->cart[$key]['stok'] ?? PHP_INT_MAX;
        if ($qty > $stok) {
            Notification::make()
                ->title('Stok tidak cukup!')
                ->body("Stok tersedia hanya {$stok} karton.")
                ->warning()
                ->send();
            $qty = $stok;
        }

        $this->cart[$key]['jumlah']   = $qty;
        $this->cart[$key]['subtotal'] = $qty * $this->cart[$key]['harga_karton'];
        $this->cart = $this->cart;
    }

    public function clearCart(): void
    {
        $this->cart             = [];
        $this->totalBayar       = '';
        $this->catatan          = '';
        $this->metodePembayaran = 'transfer';
        $this->namaPembeli      = '';
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
        $hasError = false;

        if (empty($this->cart)) {
            $this->addError('cart', 'Keranjang masih kosong, tambahkan produk terlebih dahulu.');
            $hasError = true;
        } else {
            $this->resetErrorBag('cart');
        }

        $resolvedGudangId = $this->gudangId ?: auth()->user()?->gudang_id;
        if (! $resolvedGudangId) {
            $this->addError('gudangId', 'Pilih lokasi penjualan terlebih dahulu.');
            $hasError = true;
        } else {
            $this->resetErrorBag('gudangId');
        }

        if (empty(trim($this->namaPembeli))) {
            $this->addError('namaPembeli', 'Nama pembeli wajib diisi.');
            $hasError = true;
        } else {
            $this->resetErrorBag('namaPembeli');
        }

        if ($this->bayarInt < $this->totalHarga) {
            $this->addError('totalBayar', 'Uang diterima kurang dari total harga.');
            $hasError = true;
        } else {
            $this->resetErrorBag('totalBayar');
        }

        if ($hasError) return;

        // Validasi stok sebelum proses
        foreach ($this->cart as $item) {
            $product = Product::find($item['product_id']);
            if ($product && $product->stok_karton < $item['jumlah']) {
                Notification::make()
                    ->title('Stok tidak cukup!')
                    ->body("Stok {$product->merk} hanya {$product->stok_karton} karton, diminta {$item['jumlah']} karton.")
                    ->danger()
                    ->send();
                return;
            }
        }

        $transaksi = Transaksi::create([
            'kode_transaksi' => 'TRX-' . strtoupper(Str::random(8)),
            'kasir_id'       => auth()->id(),
            'total_harga'    => $this->totalHarga,
            'total_bayar'    => $this->bayarInt,
            'kembalian'      => $this->kembalian,
            'gudang_id'          => $this->gudangId ?: auth()->user()?->gudang_id,
            'status'             => 'selesai',
            'metode_pembayaran'  => $this->metodePembayaran,
            'nama_pembeli'       => $this->namaPembeli ?: null,
            'catatan'            => $this->catatan ?: null,
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

        $this->transaksiResiId = $transaksi->id;
        $this->showResi = true;
    }
}
