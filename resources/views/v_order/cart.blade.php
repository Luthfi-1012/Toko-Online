@extends('v_layouts.app')
@section('content')

    <!-- CHECKOUT STEP INDICATOR -->
    <div class="checkout-steps">
        <div class="step-item active">
            <span class="step-number">1</span>
            <span>Keranjang Belanja</span>
        </div>
        <div class="step-divider"></div>
        <div class="step-item">
            <span class="step-number">2</span>
            <span>Pilih Pengiriman</span>
        </div>
        <div class="step-divider"></div>
        <div class="step-item">
            <span class="step-number">3</span>
            <span>Pembayaran</span>
        </div>
    </div>

    <!-- ALERTS -->
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
        <!-- CART ITEMS TABLE (COL-MD-8) -->
        <div class="col-md-8 col-sm-12" style="margin-bottom: 24px;">
            <div class="form-card-modern">
                <h3 class="form-card-title"><i class="fa fa-shopping-basket" style="color: var(--primary);"></i> Daftar Belanja Anda</h3>

                @if ($order && $order->orderItems->count() > 0)
                    <div class="table-responsive">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center" width="160">Jumlah</th>
                                    <th class="text-right">Subtotal</th>
                                    <th width="40"></th>
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
                                                <img src="{{ asset('storage/img-produk/thumb_sm_' . ($item->produk->foto ?? '')) }}" alt="" style="width: 56px; height: 56px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border-color);" onerror="this.src='{{ asset('backend/image/img-default.jpg') }}'">
                                                <div>
                                                    <strong style="color: var(--text-main); font-size: 14.5px;">{{ $item->produk->nama_produk ?? 'Produk' }}</strong>
                                                    <div style="font-size: 12px; color: var(--text-muted);">
                                                        Berat: {{ $item->produk->berat ?? 0 }} gr / item
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center" style="font-weight: 600; font-variant-numeric: tabular-nums;">
                                            Rp. {{ number_format($item->harga, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('order.updateCart', $item->id) }}" method="post" style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                                                @csrf
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control" style="width: 65px; text-align: center; padding: 6px !important; height: 34px;">
                                                <button type="submit" class="btn btn-sm btn-default" title="Perbarui Qty" style="border-radius: var(--radius-sm); height: 34px;">
                                                    <i class="fa fa-refresh text-primary"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-right" style="font-weight: 800; color: var(--primary); font-variant-numeric: tabular-nums;">
                                            Rp. {{ number_format($item->harga * $item->quantity, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('order.remove', $item->produk->id) }}" method="post" style="margin: 0;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-link text-danger" title="Hapus dari Keranjang">
                                                    <i class="fa fa-trash-o" style="font-size: 16px;"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top: 18px;">
                        <a href="{{ route('produk.all') }}" class="btn-card-detail" style="border-radius: var(--radius-full); padding: 8px 18px;">
                            <i class="fa fa-arrow-left"></i> Tambah Menu Lain
                        </a>
                    </div>
                @else
                    <div class="text-center" style="padding: 40px 0;">
                        <i class="fa fa-shopping-basket" style="font-size: 56px; color: #cbd5e1; margin-bottom: 16px;"></i>
                        <h4 style="color: var(--text-main); font-weight: 700;">Keranjang belanja Anda masih kosong</h4>
                        <p style="color: var(--text-muted); margin-bottom: 20px;">Yuk pilih jajanan dan makanan lezat khas nusantara!</p>
                        <a href="{{ route('produk.all') }}" class="btn-primary-modern" style="border-radius: var(--radius-full);">
                            Mulai Belanja Sekarang
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- SUMMARY CARD (COL-MD-4) -->
        @if ($order && $order->orderItems->count() > 0)
            <div class="col-md-4 col-sm-12">
                <div class="form-card-modern" style="background: #ffffff; border-color: #cbd5e1; position: sticky; top: 90px;">
                    <h3 class="form-card-title"><i class="fa fa-calculator" style="color: var(--accent);"></i> Ringkasan Belanja</h3>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14.5px;">
                        <span style="color: var(--text-muted);">Total Item:</span>
                        <strong style="color: var(--text-main);">{{ $order->orderItems->sum('quantity') }} Barang</strong>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14.5px;">
                        <span style="color: var(--text-muted);">Total Berat:</span>
                        <strong style="color: var(--text-main);">{{ $totalBerat }} Gram</strong>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 16px; border-top: 1px solid var(--border-light); padding-top: 14px;">
                        <span style="font-weight: 700; color: var(--text-main);">Subtotal Harga:</span>
                        <strong style="font-weight: 800; color: var(--primary); font-size: 20px; font-variant-numeric: tabular-nums;">
                            Rp. {{ number_format($totalHarga, 0, ',', '.') }}
                        </strong>
                    </div>

                    <form action="{{ route('order.select_shipping') }}" method="post">
                        @csrf
                        <input type="hidden" name="total_price" value="{{ $totalHarga }}">
                        <input type="hidden" name="total_weight" value="{{ $totalBerat }}">
                        <button type="submit" class="btn-accent-modern" style="width: 100%; border-radius: var(--radius-md); font-size: 15.5px;">
                            <span>Lanjut ke Pengiriman</span>
                            <i class="fa fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>

@endsection
