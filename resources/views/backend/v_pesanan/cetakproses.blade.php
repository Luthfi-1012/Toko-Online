<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judul }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ccc;
            margin-top: 10px;
        }
        table tr th, table tr td {
            padding: 8px;
            font-weight: normal;
            border: 1px solid #ccc;
        }
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .header-table {
            border: none;
            margin-bottom: 20px;
        }
        .header-table td {
            border: none;
            padding: 4px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td align="left">
                <h2>Laporan Pesanan Dalam Proses</h2>
                <strong>Periode:</strong> {{ date('d M Y', strtotime($tanggalAwal)) }} s/d {{ date('d M Y', strtotime($tanggalAkhir)) }}
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Order</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Status</th>
                <th>Kurir / Resi</th>
                <th>Total Berat</th>
                <th>Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @forelse ($cetak as $row)
            @php 
                $totalOrder = $row->total_harga + $row->biaya_ongkir;
                $grandTotal += $totalOrder;
            @endphp
            <tr>
                <td align="center">{{ $loop->iteration }}</td>
                <td align="center">#{{ $row->id }}</td>
                <td align="center">{{ $row->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $row->customer->user->nama ?? '-' }}</td>
                <td align="center">{{ $row->status }}</td>
                <td>{{ strtoupper($row->kurir ?? '-') }} / {{ $row->noresi ?? '-' }}</td>
                <td align="center">{{ $row->total_berat ?? 0 }} Gram</td>
                <td align="right">Rp. {{ number_format($totalOrder, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" align="center">Tidak ada data pesanan pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        @if (count($cetak) > 0)
        <tfoot>
            <tr>
                <th colspan="7" align="right"><strong>Total Nilai Transaksi:</strong></th>
                <th align="right"><strong>Rp. {{ number_format($grandTotal, 0, ',', '.') }}</strong></th>
            </tr>
        </tfoot>
        @endif
    </table>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
