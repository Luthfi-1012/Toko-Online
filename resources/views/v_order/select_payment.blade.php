@extends('v_layouts.app')
@section('content')

    <!-- CHECKOUT STEP INDICATOR -->
    <div class="checkout-steps">
        <div class="step-item">
            <span class="step-number">1</span>
            <span>Keranjang Belanja</span>
        </div>
        <div class="step-divider active"></div>
        <div class="step-item">
            <span class="step-number">2</span>
            <span>Pilih Pengiriman</span>
        </div>
        <div class="step-divider active"></div>
        <div class="step-item active">
            <span class="step-number">3</span>
            <span>Pembayaran</span>
        </div>
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

    <div class="row">
        <!-- DETAIL PESANAN & PRODUK (COL-MD-8) -->
        <div class="col-md-8 col-sm-12" style="margin-bottom: 24px;">
            <div class="form-card-modern">
                <h3 class="form-card-title"><i class="fa fa-check-square-o" style="color: var(--primary);"></i> Konfirmasi Pesanan Terakhir</h3>

                @if ($order && $order->orderItems->count() > 0)
                    <div class="table-responsive">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-right">Total</th>
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
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 12px;">
                                                <img src="{{ asset('storage/img-produk/thumb_sm_' . ($item->produk->foto ?? '')) }}" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border-color);" onerror="this.src='{{ asset('backend/image/img-default.jpg') }}'">
                                                <div>
                                                    <strong style="color: var(--text-main); font-size: 14.5px;">{{ $item->produk->nama_produk ?? 'Produk' }}</strong>
                                                    <div style="font-size: 12px; color: var(--text-muted);">
                                                        Berat: {{ $item->produk->berat ?? 0 }} gr
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center" style="font-variant-numeric: tabular-nums;">
                                            Rp. {{ number_format($item->harga, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center" style="font-weight: 700;">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="text-right" style="font-weight: 800; color: var(--primary); font-variant-numeric: tabular-nums;">
                                            Rp. {{ number_format($item->harga * $item->quantity, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- ALAMAT & PENGIRIMAN INFO -->
                    <div style="background: var(--bg-muted); border-radius: var(--radius-md); padding: 18px; margin-top: 20px; border: 1px solid var(--border-color);">
                        <h5 style="font-weight: 700; color: var(--text-main); margin-bottom: 10px;">
                            <i class="fa fa-map-marker text-danger"></i> Alamat & Info Pengiriman:
                        </h5>
                        <p style="margin: 0; font-size: 14px; color: var(--text-main);">
                            <strong>Penerima:</strong> {{ Auth::user()->nama }} ({{ Auth::user()->hp ?? '-' }})<br>
                            <strong>Alamat:</strong> {!! $order->alamat ?? '-' !!} (Kode Pos: {{ $order->pos ?? '-' }})<br>
                            <strong>Ekspedisi:</strong> {{ strtoupper($order->kurir ?? '-') }} &bull; Layanan: {{ $order->layanan_ongkir ?? '-' }} (Estimasi: {{ $order->estimasi_ongkir ?? '-' }} Hari)
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- PAYMENT ACTION CARD (COL-MD-4) -->
        <div class="col-md-4 col-sm-12">
            <div class="form-card-modern" style="background: #ffffff; border-color: #cbd5e1; position: sticky; top: 90px;">
                <h3 class="form-card-title"><i class="fa fa-credit-card" style="color: var(--primary);"></i> Rincian Pembayaran</h3>

                <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14.5px;">
                    <span style="color: var(--text-muted);">Subtotal Produk:</span>
                    <strong style="color: var(--text-main); font-variant-numeric: tabular-nums;">Rp. {{ number_format($totalHarga, 0, ',', '.') }}</strong>
                </div>

                <div style="display: flex; justify-content: space-between; margin-bottom: 14px; font-size: 14.5px;">
                    <span style="color: var(--text-muted);">Biaya Pengiriman:</span>
                    <strong style="color: var(--text-main); font-variant-numeric: tabular-nums;">Rp. {{ number_format($order->biaya_ongkir ?? 0, 0, ',', '.') }}</strong>
                </div>

                <div style="display: flex; justify-content: space-between; margin-bottom: 24px; font-size: 16px; border-top: 2px dashed var(--border-color); padding-top: 16px;">
                    <span style="font-weight: 700; color: var(--text-main);">Total Tagihan:</span>
                    <strong style="font-weight: 800; color: var(--primary); font-size: 22px; font-variant-numeric: tabular-nums;">
                        Rp. {{ number_format($totalHarga + ($order->biaya_ongkir ?? 0), 0, ',', '.') }}
                    </strong>
                </div>

                <button class="btn-accent-modern" id="pay-button" style="width: 100%; border-radius: var(--radius-md); font-size: 16px; padding: 14px;">
                    <i class="fa fa-shield"></i> Bayar Sekarang
                </button>

                <div style="margin-top: 14px; text-align: center; font-size: 12.5px; color: var(--text-muted);">
                    <i class="fa fa-lock"></i> Pembayaran aman & instan via Midtrans Payment Gateway
                </div>
            </div>
        </div>
    </div>

    <!-- Midtrans Payment Script -->
    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function() {
            if (typeof window.snap === 'undefined') {
                alert('Midtrans Snap SDK sedang dimuat atau kunci API belum diset. Silakan periksa koneksi.');
                return;
            }
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    alert("Pembayaran berhasil!");
                    console.log(result);
                    window.location.href = "{{ route('order.complete') }}";
                },
                onPending: function(result) {
                    alert("Menunggu pembayaran Anda diselesaikan.");
                    console.log(result);
                    window.location.href = "{{ route('order.history') }}";
                },
                onError: function(result) {
                    alert("Pembayaran gagal atau dibatalkan.");
                    console.log(result);
                },
                onClose: function() {
                    alert('Anda menutup jendela pembayaran sebelum transaksi selesai.');
                }
            });
        });
    </script>
@endsection
