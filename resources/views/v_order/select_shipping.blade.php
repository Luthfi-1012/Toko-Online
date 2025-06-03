@extends('v_layouts.app')

@section('content')
    {{-- Order Summary (Hidden) --}}
    <div class="col-md-12" hidden>
        <div class="order-summary clearfix">
            <div class="section-title">
                <p>PENGIRIMAN</p>
                <h3 class="title">Produk</h3>
            </div>
            @if ($order && $order->orderItems->count() > 0)
                <table class="shopping-cart-table table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th></th>
                            <th class="text-center">Harga</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Total</th>
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
                                $totalBerat += $item->produk->berat * $item->quantity;
                            @endphp
                            <tr>
                                <td class="thumb">
                                    <img src="{{ asset('storage/img-produk/thumb_sm_' . $item->produk->foto) }}" alt="">
                                </td>
                                <td class="details">
                                    <a>{{ $item->produk->nama_produk }}</a>
                                    <ul>
                                        <li><span>Berat: {{ $item->produk->berat }} Gram</span></li>
                                        <li><span>Stok: {{ $item->produk->stok }} Gram</span></li>
                                    </ul>
                                </td>
                                <td class="price text-center">
                                    <strong>Rp. {{ number_format($item->harga, 0, ',', '.') }}</strong>
                                </td>
                                <td class="qty text-center">{{ $item->quantity }}</td>
                                <td class="total text-center">
                                    <strong class="primary-color">Rp. {{ number_format($item->harga * $item->quantity, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Keranjang belanja kosong.</p>
            @endif
        </div>
    </div>

    {{-- Shipping Section --}}
    <div class="col-md-12">
        <div class="order-summary clearfix">
            <div class="section-title">
                <p>PENGIRIMAN</p>
                <h3 class="title">Pilih Pengiriman</h3>
            </div>

            <form id="shippingForm">
                {{-- Hidden Inputs --}}
                <input type="hidden" id="city_origin" name="city_origin">
                <input type="hidden" id="city_origin_name" name="city_origin_name">
                <input type="hidden" name="weight" id="weight" value="{{ $totalBerat }}">
                <input type="hidden" name="province_name" id="province_name">
                <input type="hidden" name="city_name" id="city_name">

                {{-- Dropdowns --}}
                <div class="form-group">
                    <label for="province">Provinsi Tujuan:</label>
                    <select name="province" id="province" class="input">
                        <option value="">Pilih Provinsi Tujuan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="city">Kota Tujuan:</label>
                    <select name="city" id="city" class="input">
                        <option value="">Pilih Kota Tujuan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="courier">Kurir:</label>
                    <select name="courier" id="courier" class="input">
                        <option value="">Pilih Kurir</option>
                        <option value="jne">JNE</option>
                        <option value="tiki">TIKI</option>
                        <option value="pos">POS Indonesia</option>
                    </select>
                </div>

                {{-- Alamat --}}
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea class="input" name="alamat" id="alamat">{{ Auth::user()->alamat }}</textarea>
                </div>

                <div class="form-group">
                    <label for="kode_pos">Kode Pos</label>
                    <input type="text" class="input" name="kode_pos" id="kode_pos" value="{{ Auth::user()->pos }}">
                </div>

                <button type="submit" class="primary-btn">Cek Ongkir</button>
            </form>

            {{-- Result --}}
            <br>
            <div id="result">
                <table class="shopping-cart-table table">
                    <thead>
                        <tr>
                            <th>Layanan</th>
                            <th>Biaya</th>
                            <th>Estimasi Pengiriman</th>
                            <th>Total Berat</th>
                            <th>Total Harga</th>
                            <th>Bayar</th>
                        </tr>
                    </thead>
                    <tbody id="shippingResults">
                        {{-- Hasil pengiriman akan muncul di sini --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- JavaScript --}}
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
                    if (data.rajaongkir.status.code === 200) {
                        const provinces = data.rajaongkir.results;
                        const provinceSelect = document.getElementById('province');
                        provinces.forEach(province => {
                            const option = document.createElement('option');
                            option.value = province.province_id;
                            option.textContent = province.province;
                            provinceSelect.appendChild(option);
                        });
                    }
                });

            // Load cities
            document.getElementById('province').addEventListener('change', function () {
                const provinceId = this.value;
                const provinceName = this.options[this.selectedIndex].text;
                document.getElementById('province_name').value = provinceName;

                fetch(`/cities?province_id=${provinceId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.rajaongkir.status.code === 200) {
                            const cities = data.rajaongkir.results;
                            const citySelect = document.getElementById('city');
                            citySelect.innerHTML = '<option value="">Pilih Kota Tujuan</option>';
                            cities.forEach(city => {
                                const option = document.createElement('option');
                                option.value = city.city_id;
                                option.textContent = city.city_name;
                                citySelect.appendChild(option);
                            });
                        }
                    });
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
                    return alert('Harap lengkapi alamat dan kode pos sebelum mengecek ongkir.');
                }

                if (!origin || !originName || !destination || !weight || !courier) {
                    return alert('Harap lengkapi semua kolom sebelum mengecek ongkir.');
                }

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
                    if (data.rajaongkir.status.code === 200) {
                        const results = data.rajaongkir.results[0].costs;
                        const tbody = document.getElementById('shippingResults');
                        tbody.innerHTML = '';

                        results.forEach(cost => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${cost.service}</td>
                                <td>${cost.cost[0].value} Rupiah</td>
                                <td>${cost.cost[0].etd} hari</td>
                                <td>${weight} Gram</td>
                                <td>Rp. {{ number_format($totalHarga, 0, ',', '.') }}</td>
                                <td>
                                    <form action="{{ route('order.update-ongkir') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="province" value="${document.getElementById('province').value}">
                                        <input type="hidden" name="city" value="${document.getElementById('city').value}">
                                        <input type="hidden" name="province_name" value="${document.getElementById('province_name').value}">
                                        <input type="hidden" name="city_name" value="${document.getElementById('city_name').value}">
                                        <input type="hidden" name="kurir" value="${courier}">
                                        <input type="hidden" name="alamat" value="${alamat}">
                                        <input type="hidden" name="pos" value="${kodePos}">
                                        <input type="hidden" name="layanan_ongkir" value="${cost.service}">
                                        <input type="hidden" name="biaya_ongkir" value="${cost.cost[0].value}">
                                        <input type="hidden" name="estimasi_ongkir" value="${cost.cost[0].etd}">
                                        <input type="hidden" name="total_berat" value="${weight}">
                                        <input type="hidden" name="city_origin" value="${origin}">
                                        <input type="hidden" name="city_origin_name" value="${originName}">
                                        <button type="submit" class="primary-btn">Pilih Pengiriman</button>
                                    </form>
                                </td>
                            `;
                            tbody.appendChild(row);
                        });
                    }
                });
            });
        });
    </script>
@endsection
