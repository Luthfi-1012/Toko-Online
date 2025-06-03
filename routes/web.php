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



//Route untuk halaman utama
// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () { 
    // return view('welcome'); 
    return redirect()->route('beranda'); 

}); 

// Route untuk beranda
Route::get('backend/beranda', [BerandaController::class, 'berandaBackend'])->name('backend.beranda')->middleware('auth'); 

// Route untuk login
Route::get('backend/login', [LoginController::class, 'loginBackend'])->name('backend.login-old'); 
Route::post('backend/login', [LoginController::class, 'authenticateBackend'])->name('backend.login'); 
Route::post('backend/logout', [LoginController::class, 'logoutBackend'])->name('backend.logout');

// Resource route untuk pengguna
//Route::resource('backend/user', UserController::class)->middleware('auth');
Route::resource('backend/user', UserController::class, ['as' => 'backend'])->middleware('auth');;
Route::resource('backend/kategori', KategoriController::class, ['as' => 'backend'])->middleware('auth');
Route::resource('backend/produk', ProdukController::class, ['as' => 'backend'])->middleware('auth');
// Route untuk menambahkan foto
Route::post('foto-produk/store', [ProdukController::class, 'storeFoto'])->name('backend.foto_produk.store')->middleware('auth');
// Route untuk menghapus foto
Route::delete('foto-produk/{id}', [ProdukController::class, 'destroyFoto'])->name('backend.foto_produk.destroy')->middleware('auth');    
Route::get('backend/laporan/formuser', [UserController::class, 'formUser'])->name('backend.laporan.formuser')->middleware('auth');
Route::post('backend/laporan/cetakuser', [UserController::class, 'cetakUser'])->name('backend.laporan.cetakuser')->middleware('auth');
Route::get('backend/laporan/formproduk', [ProdukController::class, 'formProduk'])->name('backend.laporan.formproduk')->middleware('auth');
Route::post('backend/laporan/cetakproduk', [ProdukController::class, 'cetakProduk'])->name('backend.laporan.cetakproduk')->middleware('auth');

// Frontend 
Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');

Route::get('/produk/detail/{id}', [ProdukController::class, 'detail'])->name('produk.detail'); 

Route::get('/produk/kategori/{id}', [ProdukController::class, 'produkKategori'])->name('produk.kategori');

Route::get('/produk/all', [ProdukController::class, 'produkAll'])->name('produk.all'); 

//API Google 
Route::get('/auth/redirect', [CustomerController::class, 'redirect'])->name('auth.redirect'); 
Route::get('/auth/google/callback', [CustomerController::class, 'callback'])->name('auth.callback'); 
// Logout 
Route::post('/logout', [CustomerController::class, 'logout'])->name('customer.logout');

// Route untuk Customer
Route::resource('backend/customer', CustomerController::class, ['as' => 'backend'])->middleware('auth');
// Group route untuk customer 
Route::middleware('is.customer')->group(function () { 
    // Route untuk menampilkan halaman akun customer 
    Route::get('/customer/akun/{id}', [CustomerController::class, 'akun']) ->name('customer.akun'); 
    // Route untuk mengupdate data akun customer 
    Route::put('/customer/updateakun/{id}', [CustomerController::class, 'updateAkun']) ->name('customer.updateakun'); 
    }); 
    
    /// Group route untuk customer 
Route::middleware('is.customer')->group(function ()  {
    // Route untuk menampilkan halaman akun customer 
    Route::get('/customer/akun/{id}', [CustomerController::class, 'akun'])->name('customer.akun'); 
 
    // Group route untuk customer 
Route::middleware('is.customer')->group(function () { 
    // Route untuk menampilkan halaman akun customer 
    Route::get('/customer/akun/{id}', [CustomerController::class, 'akun'])->name('customer.akun'); 
 
    // Route untuk mengupdate data akun customer 
    Route::put('/customer/updateakun/{id}', [CustomerController::class, 'updateAkun']) 
        ->name('customer.updateakun'); 
 
    // Route keranjang belanja 
    Route::post('add-to-cart/{id}', [OrderController::class, 'addToCart'])->name('order.addToCart'); 
    Route::get('cart', [OrderController::class, 'viewCart'])->name('order.cart'); 
    Route::post('cart/update/{id}', [OrderController::class, 'updateCart'])->name('order.updateCart'); 
    Route::post('remove/{id}', [OrderController::class, 'removeFromCart'])->name('order.remove'); 
 
    // Ongkir 
     Route::post('select-shipping', [OrderController::class, 'selectShipping'])->name('order.select_shipping'); 
    Route::post('update-ongkir', [OrderController::class, 'updateOngkir'])->name('order.update-ongkir'); 
    Route::get('select-payment', [OrderController::class, 'selectPayment'])->name('order.selectpayment');
    
    
    Route::post('/midtrans-callback', [OrderController::class, 'callback']); 
    Route::get('/order/complete', [OrderController::class, 'complete'])->name('order.complete'); 
    // Route history 
    Route::get('history', [OrderController::class, 'orderHistory'])->name('order.history'); 
    Route::get('order/invoice/{id}', [OrderController::class, 'invoiceFrontend'])->name('order.invoice'); 
}); 
    
    //route untuk test apikey
    Route::get('/list-ongkir', function () { 
    $response = Http::withHeaders([ 
    'key' => 'd3850067243aa5bef23d7e36dba51f1a'])->get('https://api.rajaongkir.com/starter/province'); //ganti 'province' atau 'city' 
    dd($response->json()); 
    }); 
    // route untuk api
    Route::get('/cek-ongkir', function () { 
        return view('ongkir'); 
    }); 
    
    Route::get('/provinces', [RajaOngkirController::class, 'getProvinces']); 
    Route::get('/cities', [RajaOngkirController::class, 'getCities']); 
    Route::post('/cost', [RajaOngkirController::class, 'getCost']);
});

// // di routes/web.php
// Route::get('/checkout', [OrderController::class, 'selectPayment'])
//      ->name('order.selectpayment');
     
//      Route::post('/pilih-pengiriman', [OrderController::class, 'updateongkir'])
//      ->name('order.updateOngkir');
     
//      Route::post('/cost', [OrderController::class, 'getCost'])
//      ->name('order.getCost');
     
//      Route::get('/proses-pembayaran', [OrderController::class, 'processPayment'])
//     ->name('order.processPayment');
    
//     Route::get('/history', [OrderController::class, 'history'])->name('order.history');

//     Route::get('/admin/orders', [OrderController::class, 'index'])->name('pesanan.index');
    
//     // untuk detail custumer
//     Route::get('/customer/{id}', [CustomerController::class, 'show'])->name('customer.show');

//     //untuk edit 
//     Route::resource('customer', CustomerController::class)->only(['edit', 'update', 'index', 'show']);

//     // untuk Hapus
//     Route::delete('/customer/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');