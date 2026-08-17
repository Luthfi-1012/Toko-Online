@extends('v_layouts.app')

@section('content')
    @php
        $totalBerat = $totalBerat ?? ($order && $order->orderItems ? $order->orderItems->sum(function($i) { return ($i->produk->berat ?? 0) * $i->quantity; }) : 0);
        $totalHarga = $totalHarga ?? ($order && $order->orderItems ? $order->orderItems->sum(function($i) { return $i->harga * $i->quantity; }) : 0);
    @endphp
    <!-- CHECKOUT STEP INDICATOR -->
    <div class="checkout-steps">
        <div class="step-item">
            <span class="step-number">1</span>
            <span>Keranjang Belanja</span>
        </div>
        <div class="step-divider active"></div>
        <div class="step-item active">
            <span class="step-number">2</span>
            <span>Pilih Pengiriman</span>
        </div>
        <div class="step-divider"></div>
        <div class="step-item">
            <span class="step-number">3</span>
            <span>Pembayaran</span>
        </div>
    </div>

    <div class="row">
        <!-- FORM PENGIRIMAN (COL-MD-12) -->
        <div class="col-md-12">
            <div class="form-card-modern">
                <h3 class="form-card-title"><i class="fa fa-truck" style="color: var(--primary);"></i> Hitung Biaya Pengiriman & Alamat Tujuan</h3>

                <form id="shippingForm">
                    {{-- Hidden Inputs --}}
                    <input type="hidden" id="city_origin" name="city_origin">
                    <input type="hidden" id="city_origin_name" name="city_origin_name">
                    <input type="hidden" name="weight" id="weight" value="{{ $totalBerat }}">
                    <input type="hidden" name="province_name" id="province_name">
                    <input type="hidden" name="city_name" id="city_name">

                    <div class="row">
                        <!-- KOLOM KIRI: ALAMAT & POS -->
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group" style="margin-bottom: 18px;">
                                <label style="font-weight: 700; color: var(--text-main);">Alamat Lengkap Pengiriman <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="alamat" id="alamat" rows="4" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan..." required>{{ Auth::user()->customer->alamat ?? '' }}</textarea>
                            </div>

                            <div class="form-group" style="margin-bottom: 18px;">
                                <label style="font-weight: 700; color: var(--text-main);">Kode Pos <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="kode_pos" id="kode_pos" value="{{ Auth::user()->customer->pos ?? '' }}" placeholder="Contoh: 16424" required>
                            </div>
                        </div>

                        <!-- KOLOM KANAN: WILAYAH & KURIR -->
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group" style="margin-bottom: 18px;">
                                <label style="font-weight: 700; color: var(--text-main);">Provinsi Tujuan <span class="text-danger">*</span></label>
                                <select name="province" id="province" class="form-control" required>
                                    <option value="">-- Pilih Provinsi Tujuan --</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom: 18px;">
                                <label style="font-weight: 700; color: var(--text-main);">Kota / Kabupaten Tujuan <span class="text-danger">*</span></label>
                                <select name="city" id="city" class="form-control" required>
                                    <option value="">-- Pilih Kota Tujuan --</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom: 18px;">
                                <label style="font-weight: 700; color: var(--text-main);">Pilihan Ekspedisi Kurir <span class="text-danger">*</span></label>
                                <select name="courier" id="courier" class="form-control" required>
                                    <option value="">-- Pilih Ekspedisi --</option>
                                    <option value="jne">JNE (Jalur Nugraha Ekakurir)</option>
                                    <option value="tiki">TIKI (Titipan Kilat)</option>
                                    <option value="pos">POS Indonesia</option>
                                </select>
                            </div>

                            <div style="background: var(--bg-muted); padding: 12px 16px; border-radius: var(--radius-md); font-size: 13.5px; color: var(--text-muted); margin-bottom: 18px;">
                                <strong>Kota Asal Toko:</strong> Depok (Jawa Barat) &bull; <strong>Total Berat Belanja:</strong> {{ $totalBerat }} Gram
                            </div>

                            <button type="submit" class="btn-primary-modern" style="width: 100%; border-radius: var(--radius-md); font-size: 15px;">
                                <i class="fa fa-calculator"></i> Cek Opsi Ongkir
                            </button>
                        </div>
                    </div>
                </form>

                {{-- RESULTS CONTAINER --}}
                <div id="result" style="margin-top: 32px; border-top: 1px solid var(--border-light); padding-top: 24px;">
                    <h4 style="font-weight: 700; color: var(--text-main); margin-bottom: 16px;">
                        <i class="fa fa-list-alt" style="color: var(--accent);"></i> Pilihan Layanan & Biaya Ongkos Kirim
                    </h4>
                    
                    <div class="table-responsive">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>Layanan Ekspedisi</th>
                                    <th>Biaya Ongkir</th>
                                    <th>Estimasi Waktu</th>
                                    <th>Total Berat</th>
                                    <th>Subtotal Produk</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="shippingResults">
                                <tr>
                                    <td colspan="6" class="text-center" style="color: var(--text-muted); padding: 30px;">
                                        Silakan pilih Provinsi, Kota Tujuan, dan Kurir lalu klik <strong>"Cek Opsi Ongkir"</strong> untuk melihat tarif pengiriman.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript RajaOngkir Logic --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const originCityCode = 115;
            const originCityName = 'Depok';

            document.getElementById('city_origin').value = originCityCode;
            document.getElementById('city_origin_name').value = originCityName;

            // Load provinces
            fetch('/provinces')
                .then(res => res.json())
                .then(data => {
                    let provinces = [];
                    if (data.data && Array.isArray(data.data)) {
                        provinces = data.data.map(p => ({ id: p.id, name: p.name }));
                    } else if (data.rajaongkir && data.rajaongkir.results) {
                        provinces = data.rajaongkir.results.map(p => ({ id: p.province_id, name: p.province }));
                    }

                    if (provinces.length > 0) {
                        const provinceSelect = document.getElementById('province');
                        provinceSelect.innerHTML = '<option value="">-- Pilih Provinsi Tujuan --</option>';
                        provinces.forEach(province => {
                            const option = document.createElement('option');
                            option.value = province.id;
                            option.textContent = province.name;
                            provinceSelect.appendChild(option);
                        });
                    }
                })
                .catch(err => console.log('Provinces fetch error:', err));

            // Load cities on province change
            document.getElementById('province').addEventListener('change', function () {
                const provinceId = this.value;
                const provinceName = this.options[this.selectedIndex].text;
                document.getElementById('province_name').value = provinceName;

                const citySelect = document.getElementById('city');
                citySelect.innerHTML = '<option value="">Memuat Kota...</option>';

                fetch(`/cities?province_id=${provinceId}`)
                    .then(res => res.json())
                    .then(data => {
                        let cities = [];
                        if (data.data && Array.isArray(data.data)) {
                            cities = data.data.map(c => ({ id: c.id, name: c.name }));
                        } else if (data.rajaongkir && data.rajaongkir.results) {
                            cities = data.rajaongkir.results.map(c => ({ id: c.city_id, name: (c.type ? c.type + ' ' : '') + c.city_name }));
                        }

                        citySelect.innerHTML = '<option value="">-- Pilih Kota Tujuan --</option>';
                        cities.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.id;
                            option.textContent = city.name;
                            citySelect.appendChild(option);
                        });
                    })
                    .catch(err => console.log('Cities fetch error:', err));
            });

            document.getElementById('city').addEventListener('change', function () {
                document.getElementById('city_name').value = this.options[this.selectedIndex].text;
            });

            // Submit shipping form
            document.getElementById('shippingForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const origin = document.getElementById('city_origin').value;
                const originName = document.getElementById('city_origin_name').value;
                const destination = document.getElementById('city').value;
                const weight = document.getElementById('weight').value;
                const courier = document.getElementById('courier').value;
                const alamat = document.getElementById('alamat').value.trim();
                const kodePos = document.getElementById('kode_pos').value.trim();

                if (!alamat || !kodePos) {
                    return alert('Harap lengkapi alamat dan kode pos pengiriman.');
                }

                if (!destination || !courier) {
                    return alert('Harap pilih provinsi, kota tujuan, dan kurir pengiriman.');
                }

                const tbody = document.getElementById('shippingResults');
                tbody.innerHTML = '<tr><td colspan="6" class="text-center" style="padding: 24px;"><i class="fa fa-spinner fa-spin"></i> Menghitung estimasi ongkir dari server...</td></tr>';

                fetch('/cost', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ origin, destination, weight, courier })
                })
                .then(res => res.json())
                .then(data => {
                    let formattedCosts = [];

                    // Komerce Schema
                    if (data.data && Array.isArray(data.data)) {
                        formattedCosts = data.data.map(item => ({
                            service: item.service,
                            description: item.description || item.name,
                            cost: item.cost,
                            etd: item.etd || '-'
                        }));
                    }
                    // Legacy RajaOngkir Schema
                    else if (data.rajaongkir && data.rajaongkir.results && data.rajaongkir.results[0] && data.rajaongkir.results[0].costs) {
                        formattedCosts = data.rajaongkir.results[0].costs.map(item => ({
                            service: item.service,
                            description: item.description,
                            cost: item.cost[0].value,
                            etd: item.cost[0].etd || '-'
                        }));
                    }

                    tbody.innerHTML = '';

                    if (formattedCosts.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-warning" style="padding: 20px;">Layanan kurir tidak tersedia untuk rute tujuan ini.</td></tr>';
                        return;
                    }

                    formattedCosts.forEach(cost => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>
                                <strong style="color: var(--text-main); font-size: 14.5px;">${courier.toUpperCase()} - ${cost.service}</strong>
                                <div style="font-size: 12px; color: var(--text-muted);">${cost.description}</div>
                            </td>
                            <td style="font-weight: 800; color: var(--primary); font-size: 15px; font-variant-numeric: tabular-nums;">
                                Rp. ${new Intl.NumberFormat('id-ID').format(cost.cost)}
                            </td>
                            <td><span class="badge-modern badge-process"><i class="fa fa-clock-o"></i> ${cost.etd} hari</span></td>
                            <td>${weight} Gram</td>
                            <td style="font-weight: 600;">Rp. {{ number_format($totalHarga, 0, ',', '.') }}</td>
                            <td class="text-right">
                                <form action="{{ route('order.update-ongkir') }}" method="post" style="margin: 0;">
                                    @csrf
                                    <input type="hidden" name="province" value="${document.getElementById('province').value}">
                                    <input type="hidden" name="city" value="${document.getElementById('city').value}">
                                    <input type="hidden" name="province_name" value="${document.getElementById('province_name').value}">
                                    <input type="hidden" name="city_name" value="${document.getElementById('city_name').value}">
                                    <input type="hidden" name="kurir" value="${courier}">
                                    <input type="hidden" name="alamat" value="${alamat}">
                                    <input type="hidden" name="pos" value="${kodePos}">
                                    <input type="hidden" name="layanan_ongkir" value="${cost.service}">
                                    <input type="hidden" name="biaya_ongkir" value="${cost.cost}">
                                    <input type="hidden" name="estimasi_ongkir" value="${cost.etd}">
                                    <input type="hidden" name="total_berat" value="${weight}">
                                    <input type="hidden" name="city_origin" value="${origin}">
                                    <input type="hidden" name="city_origin_name" value="${originName}">
                                    <button type="submit" class="btn-primary-modern" style="padding: 8px 16px; font-size: 13.5px; border-radius: var(--radius-sm);">
                                        Pilih & Lanjut <i class="fa fa-chevron-right"></i>
                                    </button>
                                </form>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                })
                .catch(err => {
                    console.log('Ongkir fetch error:', err);
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger" style="padding: 20px;">Terjadi kesalahan saat memproses perhitungan ongkir.</td></tr>';
                });
            });
        });
    </script>
@endsection
