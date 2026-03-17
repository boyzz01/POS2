<?php

namespace App\Filament\Widgets;

use App\Models\Keuangan;
use App\Models\Transaksi;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $bulan = now()->month;
        $tahun = now()->year;
        $bulanLalu = now()->subMonth()->month;
        $tahunLalu = now()->subMonth()->year;

        // Pemasukan (dari Keuangan)
        $pemasukanTotal    = Keuangan::where('jenis', 'pemasukan')->sum('jumlah');
        $pemasukanBulanIni = Keuangan::where('jenis', 'pemasukan')
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->sum('jumlah');
        $pemasukanBulanLalu = Keuangan::where('jenis', 'pemasukan')
            ->whereMonth('tanggal', $bulanLalu)->whereYear('tanggal', $tahunLalu)->sum('jumlah');

        // Pengeluaran (dari Keuangan)
        $pengeluaranTotal    = Keuangan::where('jenis', 'pengeluaran')->sum('jumlah');
        $pengeluaranBulanIni = Keuangan::where('jenis', 'pengeluaran')
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->sum('jumlah');

        // Saldo
        $saldo = $pemasukanTotal - $pengeluaranTotal;

        // Omzet POS (dari Transaksi selesai)
        $omzetBulanIni  = Transaksi::where('status', 'selesai')
            ->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->sum('total_harga');
        $omzetBulanLalu = Transaksi::where('status', 'selesai')
            ->whereMonth('created_at', $bulanLalu)->whereYear('created_at', $tahunLalu)->sum('total_harga');
        $transaksiHariIni = Transaksi::where('status', 'selesai')
            ->whereDate('created_at', today())->count();

        // Trend omzet
        $omzetTrend = $omzetBulanLalu > 0
            ? round((($omzetBulanIni - $omzetBulanLalu) / $omzetBulanLalu) * 100, 1)
            : 0;

        // Trend pemasukan
        $pemasukanTrend = $pemasukanBulanLalu > 0
            ? round((($pemasukanBulanIni - $pemasukanBulanLalu) / $pemasukanBulanLalu) * 100, 1)
            : 0;

        // Total produk aktif
        $totalProduk = Product::where('harga_karton', '>', 0)->count();

        return [
            Stat::make('Omzet Bulan Ini', 'Rp ' . number_format($omzetBulanIni, 0, ',', '.'))
                ->description($transaksiHariIni . ' transaksi hari ini · ' . ($omzetTrend >= 0 ? '+' : '') . $omzetTrend . '% vs bulan lalu')
                ->descriptionIcon($omzetTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($omzetTrend >= 0 ? 'success' : 'warning')
                ->chart($this->getOmzetChart()),

            Stat::make('Pemasukan Bulan Ini', 'Rp ' . number_format($pemasukanBulanIni, 0, ',', '.'))
                ->description(($pemasukanTrend >= 0 ? '+' : '') . $pemasukanTrend . '% vs bulan lalu')
                ->descriptionIcon($pemasukanTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color('success')
                ->chart($this->getPemasukanChart()),

            Stat::make('Pengeluaran Bulan Ini', 'Rp ' . number_format($pengeluaranBulanIni, 0, ',', '.'))
                ->description('Total semua: Rp ' . number_format($pengeluaranTotal, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),

            Stat::make('Saldo Kas', 'Rp ' . number_format(abs($saldo), 0, ',', '.'))
                ->description($saldo >= 0 ? 'Surplus' : 'Defisit')
                ->descriptionIcon($saldo >= 0 ? 'heroicon-m-check-circle' : 'heroicon-m-exclamation-circle')
                ->color($saldo >= 0 ? 'success' : 'danger'),

            Stat::make('Total Produk Aktif', $totalProduk . ' produk')
                ->description('Memiliki harga jual')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('info'),

            Stat::make('Total Transaksi POS', Transaksi::where('status', 'selesai')->count() . ' transaksi')
                ->description('Omzet: Rp ' . number_format(Transaksi::where('status', 'selesai')->sum('total_harga'), 0, ',', '.'))
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary'),
        ];
    }

    private function getOmzetChart(): array
    {
        return collect(range(6, 0))->map(function ($i) {
            $date = now()->subMonths($i);
            return (int) Transaksi::where('status', 'selesai')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_harga');
        })->toArray();
    }

    private function getPemasukanChart(): array
    {
        return collect(range(6, 0))->map(function ($i) {
            $date = now()->subMonths($i);
            return (int) Keuangan::where('jenis', 'pemasukan')
                ->whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->sum('jumlah');
        })->toArray();
    }
}
