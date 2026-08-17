<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class CustomerController extends Controller
{
    // Redirect ke Google 
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Callback dari Google 
    public function callback()
    {
        try {
            $socialUser = Socialite::driver('google')->user();

            // Cek apakah email sudah terdaftar 
            $registeredUser = User::where('email', $socialUser->email)->first();

            if (!$registeredUser) {
                // Buat user baru 
                $user = User::create([
                    'nama' => $socialUser->name ?? $socialUser->nickname ?? 'Customer',
                    'email' => $socialUser->email,
                    'role' => '2', // Role customer 
                    'status' => 1, // Status aktif 
                    'password' => Hash::make(uniqid('pass_')),
                ]);

                // Buat data customer 
                Customer::create([
                    'user_id' => $user->id,
                    'google_id' => $socialUser->id,
                    'google_token' => $socialUser->token,
                ]);

                // Login pengguna baru 
                Auth::login($user);
            } else {
                // Pastikan data customer ada jika sebelumnya belum terbuat
                Customer::firstOrCreate(
                    ['user_id' => $registeredUser->id],
                    [
                        'google_id' => $socialUser->id,
                        'google_token' => $socialUser->token,
                    ]
                );

                // Jika email sudah terdaftar, langsung login 
                Auth::login($registeredUser);
            }

            // Redirect ke halaman utama 
            return redirect()->route('beranda')->with('success', 'Berhasil masuk dengan akun Google!');
        } catch (\Exception $e) {
            return redirect()->route('beranda')->with('error', 'Gagal login via Google: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Logout pengguna 
        $request->session()->invalidate(); // Hapus session 
        $request->session()->regenerateToken(); // Regenerate token CSRF 

        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }

    public function index()
    {
        $customer = Customer::orderBy('id', 'desc')->get();
        return view('backend.v_customer.index', [
            'judul' => 'Customer',
            'sub' => 'Halaman Customer',
            'index' => $customer
        ]);
    }
    
    public function akun($id) 
{ 
    $loggedInCustomerId = Auth::user()->id; 
    // Cek apakah ID yang diberikan sama dengan ID customer yang sedang login 
    if ($id != $loggedInCustomerId) { 
        // Redirect atau tampilkan pesan error 
        return redirect()->route('customer.akun', ['id' => $loggedInCustomerId])->with('msgError', 'Anda tidak berhak mengakses akun ini.'); 
    } 
    $customer = Customer::where('user_id', $id)->firstOrFail(); 
    return view('v_customer.edit', [ 
        'judul' => 'Customer', 
        'subJudul' => 'Akun Customer', 
        'edit' => $customer 
    ]); 
} 
 
public function updateAkun(Request $request, $id) 
{
    $customer = Customer::where('user_id', $id)->firstOrFail();

    $rules = [
        'nama' => 'required|max:255',
        'hp' => 'required|min:10|max:13',
        'foto' => 'image|mimes:jpeg,jpg,png,gif|file|max:1024',
    ];

    $messages = [
        'foto.image' => 'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif.',
        'foto.max' => 'Ukuran file gambar Maksimal adalah 1024 KB.'
    ];

    if ($request->email != $customer->user->email) {
        $rules['email'] = 'required|max:255|email|unique:user';
    }

    if ($request->alamat != $customer->alamat) {
        $rules['alamat'] = 'required';
    }

    if ($request->pos != $customer->pos) {
        $rules['pos'] = 'required';
    }

    $validatedData = $request->validate($rules, $messages);

    if ($request->file('foto')) {
        if ($customer->user->foto) {
            $oldImagePath = public_path('storage/img-customer/') . $customer->user->foto;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $file = $request->file('foto');
        $extension = $file->getClientOriginalExtension();
        $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
        $directory = 'storage/img-customer/';

        ImageHelper::uploadAndResize($file, $directory, $originalFileName, 385, 400);

        $validatedData['foto'] = $originalFileName;
    } else {
        // Ini yang penting biar foto lama gak ilang
        $validatedData['foto'] = $customer->user->foto;
    }

    $customer->user->update($validatedData);

    $customer->update([
        'alamat' => $request->input('alamat'),
        'pos' => $request->input('pos'),
    ]);

    return redirect()->route('customer.akun', $id)->with('success', 'Data berhasil diperbarui');
}

// untuk bagian detail customer
public function show($id)
{
    $customer = Customer::with('user')->findOrFail($id);
    $judul = 'Detail Customer';
    return view('backend.v_customer.show', [
        'customer' => $customer,
        'judul' => $judul
    ]); 
     
}

// untuk bagian edit customor
public function edit($id)
{
    // untuk mengedit
    $customer = Customer::with('user')->findOrFail($id);
    $judul = 'Edit Customer';
    return view('backend.v_customer.edit', [
        'customer' => $customer,
        'judul' => $judul
    ]);
}

// untuk bagian foto edit customer
public function update(Request $request, $id)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email',
        'hp' => 'nullable|string|max:20',
        'alamat' => 'nullable|string',
        'pos' => 'nullable|string|max:10',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Ambil customer dan user terkait
    $customer = Customer::with('user')->findOrFail($id);
    $user = $customer->user;

    // Simpan data user
    $user->nama = $request->nama;
    $user->email = $request->email;
    $user->hp = $request->hp;

    // Proses upload foto 
    if ($request->hasFile('foto')) {
        $foto = $request->file('foto');
        $namaFoto = time() . '.' . $foto->getClientOriginalExtension();
        $foto->move(public_path('storage/img-customer'), $namaFoto);
    
        $user->foto = $namaFoto;
    }
    $user->save();

    // Simpan data customer
    $customer->alamat = $request->alamat;
    $customer->pos = $request->pos;
    $customer->save();

    return redirect()->route('backend.customer.index')->with('success', 'Data customer berhasil diperbarui');
}

// untuk hapus
public function destroy($id)
{
    $customer = Customer::with('user')->findOrFail($id);
    $user = $customer->user;

    $relatedProduk = \App\Models\Produk::where('user_id', $user->id)->exists();

    if ($relatedProduk) {
        return redirect()->back()->with('error', 'Gagal menghapus: User memiliki produk yang masih terdaftar.');
    }

    // Hapus customer
    $customer->delete();

    // Hapus foto
    if ($user->foto && file_exists(public_path('storage/img-customer/' . $user->foto))) {
        unlink(public_path('storage/img-customer/' . $user->foto));
    }

    // Hapus user
    $user->delete();

    return redirect()->route('backend.customer.index')->with('success', 'Customer dan user berhasil dihapus.');
}


}