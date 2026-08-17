<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    public function getProvinces() 
    { 
        $baseUrl = rtrim(env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1'), '/');
        $apiKey = env('RAJAONGKIR_API_KEY');

        try {
            $response = Http::withHeaders(['key' => $apiKey])->get($baseUrl . '/destination/province');
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json([
                'meta' => ['code' => 500, 'status' => 'error', 'message' => $e->getMessage()],
                'data' => []
            ], 500);
        }
    } 

    public function getCities(Request $request) 
    { 
        $provinceId = $request->input('province_id'); 
        $baseUrl = rtrim(env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1'), '/');
        $apiKey = env('RAJAONGKIR_API_KEY');

        try {
            $response = Http::withHeaders(['key' => $apiKey])->get($baseUrl . '/destination/city/' . $provinceId); 
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json([
                'meta' => ['code' => 500, 'status' => 'error', 'message' => $e->getMessage()],
                'data' => []
            ], 500);
        }
    } 

    public function getCost(Request $request) 
    { 
        $origin = $request->input('origin'); 
        $destination = $request->input('destination'); 
        $weight = $request->input('weight'); 
        $courier = $request->input('courier'); 
        $baseUrl = rtrim(env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1'), '/');
        $apiKey = env('RAJAONGKIR_API_KEY');

        try {
            $response = Http::asForm()->withHeaders([ 
                'key' => $apiKey 
            ])->post($baseUrl . '/calculate/domestic-cost', [ 
                'origin' => $origin, 
                'destination' => $destination, 
                'weight' => $weight, 
                'courier' => $courier, 
            ]); 
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json([
                'meta' => ['code' => 500, 'status' => 'error', 'message' => $e->getMessage()],
                'data' => []
            ], 500);
        }
    } 
}