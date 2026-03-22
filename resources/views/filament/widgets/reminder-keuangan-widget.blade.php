<x-filament-widgets::widget>
    @php
        $reminders = $this->getReminders();
    @endphp

    @if($reminders->isNotEmpty())
    <div style="display:flex; flex-direction:column; gap:10px;">
        @foreach($reminders as $item)
            @php
                $sisa = now()->startOfDay()->diffInDays($item->reminder_tanggal, false);
                $lewat = $sisa < 0;
                $hari_ini = $sisa === 0;
                $dekat = $sisa > 0 && $sisa <= 7;

                $warna = $lewat ? '#ef4444' : ($hari_ini ? '#f59e0b' : ($dekat ? '#f59e0b' : '#3b82f6'));
                $bg    = $lewat ? '#fef2f2' : ($hari_ini ? '#fffbeb' : ($dekat ? '#fffbeb' : '#eff6ff'));
                $border = $lewat ? '#fecaca' : ($hari_ini ? '#fde68a' : ($dekat ? '#fde68a' : '#bfdbfe'));

                $label = $lewat
                    ? 'Lewat ' . abs($sisa) . ' hari!'
                    : ($hari_ini ? 'Hari ini!' : 'Sisa ' . $sisa . ' hari');
            @endphp

            <div style="display:flex; align-items:center; gap:14px; padding:14px 18px; background:{{ $bg }}; border:1px solid {{ $border }}; border-radius:12px;">
                <div style="width:40px; height:40px; border-radius:50%; background:{{ $warna }}20; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="20" height="20" fill="none" stroke="{{ $warna }}" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>

                <div style="flex:1; min-width:0;">
                    <p style="font-size:13px; font-weight:600; color:#1e293b; margin:0 0 2px;">
                        {{ $item->judul }}
                    </p>
                    <p style="font-size:12px; color:#64748b; margin:0;">
                        <span style="font-weight:500;">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</span>
                        &bull; Reminder: {{ $item->reminder_tanggal->format('d M Y') }}
                        &bull; {{ $item->kategori }}
                    </p>
                </div>

                <div style="text-align:right; flex-shrink:0;">
                    <span style="display:inline-block; padding:4px 12px; background:{{ $warna }}; color:#fff; border-radius:20px; font-size:11px; font-weight:700;">
                        {{ $label }}
                    </span>
                </div>

                <a href="{{ route('filament.admin.resources.keuangans.edit', $item) }}"
                   style="flex-shrink:0; padding:6px 12px; background:white; border:1px solid {{ $border }}; border-radius:8px; font-size:12px; color:{{ $warna }}; text-decoration:none; font-weight:600;">
                    Tandai Selesai
                </a>
            </div>
        @endforeach
    </div>
    @endif
</x-filament-widgets::widget>
