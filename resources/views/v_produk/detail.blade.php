@extends('v_layouts.app')
@section('content')

    <!-- BREADCRUMB & BACK BUTTON -->
    <div style="margin-bottom: 20px;">
        <a href="{{ route('produk.all') }}" class="btn-card-detail" style="display: inline-flex; align-items: center; gap: 8px; border-radius: var(--radius-full); padding: 8px 18px;">
            <i class="fa fa-arrow-left"></i> Kembali ke Katalog
        </a>
    </div>

    <!-- DETAIL CARD -->
    <div class="form-card-modern" style="padding: 32px;">
        <div class="row">
            <!-- PRODUCT GALLERY (COL-MD-6) -->
            <div class="col-md-6 col-sm-12" style="margin-bottom: 24px;">
                <div style="border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--border-color); background: #f8fafc; margin-bottom: 14px; box-shadow: var(--shadow-sm);">
                    <img id="main-product-img" src="{{ asset('storage/img-produk/thumb_lg_' . $row->foto) }}" alt="{{ $row->nama_produk }}" style="width: 100%; height: 380px; object-fit: cover; transition: var(--transition);" onerror="this.src='{{ asset('backend/image/img-default.jpg') }}'">
                </div>

                <!-- Thumbnails List -->
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <div onclick="document.getElementById('main-product-img').src = '{{ asset('storage/img-produk/thumb_lg_' . $row->foto) }}'" style="width: 72px; height: 72px; border-radius: var(--radius-sm); border: 2px solid var(--primary); overflow: hidden; cursor: pointer;">
                        <img src="{{ asset('storage/img-produk/thumb_sm_' . $row->foto) }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    @foreach ($fotoProdukTambahan as $item)
                        @if ($item->produk_id == $row->id)
                            <div onclick="document.getElementById('main-product-img').src = '{{ asset('storage/img-produk/' . $item->foto) }}'" style="width: 72px; height: 72px; border-radius: var(--radius-sm); border: 1px solid var(--border-color); overflow: hidden; cursor: pointer; transition: var(--transition);">
                                <img src="{{ asset('storage/img-produk/' . $item->foto) }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- PRODUCT INFO (COL-MD-6) -->
            <div class="col-md-6 col-sm-12">
                <div style="margin-bottom: 12px;">
                    <span class="badge-modern" style="background: var(--primary-light); color: var(--primary); font-size: 13px; padding: 6px 14px;">
                        <i class="fa fa-tag"></i> {{ $row->kategori->nama_kategori ?? 'Kuliner' }}
                    </span>
                </div>

                <h1 style="font-size: 28px !important; font-weight: 800; color: var(--text-main); margin-bottom: 12px; line-height: 1.25;">
                    {{ $row->nama_produk }}
                </h1>

                <div style="font-size: 28px; font-weight: 800; color: var(--primary); font-variant-numeric: tabular-nums; margin-bottom: 20px;">
                    Rp. {{ number_format($row->harga, 0, ',', '.') }}
                </div>

                <!-- SPECS PILLS -->
                <div style="display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;">
                    <div style="background: var(--bg-muted); border: 1px solid var(--border-color); padding: 8px 16px; border-radius: var(--radius-md); font-size: 13.5px;">
                        <strong style="color: var(--text-muted);"><i class="fa fa-balance-scale"></i> Berat:</strong> {{ $row->berat }} Gram
                    </div>
                    <div style="background: var(--bg-muted); border: 1px solid var(--border-color); padding: 8px 16px; border-radius: var(--radius-md); font-size: 13.5px;">
                        <strong style="color: var(--text-muted);"><i class="fa fa-cubes"></i> Stok:</strong> {{ $row->stok }} Tersedia
                    </div>
                </div>

                <!-- DESCRIPTION -->
                <div style="margin-bottom: 28px; color: var(--text-muted); font-size: 14.5px; line-height: 1.7; border-top: 1px solid var(--border-light); padding-top: 16px;">
                    <h5 style="font-weight: 700; color: var(--text-main); margin-bottom: 10px;">Deskripsi Produk</h5>
                    <div>
                        {!! $row->detail !!}
                    </div>
                </div>

                <!-- ADD TO CART ACTION -->
                <form action="{{ route('order.addToCart', $row->id) }}" method="post">
                    @csrf
                    <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                        <button type="submit" class="btn-primary-modern" style="padding: 14px 32px; font-size: 16px; border-radius: var(--radius-full);">
                            <i class="fa fa-shopping-cart"></i> Masukkan ke Keranjang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection