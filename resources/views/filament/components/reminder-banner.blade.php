@php
    use App\Models\Keuangan;
    $u = auth()->user();
    if (! $u?->isSuperAdmin() && ! $u?->isKeuangan()) return;
    $reminders = Keuangan::where('jenis', 'pemasukan')
        ->where('reminder_aktif', true)
        ->where('reminder_selesai', false)
        ->whereNotNull('reminder_tanggal')
        ->orderBy('reminder_tanggal')
        ->get();
@endphp

@if($reminders->isNotEmpty())
<div style="padding: 0 0 12px;">
    @foreach($reminders as $item)
        @php
            $sisa  = now()->startOfDay()->diffInDays($item->reminder_tanggal, false);
            $lewat = $sisa < 0;
            $hari  = $sisa === 0;
            $dekat = $sisa > 0 && $sisa <= 7;

            [$bg, $border, $warna, $icon] = match(true) {
                $lewat => ['#fef2f2', '#fecaca', '#dc2626', '🔴'],
                $hari  => ['#fffbeb', '#fde68a', '#d97706', '🔔'],
                $dekat => ['#fffbeb', '#fde68a', '#d97706', '⚠️'],
                default => ['#eff6ff', '#bfdbfe', '#2563eb', '⏰'],
            };

            $label = $lewat ? 'Lewat ' . abs($sisa) . ' hari!' : ($hari ? 'Jatuh tempo hari ini!' : 'Sisa ' . $sisa . ' hari');
        @endphp
        <div style="display:flex; align-items:center; gap:12px; padding:10px 16px; margin-bottom:6px; background:{{ $bg }}; border:1px solid {{ $border }}; border-radius:10px; font-size:13px;">
            <span>{{ $icon }}</span>
            <span style="flex:1; color:#1e293b;">
                <strong>{{ $item->judul }}</strong>
                — Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                &bull; Jatuh tempo: {{ $item->reminder_tanggal->format('d M Y') }}
            </span>
            <span style="padding:3px 10px; background:{{ $warna }}; color:#fff; border-radius:20px; font-weight:700; font-size:11px; white-space:nowrap;">
                {{ $label }}
            </span>
            <a href="{{ route('filament.admin.resources.keuangans.edit', $item) }}"
               style="padding:4px 12px; background:white; border:1px solid {{ $border }}; border-radius:6px; color:{{ $warna }}; font-weight:600; font-size:12px; text-decoration:none; white-space:nowrap;">
                Selesai
            </a>
        </div>
    @endforeach
</div>
@endif
