@extends('backend.v_layouts.app')
@section('content')

    <!-- WELCOME BANNER -->
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-radius: 12px; overflow: hidden; background: linear-gradient(135deg, #064e3b 0%, #059669 100%); color: white; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.2); margin-bottom: 24px;">
                <div class="card-body" style="padding: 28px 32px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                        <div>
                            <span class="badge badge-pill badge-warning" style="font-size: 12px; padding: 6px 14px; text-transform: uppercase; font-weight: 700; color: #78350f; background: #fef08a; margin-bottom: 10px; display: inline-block;">
                                <i class="fa fa-shield"></i> {{ Auth::user()->role == 1 ? 'Super Administrator' : 'Administrator Toko' }}
                            </span>
                            <h2 style="color: white; font-weight: 800; margin: 0 0 6px; font-size: 26px;">
                                Selamat Datang Kembali, {{ Auth::user()->nama }}! 👋
                            </h2>
                            <p style="color: rgba(255,255,255,0.85); margin: 0; font-size: 14.5px;">
                                Kelola katalog produk jajanan khas nusantara, pesanan masuk, dan pantau performa tokomu hari ini.
                            </p>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <a href="{{ route('backend.produk.create') }}" class="btn btn-warning" style="font-weight: 700; border-radius: 8px; padding: 10px 18px;">
                                <i class="fa fa-plus"></i> Tambah Produk
                            </a>
                            <a href="{{ route('beranda') }}" target="_blank" class="btn btn-outline-light" style="font-weight: 700; border-radius: 8px; padding: 10px 18px;">
                                <i class="fa fa-external-link"></i> Buka Toko
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STAT METRIC CARDS -->
    <div class="row">
        <!-- Card 1: Total Produk -->
        <div class="col-md-3 col-sm-6">
            <div class="card" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 6px rgba(0,0,0,0.04); margin-bottom: 24px;">
                <div class="card-body" style="padding: 22px;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <div style="font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Total Menu Produk</div>
                            <h3 style="font-size: 28px; font-weight: 800; color: #0f172a; margin: 0;">{{ $totalProduk ?? 0 }}</h3>
                        </div>
                        <div style="width: 52px; height: 52px; border-radius: 12px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                            <i class="mdi mdi-food"></i>
                        </div>
                    </div>
                    <div style="margin-top: 14px; padding-top: 10px; border-top: 1px solid #f1f5f9; font-size: 12.5px;">
                        <a href="{{ route('backend.produk.index') }}" style="color: #059669; font-weight: 600; text-decoration: none;">
                            Kelola Produk &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Kategori -->
        <div class="col-md-3 col-sm-6">
            <div class="card" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 6px rgba(0,0,0,0.04); margin-bottom: 24px;">
                <div class="card-body" style="padding: 22px;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <div style="font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Kategori Makanan</div>
                            <h3 style="font-size: 28px; font-weight: 800; color: #0f172a; margin: 0;">{{ $totalKategori ?? 0 }}</h3>
                        </div>
                        <div style="width: 52px; height: 52px; border-radius: 12px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                            <i class="mdi mdi-tag-multiple"></i>
                        </div>
                    </div>
                    <div style="margin-top: 14px; padding-top: 10px; border-top: 1px solid #f1f5f9; font-size: 12.5px;">
                        <a href="{{ route('backend.kategori.index') }}" style="color: #2563eb; font-weight: 600; text-decoration: none;">
                            Kelola Kategori &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Total Pelanggan -->
        <div class="col-md-3 col-sm-6">
            <div class="card" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 6px rgba(0,0,0,0.04); margin-bottom: 24px;">
                <div class="card-body" style="padding: 22px;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <div style="font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Total Pelanggan</div>
                            <h3 style="font-size: 28px; font-weight: 800; color: #0f172a; margin: 0;">{{ $totalCustomer ?? 0 }}</h3>
                        </div>
                        <div style="width: 52px; height: 52px; border-radius: 12px; background: #faf5ff; color: #9333ea; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                            <i class="mdi mdi-account-group"></i>
                        </div>
                    </div>
                    <div style="margin-top: 14px; padding-top: 10px; border-top: 1px solid #f1f5f9; font-size: 12.5px;">
                        <a href="{{ route('backend.customer.index') }}" style="color: #9333ea; font-weight: 600; text-decoration: none;">
                            Data Customer &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Total Pesanan Masuk -->
        <div class="col-md-3 col-sm-6">
            <div class="card" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 6px rgba(0,0,0,0.04); margin-bottom: 24px;">
                <div class="card-body" style="padding: 22px;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <div style="font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Pesanan Masuk</div>
                            <h3 style="font-size: 28px; font-weight: 800; color: #0f172a; margin: 0;">{{ $totalPesanan ?? 0 }}</h3>
                        </div>
                        <div style="width: 52px; height: 52px; border-radius: 12px; background: #fffbeb; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                            <i class="mdi mdi-cart"></i>
                        </div>
                    </div>
                    <div style="margin-top: 14px; padding-top: 10px; border-top: 1px solid #f1f5f9; font-size: 12.5px;">
                        <a href="{{ route('backend.pesanan.proses') }}" style="color: #d97706; font-weight: 600; text-decoration: none;">
                            Proses Pesanan &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RECENT TRANSACTIONS TABLE & SHORTCUTS -->
    <div class="row">
        <!-- RECENT ORDERS TABLE (COL-MD-8) -->
        <div class="col-lg-8 col-md-12">
            <div class="card" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 6px rgba(0,0,0,0.04); margin-bottom: 24px;">
                <div class="card-body" style="padding: 24px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px;">
                        <h4 class="card-title" style="margin: 0; font-weight: 800; color: #0f172a;">
                            <i class="mdi mdi-receipt" style="color: #059669;"></i> Transaksi Pesanan Terbaru
                        </h4>
                        <a href="{{ route('backend.pesanan.proses') }}" class="btn btn-sm btn-outline-success" style="font-weight: 600; border-radius: 6px;">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Pelanggan</th>
                                    <th>Tanggal</th>
                                    <th>Total Bayar</th>
                                    <th>Status</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pesananTerbaru as $order)
                                    <tr>
                                        <td><strong>#{{ $order->id }}</strong></td>
                                        <td>{{ $order->customer->user->nama ?? '-' }}</td>
                                        <td>{{ $order->created_at->format('d M, H:i') }}</td>
                                        <td style="font-weight: 700; color: #059669;">
                                            Rp. {{ number_format($order->total_harga + ($order->biaya_ongkir ?? 0), 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @if ($order->status == 'Paid')
                                                <span class="badge badge-primary">Diproses</span>
                                            @elseif ($order->status == 'Kirim')
                                                <span class="badge badge-info">Dikirim</span>
                                            @elseif ($order->status == 'Selesai')
                                                <span class="badge badge-success">Selesai</span>
                                            @else
                                                <span class="badge badge-warning text-white">{{ $order->status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('backend.pesanan.detail', $order->id) }}" class="btn btn-sm btn-cyan" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center" style="padding: 24px; color: #94a3b8;">
                                            Belum ada transaksi pesanan yang tercatat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- QUICK SHORTCUTS & REVENUE CARD (COL-MD-4) -->
        <div class="col-lg-4 col-md-12">
            <!-- Total Revenue Box -->
            <div class="card" style="border-radius: 12px; border: none; background: #0f172a; color: white; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);">
                <div class="card-body" style="padding: 24px;">
                    <div style="font-size: 12.5px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                        Total Pendapatan Transaksi Selesai
                    </div>
                    <h2 style="color: #34d399; font-weight: 800; font-size: 26px; margin: 0 0 16px;">
                        Rp. {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                    </h2>
                    <a href="{{ route('backend.laporan.formselesai') }}" class="btn btn-sm btn-success" style="font-weight: 700; border-radius: 6px; width: 100%;">
                        <i class="fas fa-print"></i> Cetak Rekap Pendapatan
                    </a>
                </div>
            </div>

            <!-- Quick Action Links -->
            <div class="card" style="border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <div class="card-body" style="padding: 24px;">
                    <h5 class="card-title" style="font-weight: 800; margin-bottom: 16px; color: #0f172a;">Akses Cepat Pengelolaan</h5>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('backend.produk.create') }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
                            <span><i class="mdi mdi-plus-box text-success mr-2"></i> Tambah Produk Baru</span>
                            <i class="mdi mdi-chevron-right"></i>
                        </a>
                        <a href="{{ route('backend.kategori.create') }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
                            <span><i class="mdi mdi-tag-plus text-primary mr-2"></i> Tambah Kategori Baru</span>
                            <i class="mdi mdi-chevron-right"></i>
                        </a>
                        <a href="{{ route('backend.laporan.formproses') }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
                            <span><i class="mdi mdi-file-document-box text-warning mr-2"></i> Laporan Pesanan Proses</span>
                            <i class="mdi mdi-chevron-right"></i>
                        </a>
                        <a href="{{ route('backend.user.index') }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
                            <span><i class="mdi mdi-account-key text-info mr-2"></i> Kelola Hak Akses Admin</span>
                            <i class="mdi mdi-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
