@extends('v_layouts.app')
@section('content')

    <!-- CATALOG HEADER -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
        <div>
            <h3 style="font-size: 22px; font-weight: 800; margin: 0; color: var(--text-main);">Semua Produk Kuliner</h3>
            <p style="font-size: 13.5px; color: var(--text-muted); margin: 0;">Jelajahi berbagai jajanan dan makanan tradisional pilihan</p>
        </div>
        <div>
            <span class="badge-modern" style="background: var(--primary-light); color: var(--primary); font-size: 13px; padding: 6px 14px;">
                <i class="fa fa-check-circle"></i> {{ $produk->total() }} Produk
            </span>
        </div>
    </div>

    <!-- PRODUCT GRID -->
    <div class="row">
        @forelse ($produk as $row)
            <div class="col-md-4 col-sm-6 col-xs-12" style="margin-bottom: 24px;">
                <div class="product-card-modern">
                    <div class="product-img-wrapper">
                        <span class="product-badge-cat">
                            <i class="fa fa-tag"></i> {{ $row->kategori->nama_kategori ?? 'Umum' }}
                        </span>
                        <a href="{{ route('produk.detail', $row->id) }}">
                            <img src="{{ asset('storage/img-produk/thumb_md_' . $row->foto) }}" alt="{{ $row->nama_produk }}" onerror="this.src='{{ asset('backend/image/img-default.jpg') }}'">
                        </a>
                    </div>
                    
                    <div class="product-content-modern">
                        <h4 class="product-title-modern">
                            <a href="{{ route('produk.detail', $row->id) }}" title="{{ $row->nama_produk }}">
                                {{ $row->nama_produk }}
                            </a>
                        </h4>
                        
                        <div class="product-price-modern">
                            Rp. {{ number_format($row->harga, 0, ',', '.') }}
                        </div>

                        <div class="product-actions-modern">
                            <a href="{{ route('produk.detail', $row->id) }}" class="btn-card-detail">
                                <i class="fa fa-eye"></i> Detail
                            </a>
                            <form action="{{ route('order.addToCart', $row->id) }}" method="post" style="margin: 0;">
                                @csrf
                                <button type="submit" class="btn-card-order" style="width: 100%;">
                                    <i class="fa fa-cart-plus"></i> Pesan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center" style="padding: 40px 0;">
                <i class="fa fa-cutlery" style="font-size: 48px; color: #cbd5e1; margin-bottom: 14px;"></i>
                <h4>Belum ada produk yang ditemukan.</h4>
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    <div style="margin-top: 16px; margin-bottom: 30px; text-align: center;">
        {{ $produk->links('vendor.pagination.custom') }}
    </div>

@endsection