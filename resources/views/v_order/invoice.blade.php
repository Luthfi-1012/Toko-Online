<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #333;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ccc;
        }
        table tr td, table tr th {
            padding: 8px;
            font-weight: normal;
            border: 1px solid #ccc;
        }
        table th {
            background-color: #f7f7f7;
            font-weight: bold;
        }
        .header-table, .customer-table {
            border: none;
            margin-bottom: 15px;
        }
        .header-table td, .customer-table td {
            border: none;
            padding: 4px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td align="left" width="50%">
                <img src="{{ asset('frontend/img/logo.png') }}" alt="Logo" style="max-height: 45px;">
            </td>
            <td align="right" width="50%">
                <h2 style="margin: 0;">INVOICE</h2>
                <strong>ID Pesanan:</strong> #{{ $order->id }}<br>
                <strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}
            </td>
        </tr>
    </table>

    <table class="customer-table">
        <tr>
            <td align="left" valign="top" width="50%">
                <h4 style="margin-top: 0;">Data Pelanggan</h4>
                <strong>Nama:</strong> {{ $order->customer->user->nama ?? '-' }}<br>
                <strong>Email:</strong> {{ $order->customer->user->email ?? '-' }}<br>
                <strong>No. HP:</strong> {{ $order->customer->user->hp ?? '-' }}<br>
                <strong>Alamat:</strong><br>{!! $order->alamat ?? '-' !!}<br>
                <strong>Kode Pos:</strong> {{ $order->pos ?? '-' }}
            </td>
            <td align="left" valign="top" width="50%">
                <h4 style="margin-top: 0;">Pengiriman</h4>
                <strong>Kurir:</strong> {{ strtoupper($order->kurir ?? '-') }}<br>
                <strong>Layanan:</strong> {{ $order->layanan_ongkir ?? '-' }}<br>
                <strong>Estimasi:</strong> {{ $order->estimasi_ongkir ?? '-' }} Hari<br>
                <strong>Total Berat:</strong> {{ $order->total_berat ?? 0 }} Gram<br>
                <strong>No. Resi:</strong> {{ $order->noresi ?? 'Sedang diproses' }}<br>
                <strong>Status:</strong> {{ $order->status }}
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th>Produk</th>
                <th class="text-center" width="18%">Harga Satuan</th>
                <th class="text-center" width="10%">Qty</th>
                <th class="text-right" width="20%">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalHarga = 0;
                $totalBerat = 0;
            @endphp
            @foreach ($order->orderItems as $item)
                @php
                    $totalHarga += $item->harga * $item->quantity;
                    $totalBerat += ($item->produk->berat ?? 0) * $item->quantity;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $item->produk->nama_produk ?? 'Produk' }}</strong>
                        @if ($item->produk && $item->produk->kategori)
                            <small>({{ $item->produk->kategori->nama_kategori }})</small>
                        @endif
                        <br>
                        <small>Berat: {{ $item->produk->berat ?? 0 }} Gram</small>
                    </td>
                    <td class="text-center">Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">Rp. {{ number_format($item->harga * $item->quantity, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" style="border: none;"></th>
                <th class="text-right"><strong>Subtotal:</strong></th>
                <td class="text-right">Rp. {{ number_format($totalHarga, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th colspan="3" style="border: none;"></th>
                <th class="text-right"><strong>Ongkos Kirim:</strong></th>
                <td class="text-right">Rp. {{ number_format($order->biaya_ongkir ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th colspan="3" style="border: none;"></th>
                <th class="text-right" style="background-color: #f7f7f7;"><strong>Total Bayar:</strong></th>
                <td class="text-right" style="background-color: #f7f7f7;"><strong>Rp. {{ number_format($totalHarga + ($order->biaya_ongkir ?? 0), 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
