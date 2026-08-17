<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('frontend/img/icon_univ_bsi.png') }}">
    <title>{{ $judul ?? 'Toko Online Makanan Nusantara' }} - Kuliner Khas Indonesia</title>

    <!-- Google Font Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap & Font Awesome -->
    <link type="text/css" rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.min.css') }}">

    <!-- Original CSS & Modern Design System -->
    <link type="text/css" rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('frontend/css/modern-custom.css') }}">

    <!-- Midtrans Snap JS -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>

<body>
    <!-- TOP NOTIFICATION BAR -->
    <div id="top-header">
        <div class="container">
            <div class="text-center">
                <span>🍃 <strong>Pusat Oleh-Oleh & Jajanan Tradisional Khas Nusantara</strong> — Pengiriman Cepat & Terpercaya</span>
            </div>
        </div>
    </div>

    <!-- MAIN HEADER -->
    <header id="header">
        <div class="container">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                <!-- Brand Logo -->
                <div class="header-logo">
                    <a class="brand-title" href="{{ route('beranda') }}">
                        <i class="fa fa-cutlery"></i>
                        <span>Toko<strong style="color: var(--primary);">Makanan</strong></span>
                    </a>
                </div>

                <!-- Right Actions (Cart & Account) -->
                <ul class="header-btns">
                    <!-- Cart Button -->
                    @php
                        $cartCount = 0;
                        if (Auth::check() && Auth::user()->role == 2) {
                            $pendingOrder = \App\Models\Order::where('user_id', Auth::id())->where('status', 'pending')->first();
                            if ($pendingOrder) {
                                $cartCount = $pendingOrder->orderItems->sum('quantity');
                            }
                        }
                    @endphp
                    <li>
                        <a href="{{ route('order.cart') }}" class="header-pill-btn btn-accent">
                            <i class="fa fa-shopping-basket"></i>
                            <span>Keranjang</span>
                            @if($cartCount > 0)
                                <span class="badge" style="background: #ffffff; color: var(--primary); font-weight: 800; border-radius: 10px; margin-left: 4px;">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>

                    <!-- Account / Login -->
                    @if (Auth::check())
                        <li class="header-account dropdown default-dropdown">
                            <div class="header-pill-btn dropdown-toggle" role="button" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-user-circle"></i>
                                <span>{{ Auth::user()->nama }}</span>
                                <i class="fa fa-angle-down"></i>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-right custom-menu">
                                <li>
                                    <a href="{{ route('customer.akun', ['id' => Auth::user()->id]) }}">
                                        <i class="fa fa-user"></i> Akun Profil
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('order.history') }}">
                                        <i class="fa fa-history"></i> Riwayat Pesanan
                                    </a>
                                </li>
                                <li class="divider" style="margin: 4px 0;"></li>
                                <li>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('keluar-app').submit();" style="color: #dc2626 !important;">
                                        <i class="fa fa-sign-out" style="color: #dc2626;"></i> Keluar
                                    </a>
                                    <form id="keluar-app" action="{{ route('customer.logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('auth.redirect') }}" class="header-pill-btn">
                                <i class="fa fa-google"></i>
                                <span>Masuk / Daftar</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </header>

    <!-- NAVIGATION MENU -->
    <div id="navigation">
        <div class="container">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                @php
                    $kategoriList = DB::table('kategori')->orderBy('nama_kategori', 'asc')->get();
                @endphp
                <ul class="menu-list">
                    <li class="{{ request()->routeIs('beranda') ? 'active' : '' }}"><a href="{{ route('beranda') }}"><i class="fa fa-home"></i> Beranda</a></li>
                    <li class="{{ request()->routeIs('produk.all') ? 'active' : '' }}"><a href="{{ route('produk.all') }}"><i class="fa fa-th-large"></i> Semua Produk</a></li>
                    
                    <!-- Dropdown Kategori -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-tags"></i> Kategori <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu custom-menu">
                            @foreach ($kategoriList as $k)
                                <li><a href="{{ route('produk.kategori', $k->id) }}">{{ $k->nama_kategori }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                </ul>

                <div class="hidden-xs" style="font-size: 13px; color: var(--text-muted); font-weight: 500;">
                    <i class="fa fa-shield text-success"></i> 100% Produk Halal & Higienis
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN BODY SECTION -->
    <div class="section" style="padding-top: 10px; min-height: 50vh;">
        <div class="container">
            @php
                $isCatalogPage = request()->routeIs('beranda') || request()->routeIs('produk.all') || request()->routeIs('produk.kategori');
            @endphp

            @if ($isCatalogPage)
                <!-- Catalog Layout: Sidebar Kategori (col-md-3) + Content (col-md-9) -->
                <div class="row">
                    <!-- ASIDE CATEGORY FILTER -->
                    <div class="col-md-3 col-sm-4">
                        <div class="sidebar-modern-card">
                            <h4 class="sidebar-modern-title"><i class="fa fa-filter"></i> Kategori Produk</h4>
                            <ul class="category-modern-list">
                                <li class="{{ request()->routeIs('produk.all') || (request()->routeIs('beranda') && !request()->segment(3)) ? 'active' : '' }}">
                                    <a href="{{ route('produk.all') }}">
                                        <span>Semua Kategori</span>
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </li>
                                @foreach ($kategoriList as $row)
                                    <li class="{{ request()->segment(3) == $row->id ? 'active' : '' }}">
                                        <a href="{{ route('produk.kategori', $row->id) }}">
                                            <span>{{ $row->nama_kategori }}</span>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Benefit Box -->
                        <div class="sidebar-modern-card" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-color: #bbf7d0;">
                            <div style="font-size: 24px; color: var(--primary); margin-bottom: 8px;"><i class="fa fa-truck"></i></div>
                            <h5 style="font-weight: 700; color: #166534; margin-bottom: 6px;">Jangkauan Kirim Luas</h5>
                            <p style="font-size: 13px; color: #15803d; margin: 0;">Mendukung ekspedisi JNE, TIKI, dan POS ke seluruh penjuru kota di Indonesia.</p>
                        </div>
                    </div>

                    <!-- MAIN CONTENT -->
                    <div class="col-md-9 col-sm-8">
                        @yield('content')
                    </div>
                </div>
            @else
                <!-- Full-Width Layout for Cart, Checkout, Detail, History, Profile -->
                <div class="row">
                    <div class="col-12 col-md-12">
                        @yield('content')
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- MODERN FOOTER -->
    <footer id="footer">
        <div class="container">
            <div class="row">
                <!-- Brand & Bio -->
                <div class="col-md-4 col-sm-6" style="margin-bottom: 24px;">
                    <a class="brand-title" href="#" style="color: #fff; font-size: 22px; margin-bottom: 14px; display: inline-block;">
                        <i class="fa fa-cutlery" style="color: var(--accent);"></i>
                        <span>Toko<strong style="color: #34d399;">Makanan</strong></span>
                    </a>
                    <p style="font-size: 14px; line-height: 1.7; color: #94a3b8;">
                        Pusat jajanan tradisional dan aneka makanan nusantara berkualitas. Dibuat dengan cita rasa otentik khas Indonesia yang higienis, lezat, dan terpercaya.
                    </p>
                </div>

                <!-- Navigasi Belanja -->
                <div class="col-md-3 col-sm-6" style="margin-bottom: 24px;">
                    <h4 class="footer-header">Navigasi Belanja</h4>
                    <ul class="list-links" style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 8px;"><a href="{{ route('beranda') }}">Beranda Toko</a></li>
                        <li style="margin-bottom: 8px;"><a href="{{ route('produk.all') }}">Katalog Produk</a></li>
                        <li style="margin-bottom: 8px;"><a href="{{ route('order.cart') }}">Keranjang Belanja</a></li>
                        <li style="margin-bottom: 8px;"><a href="{{ route('order.history') }}">Riwayat Pesanan</a></li>
                    </ul>
                </div>

                <!-- Kategori Pilihan -->
                <div class="col-md-2 col-sm-6" style="margin-bottom: 24px;">
                    <h4 class="footer-header">Kategori</h4>
                    <ul class="list-links" style="list-style: none; padding: 0;">
                        @foreach ($kategoriList->take(4) as $k)
                            <li style="margin-bottom: 8px;"><a href="{{ route('produk.kategori', $k->id) }}">{{ $k->nama_kategori }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Kontak & Info Pengembang -->
                <div class="col-md-3 col-sm-6" style="margin-bottom: 24px;">
                    <h4 class="footer-header">Informasi</h4>
                    <p style="font-size: 13.5px; color: #94a3b8; margin-bottom: 8px;">
                        <strong>Mata Kuliah:</strong> Web Programming III<br>
                        <strong>Project:</strong> E-Commerce Toko Makanan
                    </p>
                    <div style="margin-top: 14px;">
                        <span class="badge" style="background: rgba(255,255,255,0.1); padding: 8px 12px; font-weight: 500;">
                            <i class="fa fa-lock text-success"></i> Pembayaran Aman Midtrans
                        </span>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="footer-copyright text-center">
                <p style="margin: 0;">&copy; {{ date('Y') }} Toko Online Makanan Nusantara. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('frontend/js/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
</body>

</html>
