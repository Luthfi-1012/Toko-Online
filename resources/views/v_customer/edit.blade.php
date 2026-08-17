@extends('v_layouts.app')
@section('content')

    <div class="row">
        <div class="col-md-10 col-md-offset-1 col-sm-12">
            <div class="form-card-modern">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 14px;">
                    <div>
                        <h3 style="font-size: 22px; font-weight: 800; color: var(--text-main); margin: 0;">
                            <i class="fa fa-user-circle" style="color: var(--primary);"></i> Profil Akun Saya
                        </h3>
                        <p style="font-size: 13.5px; color: var(--text-muted); margin: 4px 0 0;">Perbarui data identitas dan alamat pengiriman Anda</p>
                    </div>
                    <a href="{{ route('beranda') }}" class="btn-card-detail" style="border-radius: var(--radius-full);">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>

                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible" role="alert" style="border-radius: var(--radius-md);">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <i class="fa fa-check-circle"></i> <strong>{{ session('success') }}</strong>
                    </div>
                @endif
                @if (session()->has('msgError') || session()->has('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert" style="border-radius: var(--radius-md);">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <i class="fa fa-exclamation-circle"></i> <strong>{{ session('msgError') ?? session('error') }}</strong>
                    </div>
                @endif

                <form action="{{ route('customer.updateakun', $edit->user->id) }}" method="post" enctype="multipart/form-data">
                    @method('put')
                    @csrf

                    <div class="row">
                        <!-- FOTO AVATAR (COL-MD-4) -->
                        <div class="col-md-4 col-sm-12 text-center" style="margin-bottom: 24px;">
                            <div style="background: var(--bg-muted); border-radius: var(--radius-lg); padding: 24px; border: 1px solid var(--border-color);">
                                <div style="width: 140px; height: 140px; margin: 0 auto 16px; border-radius: 50%; overflow: hidden; border: 3px solid var(--primary); box-shadow: var(--shadow-md);">
                                    @if ($edit->user->foto)
                                        <img src="{{ asset('storage/img-customer/' . $edit->user->foto) }}" class="foto-preview" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='{{ asset('backend/image/img-default.jpg') }}'">
                                    @else
                                        <img src="{{ asset('backend/image/img-default.jpg') }}" class="foto-preview" style="width: 100%; height: 100%; object-fit: cover;">
                                    @endif
                                </div>

                                <div class="form-group" style="margin-bottom: 0;">
                                    <label style="font-size: 13px; font-weight: 600; color: var(--text-muted); cursor: pointer;" class="btn btn-sm btn-default">
                                        <i class="fa fa-camera"></i> Ganti Foto Profil
                                        <input type="file" name="foto" class="d-none @error('foto') is-invalid @enderror" onchange="previewFoto()" style="display: none;">
                                    </label>
                                    @error('foto')
                                        <div class="text-danger" style="font-size: 12px; margin-top: 6px;">{{ $message }}</div>
                                    @enderror
                                    <div style="font-size: 11.5px; color: var(--text-light); margin-top: 6px;">Format JPG/PNG, maks 1MB</div>
                                </div>
                            </div>
                        </div>

                        <!-- DATA PROFIL (COL-MD-8) -->
                        <div class="col-md-8 col-sm-12">
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label style="font-weight: 700; color: var(--text-main);">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" value="{{ old('nama', $edit->user->nama) }}" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan Nama Lengkap" required>
                                @error('nama')
                                    <span class="text-danger" style="font-size: 12.5px;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" style="margin-bottom: 16px;">
                                        <label style="font-weight: 700; color: var(--text-main);">Alamat Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" value="{{ old('email', $edit->user->email) }}" class="form-control @error('email') is-invalid @enderror" placeholder="email@domain.com" required>
                                        @error('email')
                                            <span class="text-danger" style="font-size: 12.5px;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" style="margin-bottom: 16px;">
                                        <label style="font-weight: 700; color: var(--text-main);">Nomor HP / WhatsApp <span class="text-danger">*</span></label>
                                        <input type="text" name="hp" value="{{ old('hp', $edit->user->hp) }}" class="form-control @error('hp') is-invalid @enderror" placeholder="0812xxxxxxxx" required>
                                        @error('hp')
                                            <span class="text-danger" style="font-size: 12.5px;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 16px;">
                                <label style="font-weight: 700; color: var(--text-main);">Alamat Pengiriman Utama</label>
                                <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan...">{{ old('alamat', $edit->alamat) }}</textarea>
                                @error('alamat')
                                    <span class="text-danger" style="font-size: 12.5px;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group" style="margin-bottom: 24px;">
                                <label style="font-weight: 700; color: var(--text-main);">Kode Pos</label>
                                <input type="text" name="pos" value="{{ old('pos', $edit->pos) }}" class="form-control @error('pos') is-invalid @enderror" placeholder="Contoh: 16424">
                                @error('pos')
                                    <span class="text-danger" style="font-size: 12.5px;">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn-primary-modern" style="border-radius: var(--radius-md); padding: 12px 28px;">
                                <i class="fa fa-save"></i> Simpan Perubahan Profil
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script preview foto --}}
    <script>
        function previewFoto() {
            const foto = document.querySelector('input[name=foto]');
            const preview = document.querySelector('.foto-preview');
            if (foto.files && foto.files[0]) {
                const fileReader = new FileReader();
                fileReader.readAsDataURL(foto.files[0]);
                fileReader.onload = function (e) {
                    preview.src = e.target.result;
                }
            }
        }
    </script>
@endsection
