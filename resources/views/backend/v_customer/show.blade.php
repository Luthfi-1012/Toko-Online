@extends('backend.v_layouts.app')
@section('content')
    <!-- contentAwal -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $judul }} <br><br></h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 20%;">Foto</th>
                                    <th>Data Customer</th>
                                </tr>
                            </thead>
                            {{-- awal untuk buat tab --}}
                            <tbody>
                                {{-- untuk memasukan fotonya --}}
                                <tr>
                                    <td class="text-center align-middle">
                                        @if ($customer->user->foto)
                                            <img src="{{ asset('storage/img-customer/' . $customer->user->foto) }}"
                                                width="150" height="150" class="rounded mx-auto d-block"
                                                style="object-fit: cover;">
                                        @else
                                            <img src="{{ asset('storage/img-user/img-default.jpg') }}" width="150"
                                                height="150" class="rounded mx-auto d-block" style="object-fit: cover;">
                                        @endif
                                    </td>

                                    {{-- untuk data user --}}
                                    <td>
                                        <table class="table table-borderless mb-0">
                                            <tr>
                                                <th>Nama</th>
                                                <td>{{ $customer->user->nama }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>{{ $customer->user->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Telepon</th>
                                                <td>{{ $customer->user->hp ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Alamat</th>
                                                <td>{{ $customer->alamat ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Kode Pos</th>
                                                <td>{{ $customer->pos ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Daftar</th>
                                                <td>{{ $customer->created_at->format('d M Y H:i') }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <a href="{{ route('backend.customer.index') }}" class="btn btn-secondary mt-3">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- contentAkhir -->
@endsection
