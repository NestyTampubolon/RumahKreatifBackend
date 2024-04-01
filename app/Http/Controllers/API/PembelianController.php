<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class PembelianController extends Controller
{
    //
    public function daftar_pembelian(Request $request)
    {
        $toko  = DB::table('merchants')->select('merchant_id')->where('user_id', $request->user_id)->first();
        $purchases = DB::table('product_purchases')
            ->whereNotIn('status_pembelian', ["status1_ambil", "status1"])
            ->where('is_cancelled', 0)
            ->select('product_purchases.purchase_id', 'kode_pembelian', 'status_pembelian', 'products.product_id', 'name', 'harga_pembelian', 'ongkir', DB::raw('MIN(product_name) as product_name'), DB::raw('MIN(price) as price'), DB::raw('MIN(jumlah_pembelian_produk) as jumlah_pembelian_produk'))
            ->where('merchant_id', $toko->merchant_id)
            ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
            ->join('profiles', 'purchases.user_id', '=', 'profiles.user_id')
            ->join('users', 'purchases.user_id', '=', 'users.id')
            ->orderBy('product_purchases.purchase_id', 'desc')
            ->groupBy('purchase_id', 'kode_pembelian', 'status_pembelian', 'name', 'harga_pembelian', 'products.product_id', 'ongkir')->get()
            ->map(function ($item) {
                if ($item->status_pembelian == 'status2_ambil' || $item->status_pembelian == 'status2') {
                    $item->status_pembelian = 'Perlu Dikemas';
                } elseif ($item->status_pembelian == 'status3') {
                    $item->status_pembelian = 'Dalam Perjalanan';
                } elseif ($item->status_pembelian == 'status3_ambil') {
                    $item->status_pembelian = 'Belum Diambil';
                } elseif ($item->status_pembelian == 'status4_ambil_a') {
                    $item->status_pembelian = 'Belum Dikonfirmasi Pembeli';
                } elseif ($item->status_pembelian == 'status4' || $item->status_pembelian == 'status4_ambil_b') {
                    $item->status_pembelian = 'Berhasil [Belum Konfirmasi Pembayaran]';
                } elseif ($item->status_pembelian == 'status5' || $item->status_pembelian == 'status5_ambil') {
                    $item->status_pembelian = 'Berhasil [Telah Konfirmasi Pembayaran]';
                } else {
                    $item->status_pembelian = 'Dibatalkan';
                }
                return $item;
            });
        return response()->json(
            $purchases
        );
    }


    public function detail_pembelian(Request $request)
    {
        setlocale(LC_TIME, 'id_ID');
        $purchasesdetail = DB::table('purchases')
            ->where('is_cancelled', 0)
            ->where('purchases.purchase_id', $request->purchase_id)
            ->leftjoin('product_purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->leftjoin('user_address', 'purchases.alamat_purchase', '=', 'user_address.user_address_id')
            ->join('profiles', 'purchases.user_id', '=', 'profiles.user_id')
            ->leftjoin('products', 'product_purchases.product_id', '=', 'products.product_id')
            ->orderBy('purchases.kode_pembelian', 'desc')->get()
            ->map(function ($item) {
                if ($item->status_pembelian == 'status2_ambil') {
                    $item->status_pembelian = 'Perlu Dikemas';
                } elseif ($item->status_pembelian == 'status2') {
                    $item->status_pembelian = 'Perlu Dikemas dan Masukkan Nomor Resi';
                } elseif ($item->status_pembelian == 'status3') {
                    $item->status_pembelian = 'Dalam Perjalanan';
                } elseif ($item->status_pembelian == 'status3_ambil') {
                    $item->status_pembelian = 'Belum Diambil';
                } elseif ($item->status_pembelian == 'status4_ambil_a') {
                    $item->status_pembelian = 'Belum Dikonfirmasi Pembeli';
                } elseif ($item->status_pembelian == 'status4' || $item->status_pembelian == 'status4_ambil_b') {
                    $item->status_pembelian = 'Berhasil [Belum Konfirmasi Pembayaran]';
                } elseif ($item->status_pembelian == 'status5' || $item->status_pembelian == 'status5_ambil') {
                    $item->status_pembelian = 'Berhasil [Telah Konfirmasi Pembayaran]';
                } else {
                    $item->status_pembelian = '';
                }
                if ($item->courier_code == "pos") {
                    $item->courier_code = "POS Indonesia (POS)";
                } else if ($item->courier_code == "jne") {
                    $item->courier_code = "Jalur Nugraha Eka (JNE)";
                } else {
                    $item->courier_code = '';
                }
                return $item;
            });
        return response()->json(
            $purchasesdetail
        );
    }

    public function update_status_pembelian(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');


        $purchases = DB::table('purchases')->where('purchase_id', $request->purchase_id)->first();

        if ($purchases->status_pembelian == "status1") {
            DB::table('purchases')->where('purchase_id', $request->purchase_id)->update([
                'status_pembelian' => 'status2',
                'updated_at' => Carbon::now(),
            ]);
        } else if ($purchases->status_pembelian == "status3") {
            DB::table('purchases')->where('purchase_id', $request->purchase_id)->update([
                'status_pembelian' => 'status4',
                'updated_at' => Carbon::now(),
            ]);
        } else if ($purchases->status_pembelian == "status4") {
            DB::table('purchases')->where('purchase_id', $request->purchase_id)->update([
                'status_pembelian' => 'status5',
                'updated_at' => Carbon::now(),
            ]);
        }

        if ($purchases->status_pembelian == "status1_ambil") {
            DB::table('purchases')->where('purchase_id', $request->purchase_id)->update([
                'status_pembelian' => 'status2_ambil',
                'updated_at' => Carbon::now(),
            ]);
        } else if ($purchases->status_pembelian == "status2_ambil") {
            DB::table('purchases')->where('purchase_id', $request->purchase_id)->update([
                'status_pembelian' => 'status3_ambil',
                'updated_at' => Carbon::now(),
            ]);
        } else if ($purchases->status_pembelian == "status3_ambil") {
            DB::table('purchases')->where('purchase_id', $request->purchase_id)->update([
                'status_pembelian' => 'status4_ambil_a',
                'updated_at' => Carbon::now(),
            ]);
        } else if ($purchases->status_pembelian == "status4_ambil_a") {
            DB::table('purchases')->where('purchase_id', $request->purchase_id)->update([
                'status_pembelian' => 'status4_ambil_b',
                'updated_at' => Carbon::now(),
            ]);
        } else if ($purchases->status_pembelian == "status4_ambil_b") {
            DB::table('purchases')->where('purchase_id', $request->purchase_id)->update([
                'status_pembelian' => 'status5_ambil',
                'updated_at' => Carbon::now(),
            ]);
        }

        return response()->json(
            200
        );
    }

    public function update_status2_pembelian(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $purchases = DB::table('purchases')->where('purchase_id', $request->purchase_id)->first();

        if ($purchases->status_pembelian == "status2" || $purchases->status_pembelian == "status3") {

            DB::table('purchases')->where('purchase_id', $request->purchase_id)->update([
                'no_resi' => $request->no_resi,
                'status_pembelian' => 'status3',
                'updated_at' => Carbon::now(),
            ]);
            return response()->json(
                200
            );
        }
    }

    public function daftarPembelianApi()
    {
            // Ambil semua data pembelian dari database
            $purchases = DB::table('product_purchases')
                ->select(
                    'product_purchases.purchase_id',
                    'kode_pembelian',
                    'status_pembelian',
                    'name',
                    'username'
                )
                ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
                ->join('profiles', 'purchases.user_id', '=', 'profiles.user_id')
                ->join('users', 'purchases.user_id', '=', 'users.id')
                ->orderBy('product_purchases.purchase_id', 'desc')
                ->groupBy('purchase_id', 'kode_pembelian', 'status_pembelian', 'name', 'username')
                ->get();
    
            // Jumlah pembelian
            $jumlah_purchases = DB::table('product_purchases')
                ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
                ->groupBy('purchase_id')
                ->count();
    
            // Detail pembelian produk
            $product_purchases = DB::table('product_purchases')
                ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
                ->orderBy('product_purchases.product_purchase_id', 'desc')
                ->get();
    
            // Spesifikasi produk
            $product_specifications = DB::table('product_specifications')
                ->join('products', 'product_specifications.product_id', '=', 'products.product_id')
                ->join('specifications', 'product_specifications.specification_id', '=', 'specifications.specification_id')
                ->join('specification_types', 'specifications.specification_type_id', '=', 'specification_types.specification_type_id')
                ->get();
    
            // Jumlah bukti pembayaran
            $count_proof_of_payment = DB::table('proof_of_payments')->count();
    
            // Bukti pembayaran
            $proof_of_payments = DB::table('proof_of_payments')->get();
    
            // Return data dalam format JSON
            return response()->json([
                'purchases' => $purchases,
                'jumlah_purchases' => $jumlah_purchases,
                'product_purchases' => $product_purchases,
                'product_specifications' => $product_specifications,
                'proof_of_payments' => $proof_of_payments,
                'count_proof_of_payment' => $count_proof_of_payment
            ]);
        
    }

}
