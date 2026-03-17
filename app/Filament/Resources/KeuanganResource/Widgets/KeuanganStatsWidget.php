<?php

namespace App\Filament\Resources\KeuanganResource\Widgets;

use App\Models\Keuangan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KeuanganStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $pemasukan   = Keuangan::where('jenis', 'pemasukan')->sum('jumlah');
        $pengeluaran = Keuangan::where('jenis', 'pengeluaran')->sum('jumlah');
        $saldo       = $pemasukan - $pengeluaran;

        $pemasukanBulanIni   = Keuangan::where('jenis', 'pemasukan')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('jumlah');

        $pengeluaranBulanIni = Keuangan::where('jenis', 'pengeluaran')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('jumlah');

        return [
            Stat::make('Total Pemasukan', 'Rp ' . number_format($pemasukan, 0, ',', '.'))
                ->description('Bulan ini: Rp ' . number_format($pemasukanBulanIni, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Pengeluaran', 'Rp ' . number_format($pengeluaran, 0, ',', '.'))
                ->description('Bulan ini: Rp ' . number_format($pengeluaranBulanIni, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),

            Stat::make('Saldo', 'Rp ' . number_format(abs($saldo), 0, ',', '.'))
                ->description($saldo >= 0 ? 'Surplus' : 'Defisit')
                ->descriptionIcon($saldo >= 0 ? 'heroicon-m-check-circle' : 'heroicon-m-exclamation-circle')
                ->color($saldo >= 0 ? 'success' : 'warning'),
        ];
    }
}
