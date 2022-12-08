<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WilayahController extends Controller
{
    public function __construct()
    {
        $this->RajaOngkirKey = env('RAJAONGKIR_KEY');
        $this->RajaOngkirUrl = env('RAJAONGKIR_URL');
    }

    public static function customResponse($status, $message, $data = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
    }

    public function getProvinsi(Request $request)
    {
        $response = Http::withHeaders([
            'key' => $this->RajaOngkirKey
        ])->get($this->RajaOngkirUrl . 'province');
        $data = json_decode($response->body(), false);
        $provinsis = $data->rajaongkir->results;
        $data = array_map(function ($provinsi) {
            return [
                'id' => $provinsi->province_id,
                'nama' => $provinsi->province
            ];
        }, $provinsis);
        return $this->customResponse(true, 'Berhasil mengambil data provinsi', $data);
    }

    public function getKota($id)
    {
        $response = Http::withHeaders([
            'key' => $this->RajaOngkirKey
        ])->get($this->RajaOngkirUrl . 'city?province=' . $id);
        $data = json_decode($response->body(), false);
        $data = $data->rajaongkir->results;
        $data = array_map(function ($item) {
            return [
                'id' => $item->city_id,
                'nama' => $item->type . ' ' . $item->city_name,
                'kode_pos' => $item->postal_code
            ];
        }, $data);
        return $this->customResponse(true, 'Berhasil mengambil data kota', $data);
    }

    public function getKecamatan($id)
    {
        $response = Http::withHeaders([
            'key' => $this->RajaOngkirKey
        ])->get($this->RajaOngkirUrl . 'subdistrict?city=' . $id);
        $data = json_decode($response->body(), false);
        $data = $data->rajaongkir->results;
        $data = array_map(function ($item) {
            return [
                'id' => $item->subdistrict_id,
                'nama' => $item->subdistrict_name
            ];
        }, $data);
        return $this->customResponse(true, 'Berhasil mengambil data kecamatan', $data);
    }

    public function getOngkir(Request $request)
    {
        $request = (object) $request->json()->all();
        $response = Http::withHeaders([
            'key' => $this->RajaOngkirKey
        ])->post($this->RajaOngkirUrl . 'cost', [
            'origin' => $request->asal,
            'originType' => 'subdistrict',
            'destination' => $request->tujuan,
            'destinationType' => 'subdistrict',
            'weight' => $request->berat,
            'courier' => 'jnt'
        ]);
        $data = json_decode($response->body(), false);
        $data = $data->rajaongkir->results;
        $data = ['ongkir' => $data[0]->costs[0]->cost[0]->value];
        return $this->customResponse(true, 'Berhasil mengambil data ongkir', $data);
    }
}
