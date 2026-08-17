<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Customer;
use App\Models\Order;

class BerandaController extends Controller
{
    public function berandaBackend()
    {
        $totalProduk = Produk::count();
        $totalKategori = Kategori::count();
        $totalCustomer = Customer::count();
        $totalPesanan = Order::count();
        $totalPendapatan = Order::where('status', 'Selesai')->sum('total_harga');
        $pesananTerbaru = Order::with('customer.user')->latest()->take(5)->get();

        return view('backend.v_beranda.index', [
            'judul' => 'Dashboard Utama',
            'sub' => 'Ringkasan Statistik & Operasional Toko',
            'totalProduk' => $totalProduk,
            'totalKategori' => $totalKategori,
            'totalCustomer' => $totalCustomer,
            'totalPesanan' => $totalPesanan,
            'totalPendapatan' => $totalPendapatan,
            'pesananTerbaru' => $pesananTerbaru,
        ]);
    }

    public function index() 
    { 
        $produk = Produk::where('status', 1)->orderBy('updated_at', 'desc')->paginate(6); 
        return view('v_beranda.index', [ 
            'judul' => 'Beranda', 
            'produk' => $produk, 
        ]); 
    } 
}
