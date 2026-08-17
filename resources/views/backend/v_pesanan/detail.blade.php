@extends('backend.v_layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Detail Pesanan #{{ $order->id }}</h4>
                <div class="row m-t-20">
                    <div class="col-md-6">
                        <h5>Informasi Pelanggan</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th width="35%">Nama</th>
                                <td>: {{ $order->customer->user->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>: {{ $order->customer->user->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>No. HP</th>
                                <td>: {{ $order->customer->user->hp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Pesanan</th>
                                <td>: {{ $order->created_at->format('d M Y H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Informasi Pengiriman</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th width="35%">Kurir</th>
                                <td>: {{ strtoupper($order->kurir ?? '-') }}</td>
                            </tr>
                            <tr>
                                <th>Layanan & Estimasi</th>
                                <td>: {{ $order->layanan_ongkir ?? '-' }} ({{ $order->estimasi_ongkir ?? '-' }} hari)</td>
                            </tr>
                            <tr>
                                <th>Biaya Ongkir</th>
                                <td>: Rp. {{ number_format($order->biaya_ongkir ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Total Berat</th>
                                <td>: {{ $order->total_berat ?? 0 }} Gram</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <h5 class="m-t-20">Daftar Produk</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $subtotal = 0; @endphp
                            @foreach ($order->orderItems as $item)
                            @php $subtotal += ($item->harga * $item->quantity); @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if ($item->produk && $item->produk->foto)
                                        <img src="{{ asset('storage/img-produk/thumb_sm_' . $item->produk->foto) }}" width="60" alt="">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $item->produk->nama_produk ?? 'Produk dihapus' }}</td>
                                <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp. {{ number_format($item->harga * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-right">Subtotal Produk:</th>
                                <th>Rp. {{ number_format($subtotal, 0, ',', '.') }}</th>
                            </tr>
                            <tr>
                                <th colspan="5" class="text-right">Biaya Pengiriman:</th>
                                <th>Rp. {{ number_format($order->biaya_ongkir ?? 0, 0, ',', '.') }}</th>
                            </tr>
                            <tr>
                                <th colspan="5" class="text-right">Total Bayar:</th>
                                <th>Rp. {{ number_format($order->total_harga + ($order->biaya_ongkir ?? 0), 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <h5 class="m-t-30">Update Status Pesanan & Pengiriman</h5>
                <form action="{{ route('backend.pesanan.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Status Pesanan</label>
                            <select name="status" class="form-control" required>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Paid" {{ $order->status == 'Paid' ? 'selected' : '' }}>Paid / Diproses</option>
                                <option value="Kirim" {{ $order->status == 'Kirim' ? 'selected' : '' }}>Kirim (Dalam Pengiriman)</option>
                                <option value="Selesai" {{ $order->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>No. Resi Pengiriman</label>
                            <input type="text" name="noresi" value="{{ old('noresi', $order->noresi) }}" class="form-control" placeholder="Masukkan nomor resi...">
                        </div>
                        <div class="col-md-8 form-group">
                            <label>Alamat Pengiriman</label>
                            <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $order->alamat) }}</textarea>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Kode Pos</label>
                            <input type="text" name="pos" value="{{ old('pos', $order->pos) }}" class="form-control" placeholder="Kode Pos...">
                        </div>
                    </div>

                    <div class="m-t-20">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('backend.pesanan.proses') }}" class="btn btn-secondary">Kembali</a>
                        <a href="{{ route('backend.pesanan.invoice', $order->id) }}" target="_blank" class="btn btn-success float-right"><i class="fas fa-print"></i> Cetak Invoice</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
