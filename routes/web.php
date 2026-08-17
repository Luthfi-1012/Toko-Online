<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LoginController; 
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\RajaOngkirController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Utama -> Redirect ke Beranda
Route::get('/', function () { 
    return redirect()->route('beranda'); 
}); 

// Frontend Routes
Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');
Route::get('/produk/all', [ProdukController::class, 'produkAll'])->name('produk.all'); 
Route::get('/produk/kategori/{id}', [ProdukController::class, 'produkKategori'])->name('produk.kategori');
Route::get('/produk/detail/{id}', [ProdukController::class, 'detail'])->name('produk.detail'); 

// Auth Customer (Google OAuth / Logout)
Route::get('/auth/redirect', [CustomerController::class, 'redirect'])->name('auth.redirect'); 
Route::get('/auth/google/callback', [CustomerController::class, 'callback'])->name('auth.callback'); 
Route::post('/logout', [CustomerController::class, 'logout'])->name('customer.logout');

// Group Route Customer (Perlu Login Customer)
Route::middleware('is.customer')->group(function () { 
    // Akun Customer 
    Route::get('/customer/akun/{id}', [CustomerController::class, 'akun'])->name('customer.akun'); 
    Route::put('/customer/updateakun/{id}', [CustomerController::class, 'updateAkun'])->name('customer.updateakun'); 

    // Keranjang Belanja 
    Route::post('add-to-cart/{id}', [OrderController::class, 'addToCart'])->name('order.addToCart'); 
    Route::get('cart', [OrderController::class, 'viewCart'])->name('order.cart'); 
    Route::post('cart/update/{id}', [OrderController::class, 'updateCart'])->name('order.updateCart'); 
    Route::post('remove/{id}', [OrderController::class, 'removeFromCart'])->name('order.remove'); 

    // Pengiriman & Ongkir
    Route::post('select-shipping', [OrderController::class, 'selectShipping'])->name('order.select_shipping'); 
    Route::post('update-ongkir', [OrderController::class, 'updateOngkir'])->name('order.update-ongkir'); 
    Route::get('select-payment', [OrderController::class, 'selectPayment'])->name('order.selectpayment');
    Route::post('/midtrans-callback', [OrderController::class, 'callback']); 
    Route::get('/order/complete', [OrderController::class, 'complete'])->name('order.complete'); 
    
    // History & Invoice Customer
    Route::get('history', [OrderController::class, 'orderHistory'])->name('order.history'); 
    Route::get('order/invoice/{id}', [OrderController::class, 'invoiceFrontend'])->name('order.invoice'); 

    // API RajaOngkir (Frontend)
    Route::get('/provinces', [RajaOngkirController::class, 'getProvinces']); 
    Route::get('/cities', [RajaOngkirController::class, 'getCities']); 
    Route::post('/cost', [RajaOngkirController::class, 'getCost']);
    Route::get('/cek-ongkir', function () { 
        return view('ongkir'); 
    }); 
});

// Backend Login & Logout
Route::get('backend/login', [LoginController::class, 'loginBackend'])->name('backend.login'); 
Route::post('backend/login', [LoginController::class, 'authenticateBackend'])->name('backend.login.post'); 
Route::post('backend/logout', [LoginController::class, 'logoutBackend'])->name('backend.logout');

// Backend Routes (Perlu Auth Admin/Super Admin)
Route::middleware('auth')->prefix('backend')->name('backend.')->group(function () {
    Route::get('/beranda', [BerandaController::class, 'berandaBackend'])->name('beranda'); 

    // Master Data
    Route::resource('user', UserController::class);
    Route::resource('customer', CustomerController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('produk', ProdukController::class);

    // Foto Produk Tambahan
    Route::post('foto-produk/store', [ProdukController::class, 'storeFoto'])->name('foto_produk.store');
    Route::delete('foto-produk/{id}', [ProdukController::class, 'destroyFoto'])->name('foto_produk.destroy');    

    // Laporan Master Data
    Route::get('laporan/formuser', [UserController::class, 'formUser'])->name('laporan.formuser');
    Route::post('laporan/cetakuser', [UserController::class, 'cetakUser'])->name('laporan.cetakuser');
    Route::get('laporan/formproduk', [ProdukController::class, 'formProduk'])->name('laporan.formproduk');
    Route::post('laporan/cetakproduk', [ProdukController::class, 'cetakProduk'])->name('laporan.cetakproduk');

    // Manajemen Pesanan
    Route::get('pesanan/proses', [OrderController::class, 'statusProses'])->name('pesanan.proses');
    Route::get('pesanan/detail/{id}', [OrderController::class, 'statusDetail'])->name('pesanan.detail');
    Route::put('pesanan/update/{id}', [OrderController::class, 'statusUpdate'])->name('pesanan.update');
    Route::get('pesanan/invoice/{id}', [OrderController::class, 'invoiceFrontend'])->name('pesanan.invoice');

    // Laporan Pesanan
    Route::get('laporan/formproses', [OrderController::class, 'formOrderProses'])->name('laporan.formproses');
    Route::post('laporan/cetakproses', [OrderController::class, 'cetakOrderProses'])->name('laporan.cetakproses');
    Route::get('laporan/formselesai', [OrderController::class, 'formOrderSelesai'])->name('laporan.formselesai');
    Route::post('laporan/cetakselesai', [OrderController::class, 'cetakOrderSelesai'])->name('laporan.cetakselesai');
});

// Alias backward compatibility untuk route pesanan backend yang dipanggil di view
Route::middleware('auth')->group(function () {
    Route::get('backend/pesanan-proses', [OrderController::class, 'statusProses'])->name('pesanan.proses');
    Route::get('backend/pesanan-detail/{id}', [OrderController::class, 'statusDetail'])->name('pesanan.detail');
    Route::put('backend/pesanan-update/{id}', [OrderController::class, 'statusUpdate'])->name('pesanan.update');
    Route::get('backend/pesanan-invoice/{id}', [OrderController::class, 'invoiceFrontend'])->name('pesanan.invoice');
});