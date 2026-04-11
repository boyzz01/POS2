<style>
    @media print {
        @page {
            size: 80mm auto;
            margin: 0;
        }
        body * { visibility: hidden !important; }
        #resi-print-only, #resi-print-only * { visibility: visible !important; }
        #resi-print-only {
            position: fixed;
            top: 0; left: 0;
            width: 80mm;
            padding: 4mm 4mm 6mm 4mm;
            text-align: center;
            font-family: 'Courier New', monospace;
        }
        #resi-print-only svg {
            width: 56mm !important;
            height: 56mm !important;
            display: block;
            margin: 0 auto 3mm auto;
        }
    }
</style>

{{-- Area khusus print: QR + waktu --}}
<div id="resi-print-only" style="display:none;">
    <div>
        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($transaksi->kode_transaksi) !!}
    </div>
    <div style="font-size:10px; margin-top:4px;">{{ $transaksi->created_at->format('d M Y, H:i') }}</div>
</div>

<div class="p-4">
    {{-- Resi Content --}}
    <div id="resi-print-area">
        {{-- Header --}}
        <div style="text-align:center; margin-bottom:16px;">
            <div style="font-size:18px; font-weight:700; letter-spacing:1px;">TOKO POS</div>
            <div style="font-size:12px; color:#6b7280;">Bukti Transaksi</div>
            <div style="border-top:1px dashed #d1d5db; margin-top:8px; padding-top:8px;">
                <div style="font-size:13px; font-weight:600;">{{ $transaksi->kode_transaksi }}</div>
                <div style="font-size:11px; color:#6b7280;">{{ $transaksi->created_at->format('d M Y, H:i') }}</div>
            </div>
        </div>

        {{-- QR Code --}}
        <div style="display:flex; justify-content:center; margin-bottom:16px;">
            <div style="width:140px;height:140px;">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(140)->generate($transaksi->kode_transaksi) !!}
            </div>
        </div>

        {{-- Info Transaksi --}}
        <div style="font-size:12px; margin-bottom:12px; border:1px solid #e5e7eb; border-radius:6px; overflow:hidden;">
            <div style="background:#f9fafb; padding:6px 10px; display:flex; justify-content:space-between;">
                <span style="color:#6b7280;">Kasir</span>
                <span style="font-weight:600;">{{ $transaksi->kasir?->name ?? '-' }}</span>
            </div>
            @if($transaksi->gudang)
            <div style="background:#fff; padding:6px 10px; display:flex; justify-content:space-between; border-top:1px solid #e5e7eb;">
                <span style="color:#6b7280;">Lokasi</span>
                <span style="font-weight:600;">{{ $transaksi->gudang->nama }}</span>
            </div>
            @endif
            @if($transaksi->nama_pembeli)
            <div style="background:#f9fafb; padding:6px 10px; display:flex; justify-content:space-between; border-top:1px solid #e5e7eb;">
                <span style="color:#6b7280;">Pembeli</span>
                <span style="font-weight:600;">{{ $transaksi->nama_pembeli }}</span>
            </div>
            @endif
            <div style="background:#fff; padding:6px 10px; display:flex; justify-content:space-between; border-top:1px solid #e5e7eb;">
                <span style="color:#6b7280;">Metode</span>
                <span style="font-weight:600; text-transform:capitalize;">{{ $transaksi->metode_pembayaran }}</span>
            </div>
        </div>

        {{-- Items --}}
        <div style="font-size:12px; margin-bottom:12px;">
            <div style="font-weight:600; margin-bottom:6px; padding-bottom:4px; border-bottom:1px solid #e5e7eb;">Detail Pembelian</div>
            @foreach($transaksi->items as $item)
            <div style="display:flex; justify-content:space-between; padding:4px 0; border-bottom:1px dashed #f3f4f6;">
                <div>
                    <div style="font-weight:500;">{{ $item->nama_produk }}</div>
                    @if($item->ukuran)
                    <div style="color:#9ca3af; font-size:11px;">{{ $item->ukuran }} × {{ $item->jumlah }}</div>
                    @else
                    <div style="color:#9ca3af; font-size:11px;">× {{ $item->jumlah }}</div>
                    @endif
                </div>
                <div style="font-weight:500; white-space:nowrap; margin-left:8px;">
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                </div>
            </div>
            @endforeach
        </div>

        {{-- Totals --}}
        <div style="font-size:12px; border-top:1px dashed #d1d5db; padding-top:8px;">
            <div style="display:flex; justify-content:space-between; padding:3px 0;">
                <span style="color:#6b7280;">Total</span>
                <span style="font-weight:600;">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; padding:3px 0;">
                <span style="color:#6b7280;">Bayar</span>
                <span>Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; padding:3px 0; border-top:1px solid #e5e7eb; margin-top:4px;">
                <span style="color:#6b7280;">Kembalian</span>
                <span style="font-weight:700; color:#16a34a;">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</span>
            </div>
        </div>

        @if($transaksi->catatan)
        <div style="font-size:11px; color:#6b7280; margin-top:10px; padding:6px 8px; background:#f9fafb; border-radius:4px;">
            <span style="font-weight:600;">Catatan:</span> {{ $transaksi->catatan }}
        </div>
        @endif

        <div style="text-align:center; font-size:11px; color:#9ca3af; margin-top:14px; padding-top:8px; border-top:1px dashed #d1d5db;">
            Terima kasih atas pembelian Anda!
        </div>
    </div>

    {{-- Print Button --}}
    <div style="display:flex; justify-content:center; margin-top:16px;">
        <button
            onclick="document.getElementById('resi-print-only').style.display='block'; window.print(); document.getElementById('resi-print-only').style.display='none';"
            style="background:#3b82f6; color:#fff; padding:8px 24px; border:none; border-radius:6px; font-size:13px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:6px;"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                <path d="M6 9V2h12v7H6zm0 5H4a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-2v6H6v-6zm2 4h8v-4H8v4zM18 11a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
            </svg>
            Print
        </button>
    </div>
</div>
