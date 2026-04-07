<?php

namespace App\Filament\Widgets;

use App\Models\Keuangan;
use Filament\Widgets\Widget;

class ReminderKeuanganWidget extends Widget
{
    protected string $view = 'filament.widgets.reminder-keuangan-widget';

    protected static ?int $sort = -1;

    protected int|string|array $columnSpan = 'full';

    public function getReminders(): \Illuminate\Database\Eloquent\Collection
    {
        return Keuangan::where('jenis', 'pemasukan')
            ->where('reminder_aktif', true)
            ->where('reminder_selesai', false)
            ->whereNotNull('reminder_tanggal')
            ->orderBy('reminder_tanggal')
            ->get();
    }

    public static function canView(): bool
    {
        $user = auth()->user();
        if (! $user?->isSuperAdmin() && ! $user?->isKeuangan()) {
            return false;
        }

        return Keuangan::where('jenis', 'pemasukan')
            ->where('reminder_aktif', true)
            ->where('reminder_selesai', false)
            ->whereNotNull('reminder_tanggal')
            ->exists();
    }
}
