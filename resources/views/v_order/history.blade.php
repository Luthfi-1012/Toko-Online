@extends('v_layouts.app')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="form-card-modern">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; margin-bottom: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 14px;">
                    <div>
                        <h3 style="font-size: 22px; font-weight: 800; color: var(--text-main); margin: 0;">
                            <i class="fa fa-history" style="color: var(--primary);"></i> Riwayat Pesanan Saya
                        </h3>
                        <p style="font-size: 13.5px; color: var(--text-muted); margin: 4px 0 0;">Daftar seluruh transaksi pemesanan makanan Anda</p>
                    </div>
                    <a href="{{ route('produk.all') }}" class="btn-card-detail" style="border-radius: var(--radius-full);">
                        <i class="fa fa-plus"></i> Pesan Lagi
                    </a>
                </div>

                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible" role="alert" style="border-radius: var(--radius-md);">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <i class="fa fa-check-circle"></i> <strong>{{ session('success') }}</strong>
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert" style="border-radius: var(--radius-md);">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <i class="fa fa-exclamation-circle"></i> <strong>{{ session('error') }}</strong>
                    </div>
                @endif

                @if ($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>ID Pesanan</th>
                                    <th>Tanggal</th>
                                    <th>Total Bayar</th>
                                    <th>Kurir & Resi</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>
                                            <strong style="color: var(--text-main); font-size: 14.5px;">#{{ $order->id }}</strong>
                                        </td>
                                        <td style="color: var(--text-muted); font-size: 13.5px;">
                                            {{ $order->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td style="font-weight: 800; color: var(--primary); font-variant-numeric: tabular-nums;">
                                            Rp. {{ number_format($order->total_harga + ($order->biaya_ongkir ?? 0), 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <div style="font-size: 13.5px; font-weight: 600;">{{ strtoupper($order->kurir ?? '-') }} ({{ $order->layanan_ongkir ?? '-' }})</div>
                                            <small style="color: var(--text-muted);">Resi: {{ $order->noresi ?? 'Menunggu Pengiriman' }}</small>
                                        </td>
                                        <td class="text-center">
                                            @if ($order->status == 'Paid')
                                                <span class="badge-modern badge-process"><i class="fa fa-refresh fa-spin"></i> Diproses</span>
                                            @elseif ($order->status == 'Kirim')
                                                <span class="badge-modern badge-sent"><i class="fa fa-truck"></i> Dikirim</span>
                                            @elseif ($order->status == 'Selesai')
                                                <span class="badge-modern badge-done"><i class="fa fa-check"></i> Selesai</span>
                                            @else
                                                <span class="badge-modern badge-pending">{{ $order->status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <button class="btn-card-detail" data-toggle="modal" data-target="#orderDetailModal{{ $order->id }}" style="padding: 6px 12px; font-size: 13px;">
                                                <i class="fa fa-eye"></i> Detail
                                            </button>
                                            <a href="{{ route('order.invoice', $order->id) }}" target="_blank" class="btn btn-sm btn-default" style="border-radius: var(--radius-md); padding: 6px 12px; font-weight: 600; font-size: 13px;">
                                                <i class="fa fa-print"></i> Invoice
                                            </a>

                                            <!-- Detail Modal -->
                                            <div class="modal fade" id="orderDetailModal{{ $order->id }}" tabindex="-1" role="dialog" aria-hidden="true" style="text-align: left;">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content" style="border-radius: var(--radius-lg); overflow: hidden; border: none; box-shadow: var(--shadow-xl);">
                                                        <div class="modal-header" style="background: var(--bg-muted); border-bottom: 1px solid var(--border-color); padding: 18px 24px;">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title" style="font-weight: 800; color: var(--text-main);">
                                                                Rincian Pesanan #{{ $order->id }}
                                                            </h4>
                                                        </div>
                                                        <div class="modal-body" style="padding: 24px;">
                                                            <div class="row" style="margin-bottom: 18px;">
                                                                <div class="col-sm-6">
                                                                    <p style="margin: 0; font-size: 13.5px; color: var(--text-muted);">
                                                                        <strong>Status Pesanan:</strong> {{ $order->status }}<br>
                                                                        <strong>Tanggal Transaksi:</strong> {{ $order->created_at->format('d M Y H:i') }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <p style="margin: 0; font-size: 13.5px; color: var(--text-muted);">
                                                                        <strong>Kurir / Resi:</strong> {{ strtoupper($order->kurir ?? '-') }} &bull; {{ $order->noresi ?? 'Sedang diproses' }}<br>
                                                                        <strong>Alamat Kirim:</strong> {!! $order->alamat ?? '-' !!} ({{ $order->pos ?? '-' }})
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="table-responsive">
                                                                <table class="table-modern">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Menu Produk</th>
                                                                            <th class="text-center">Jumlah</th>
                                                                            <th class="text-center">Harga</th>
                                                                            <th class="text-right">Total</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($order->orderItems as $item)
                                                                            <tr>
                                                                                <td>
                                                                                    <strong>{{ $item->produk->nama_produk ?? 'Produk' }}</strong>
                                                                                </td>
                                                                                <td class="text-center">{{ $item->quantity }}</td>
                                                                                <td class="text-center">Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                                                                                <td class="text-right" style="font-weight: 700; color: var(--primary);">
                                                                                    Rp. {{ number_format($item->harga * $item->quantity, 0, ',', '.') }}
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <th colspan="3" class="text-right" style="background: none; border: none;">Biaya Ongkir:</th>
                                                                            <th class="text-right" style="background: none; border: none;">Rp. {{ number_format($order->biaya_ongkir ?? 0, 0, ',', '.') }}</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th colspan="3" class="text-right" style="background: var(--bg-muted);">Grand Total:</th>
                                                                            <th class="text-right" style="background: var(--bg-muted); color: var(--primary); font-size: 16px;">
                                                                                Rp. {{ number_format($order->total_harga + ($order->biaya_ongkir ?? 0), 0, ',', '.') }}
                                                                            </th>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer" style="background: var(--bg-muted); border-top: 1px solid var(--border-color); padding: 14px 24px;">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: var(--radius-sm);">Tutup</button>
                                                            <a href="{{ route('order.invoice', $order->id) }}" target="_blank" class="btn-primary-modern" style="padding: 8px 18px; font-size: 13.5px; border-radius: var(--radius-sm);">
                                                                <i class="fa fa-print"></i> Cetak Invoice
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center" style="padding: 40px 0;">
                        <i class="fa fa-history" style="font-size: 56px; color: #cbd5e1; margin-bottom: 16px;"></i>
                        <h4 style="color: var(--text-main); font-weight: 700;">Belum Ada Riwayat Pesanan</h4>
                        <p style="color: var(--text-muted); margin-bottom: 20px;">Anda belum melakukan pemesanan makanan.</p>
                        <a href="{{ route('produk.all') }}" class="btn-primary-modern" style="border-radius: var(--radius-full);">
                            Jelajahi Menu Makanan
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
