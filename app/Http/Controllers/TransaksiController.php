<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\Keranjang;
use App\Models\Notifikasi;
use App\Models\Pembayaran;
use App\Models\Toko;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Nette\Utils\Random;
use Nullix\CryptoJsAes\CryptoJsAes;

class TransaksiController extends Controller
{
    public function __construct()
    {
        $this->status = [
            0 => 'Menunggu Pembayaran',
            1 => 'Dibayar',
            2 => 'Diproses Toko',
            3 => 'Dikirim Ke Alamat',
            4 => 'Selesai',
            5 => 'Dibatalkan',
            6 => 'Pembayaran Kadaluarsa'
        ];

        $this->statusMidtrans = [
            'pending' => '0',
            'settlement' => '1',
            'capture' => '1',
            'deny' => '2',
            'cancel' => '2',
            'expire' => '3',
            'refund' => '4',
        ];
    }

    public function createTransaction(Request $request)
    {
        $request = (object)$request->json()->all();
        $data = $request->data;
        $data = json_decode(json_encode($data));
        $data = (collect($data));
        $pembayaran = Pembayaran::create([
            'id_user' => $data[0]->id_user,
            'status' => 0,
            'kode' => 'MTR-' . Random::generate(5, '0-9A-Z')
        ]);
        Notifikasi::create([
            'type' => 'user',
            'id_user' => $data[0]->id_user,
            'pesan' => 'Pembayaran ' . $pembayaran->kode . ' berhasil dibuat',
            'icon' => 'fa fa-money',
            'bg_color' => 'info',
            'text_color' => 'text-white'
        ]);
        // Keranjang::whereIn('id', $request->keranjang)->delete();
        $data = $data->map(
            function ($item) use ($pembayaran) {
                $kode = 'TBL-' . Random::generate(5, '0-9A-Z');
                $transaksi = Transaksi::create([
                    'id_user' => $item->id_user,
                    'id_alamat' => $item->id_alamat,
                    'id_toko' => $item->id_toko,
                    'id_pembayaran' => $pembayaran->id,
                    'ongkir' => $item->ongkir,
                    'total_harga' => $item->total_harga,
                    'total' => $item->total,
                    'kode' => $kode,
                ]);

                Notifikasi::create([
                    'type' => 'user',
                    'id_user' => $item->id_user,
                    'pesan' => 'Pesanan dengan kode ' . $kode . ' berhasil dibuat',
                    'icon' => 'fa fa-shopping-bag',
                    'bg_color' => 'warning',
                    'text_color' => 'text-dark'
                ]);

                Notifikasi::create([
                    'type' => 'toko',
                    'id_toko' => $item->id_toko,
                    'pesan' => 'Pesanan dengan kode ' . $kode . ' berhasil dibuat',
                    'icon' => 'fa fa-shopping-bag',
                    'bg_color' => 'warning',
                    'text_color' => 'text-dark'
                ]);

                // $id_keranjang = $item->id_keranjang;
                // Keranjang::where('id', $id_keranjang)->delete();

                $item->products = collect($item->products);
                $item->products->map(
                    function ($item) use ($transaksi) {
                        $id_transaksi = $transaksi->id;
                        TransaksiItem::create([
                            'id_transaksi' => $id_transaksi,
                            'id_produk' => $item->id_produk,
                            'qty' => $item->qty,
                            'harga' => $item->harga,
                            'total' => $item->total,
                        ]);
                    }
                );

                $transaksis = Transaksi::where('id', $transaksi->id)->first();
                $transaksis->products = TransaksiItem::where('id_transaksi', $transaksi->id)->get();
                return $transaksis;
            }
        );
        $data = array(
            'pembayaran' => $pembayaran->id,
            'encrypt_pembayaran' => Crypt::encryptString($pembayaran->id),
            'data' => $data
        );
        return WilayahController::customResponse(true, 'Success', $data);
    }

    public function pembayaran2(Request $request)
    {
        $data = $request->data;
        $explode = explode('TBL', $data);
        $ct = $explode[0];
        $iv = $explode[1];
        $s = $explode[2];
        $encrypted = '{"ct":"' . $ct . '","iv":"' . $iv . '","s":"' . $s . '"}';
        $password = env('APP_KEY');
        $decrypted = CryptoJsAes::decrypt($encrypted, $password);
        // $decrypted = json_decode($decrypted);
        // echo $decrypted;
        print_r($decrypted, false);
    }

    public function pembayaran(Request $request)
    {
        $id = Crypt::decryptString($request->data);
        $pembayaran = Pembayaran::where('id', $id)->first();
        $transaksi = Transaksi::where('id_pembayaran', $pembayaran->id)->get();
        $alamat = Alamat::where('id', $transaksi[0]->id_alamat)->first();
        $transaksi = $transaksi->map(
            function ($item) {
                $item->nama_toko = Toko::where('id', $item->id_toko)->first()->nama_toko;
                $item->products = TransaksiItem::where('id_transaksi', $item->id)->get();
                return $item;
            }
        );

        $data = array(
            'pembayaran' => $pembayaran,
            'alamat' => $alamat,
            'transaksi' => $transaksi
        );

        return response()->json($data);
    }
}
