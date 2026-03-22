<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #1e293b; }
        h2 { text-align: center; margin-bottom: 4px; color: #4f46e5; }
        p.sub { text-align: center; color: #64748b; margin: 0 0 16px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #4f46e5; color: #fff; }
        th { padding: 8px 10px; text-align: left; font-size: 11px; }
        td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 20px; font-size: 10px; color: #94a3b8; text-align: right; }
    </style>
</head>
<body>
    <h2>Daftar Produk</h2>
    <p class="sub">Dicetak: {{ now()->format('d M Y, H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Merk</th>
                <th>Ukuran</th>
                <th class="text-center">Pcs/Karton</th>
                <th class="text-right">Harga Karton</th>
                <th class="text-center">Stok (Ktn)</th>
                <th class="text-center">Total Pcs</th>
                <th>Gudang</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $i => $product)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $product->merk }}</td>
                <td>{{ $product->ukuran }}</td>
                <td class="text-center">{{ $product->pcs_per_karton }}</td>
                <td class="text-right">Rp {{ number_format($product->harga_karton, 0, ',', '.') }}</td>
                <td class="text-center">{{ $product->stok_karton }}</td>
                <td class="text-center">{{ $product->total_pcs }}</td>
                <td>{{ $product->gudang?->nama ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Total: {{ $products->count() }} produk</div>
</body>
</html>
