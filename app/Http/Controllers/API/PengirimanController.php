<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;

use Carbon\Carbon;

class PengirimanController extends Controller
{
    //

    public function PostBeliProduk(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $user_id = $request->user_id;

        $kode_pembelian = 'rkt_' . time();

        $voucher_pembelian = $request->voucher_pembelian;
        $voucher_ongkos_kirim = $request->voucher_ongkos_kirim;

        $potongan_pembelian = $request->potongan_pembelian;

        $alamat_purchase = $request->alamat_purchase;

        $courier_code = $request->courier;
        $service = $request->service;

        DB::table('checkouts')->insert([
            'user_id' => $user_id,
        ]);

        $checkout_id = DB::table('checkouts')->select('checkout_id')->orderBy('checkout_id', 'desc')->first();

        if ($voucher_pembelian) {
            DB::table('claim_vouchers')->insert([
                'checkout_id' => $checkout_id->checkout_id,
                'voucher_id' => $voucher_pembelian,
            ]);
        }

        if ($voucher_ongkos_kirim) {
            DB::table('claim_vouchers')->insert([
                'checkout_id' => $checkout_id->checkout_id,
                'voucher_id' => $voucher_ongkos_kirim,
            ]);
        }

        $merchant_ids = $request->merchant_id;
        $metodes = $request->metode_pembelian;
        $harga_pembelians = $request->harga_pembelian;

        $count = count($merchant_ids);

        for ($i = 0; $i < $count; $i++) {
            $merchant_id = $merchant_ids[$i];
            $metode = $metodes[$i];
            $harga_pembelian = $harga_pembelians[$i];

            // your code here
            if ($metode == 1) {
                $purchase_id = DB::table('purchases')
                    ->insertGetId([
                        'kode_pembelian' => $kode_pembelian,
                        'user_id' => $user_id,
                        'checkout_id' => $checkout_id->checkout_id,
                        'alamat_purchase' => "",
                        'harga_pembelian' => $harga_pembelian,
                        'potongan_pembelian' => $potongan_pembelian,
                        'status_pembelian' => "status1_ambil",
                        'ongkir' => 0,
                        'is_cancelled' => 0,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
            }

            if ($metode == 2) {
                $ongkir = $request->ongkir;
                $purchase_id = DB::table('purchases')
                    ->insertGetId([
                        'kode_pembelian' => $kode_pembelian,
                        'user_id' => $user_id,
                        'checkout_id' => $checkout_id->checkout_id,
                        'alamat_purchase' => $alamat_purchase,
                        'harga_pembelian' => $harga_pembelian,
                        'potongan_pembelian' => $potongan_pembelian,
                        'status_pembelian' => "status1",
                        'courier_code' => $courier_code,
                        'service' => $service,
                        'ongkir' => $ongkir,
                        'is_cancelled' => 0,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
            }

            foreach ($request->cart_id as $cart_id) {
                $product_purchase = DB::table('carts')
                    ->select('carts.product_id', 'heavy', 'jumlah_masuk_keranjang', 'price')
                    ->where('user_id', $user_id)
                    ->where('cart_id', $cart_id)
                    ->where('merchant_id', $merchant_id)
                    ->join('products', 'carts.product_id', '=', 'products.product_id')
                    ->get();

                foreach ($product_purchase as $product_purchase) {
                    DB::table('product_purchases')->insert([
                        'purchase_id' => $purchase_id,
                        'product_id' => $product_purchase->product_id,
                        'berat_pembelian_produk' => $product_purchase->jumlah_masuk_keranjang * $product_purchase->heavy,
                        'jumlah_pembelian_produk' => $product_purchase->jumlah_masuk_keranjang,
                        'harga_pembelian_produk' => $product_purchase->jumlah_masuk_keranjang * $product_purchase->price,
                    ]);

                    $stok = DB::table('stocks')->select('stok')->where('product_id', $product_purchase->product_id)->first();

                    DB::table('stocks')->where('product_id', $product_purchase->product_id)->update([
                        'stok' => $stok->stok - $product_purchase->jumlah_masuk_keranjang,
                    ]);

                    DB::table('carts')->where('user_id', $user_id)->where('product_id', $product_purchase->product_id)->delete();
                }
            }
        }

        return response()->json(
            200
        );
    }


    public function daftar_pembelian(Request $request)
    {
        setlocale(LC_TIME, 'id_ID');
        $user_id = $request->user_id;
        $purchases = DB::table('purchases')->where('user_id', $user_id)->where('is_cancelled', 0)
            ->whereNotIn('status_pembelian', ["status1_ambil"])
            ->where('is_cancelled', 0)
            ->join('users', 'purchases.user_id', '=', 'users.id')
            ->groupBy('kode_pembelian')
            ->select('kode_pembelian', DB::raw('MAX(purchase_id) as purchase_id'), DB::raw('CAST(SUM(harga_pembelian) AS UNSIGNED) as harga_pembelian'), DB::raw("DATE_FORMAT(MAX(purchases.created_at), '%Y-%m-%d') as created_at"), DB::raw('MAX(status_pembelian) as status_pembelian'))
            ->orderBy('kode_pembelian', 'desc')->get()
            ->map(function ($item) {
                $item->created_at = \Carbon\Carbon::createFromFormat('Y-m-d', $item->created_at)->format('d M Y');
                return $item;
            });
        // $purchases = DB::table('purchases')
        // ->join('product_purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
        // ->join('proof_of_payments','proof_of_payments.purchase_id','=', 'purchases.purchase_id')
        // ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
        // ->get();
        return response()->json(
            $purchases
        );
    }

    public function menunggu_pembayaran(Request $request)
    {
        setlocale(LC_TIME, 'id_ID');
        $user_id = $request->user_id;
            $kode_pembelian = DB::table('purchases')
                ->select('kode_pembelian')
                ->where('user_id', $user_id)
                ->where('is_cancelled', 0)
                ->orderBy('kode_pembelian', 'desc')
                ->get();

        $purchases = DB::table('purchases')->where('user_id', $user_id)->where('is_cancelled', 0)
            ->where('status_pembelian', ["status1_ambil"])
            ->where('is_cancelled', 0)
            ->join('users', 'purchases.user_id', '=', 'users.id')
            ->leftJoin('proof_of_payments', 'purchases.purchase_id', '=', 'proof_of_payments.purchase_id')
            ->leftjoin('product_purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->leftjoin('products', 'product_purchases.product_id', '=', 'products.product_id')
            ->whereNull('proof_of_payments.purchase_id')
            ->groupBy('kode_pembelian')
            ->select('kode_pembelian', DB::raw('MAX(purchases.purchase_id) as purchase_id'), DB::raw('CAST(SUM(harga_pembelian) AS UNSIGNED) as harga_pembelian'), DB::raw("DATE_FORMAT(MAX(purchases.created_at), '%Y-%m-%d') as created_at"), DB::raw('MAX(status_pembelian) as status_pembelian'))
            ->orderBy('kode_pembelian', 'desc')->get()
            ->map(function ($item) {
                $item->created_at = \Carbon\Carbon::createFromFormat('Y-m-d', $item->created_at)->format('d M Y');
                return $item;
            });

        return response()->json($purchases);
    }

    public function detail_pesanan(Request $request)
    {

        setlocale(LC_TIME, 'id_ID');
        $user_id = $request->user_id;

        $purchasesdetail = DB::table('purchases')->where('user_id', $user_id)->where('is_cancelled', 0)
            ->where('kode_pembelian', $request->kode_pembelian)
            ->join('users', 'purchases.user_id', '=', 'users.id')
            ->groupBy('kode_pembelian')
            ->select('kode_pembelian', DB::raw('MAX(purchase_id) as purchase_id'), DB::raw('CAST(SUM(harga_pembelian) AS UNSIGNED) as harga_pembelian'), DB::raw("DATE_FORMAT(MAX(purchases.created_at), '%Y-%m-%d') as created_at"), DB::raw('MAX(status_pembelian) as status_pembelian'), DB::raw('MAX(ongkir) as ongkir'))
            ->orderBy('kode_pembelian', 'desc')->get()
            ->map(function ($item) {
                $item->created_at = \Carbon\Carbon::createFromFormat('Y-m-d', $item->created_at)->format('d M Y');
                return $item;
            });

        $purchases = DB::table('purchases')
            ->where('user_id', $user_id)
            ->where('kode_pembelian', $request->kode_pembelian)
            ->leftjoin('product_purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->leftjoin('products', 'product_purchases.product_id', '=', 'products.product_id')
            ->get();

        return response()->json([
            'purchasesdetail' => $purchasesdetail,
            'purchases' => $purchases,
        ]);
    }

    public function hapus(Request $request)
    {
        if (DB::table('purchases')
            ->where('kode_pembelian', '=', $request->kode_pembelian)
            ->update(['is_cancelled' => 1])
        ) {

            return response()->json(
                200
            );
        }
    }

    public function PostBuktiPembayaran(Request $request)
    {
        if ($request->hasFile('proof_of_payment_image')) {
            $proof_of_payment_image = $request->file('proof_of_payment_image');
            $proof_of_payment_image_name = time() . '_' . $proof_of_payment_image->getClientOriginalName();
            $tujuan_upload = './asset/u_file/proof_of_payment_image';
            $proof_of_payment_image->move($tujuan_upload, $proof_of_payment_image_name);

            $purchase_ids = $request->purchase_id;

            DB::table('proof_of_payments')->insert([
                'purchase_id' => $purchase_ids,
                'proof_of_payment_image' => $proof_of_payment_image_name,
            ]);
            return response()->json([
                200
            ]);
        } else {
            return response()->json([
                'error' => 'No image file provided'
            ], 400);
        }
    }
}
