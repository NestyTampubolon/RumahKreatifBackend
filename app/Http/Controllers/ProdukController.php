<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;
use Illuminate\Support\Facades\File;

class ProdukController extends Controller
{
    public function produk() {
        $toko = Session::get('toko');

        if($toko){
            $products = DB::table('products')->where('merchant_id', $toko)->orderBy('product_id', 'desc')
            ->join('categories', 'products.category_id', '=', 'categories.category_id')->get();

            return view('user.toko.produk')->with('products', $products);
        }

        else if(!$toko){
            $kategori_produk_id = 0;
            
            $products = DB::table('products')->orderBy('product_id', 'desc')->join('categories', 'products.category_id', '=', 'categories.category_id')
            ->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')->get();
            
            $product_info = DB::table('products')->orderBy('product_id', 'desc')->join('categories', 'products.category_id', '=', 'categories.category_id')
            ->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')->first();
            
            $categories = DB::table('categories')->orderBy('nama_kategori', 'asc')->get();
            
            // $nama_kategori = DB::table('categories')->where('category_id', $kategori_produk_id)->first();

            return view('user.produk')->with('products', $products)->with('product_info', $product_info)->with('categories', $categories)
            ->with('kategori_produk_id', $kategori_produk_id);
        }
    }

    public function cari_produk(Request $request)
    {        
		$cari = $request->cari_produk;

            return redirect("./produk/cari/$cari");
    }

    public function cari_produk_view(Request $request)
    {        
		$cari = $request->cari_produk;

        $kategori_produk_id = 0;
        
        $products = DB::table('products')->join('categories', 'products.category_id', '=', 'categories.category_id')
        ->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')->where('product_name', 'like',"%".$cari."%")
        ->orwhere('nama_merchant', 'like',"%".$cari."%")->orderBy('product_name', 'asc')->get();
        
        $product_info = DB::table('products')->orderBy('product_id', 'desc')->join('categories', 'products.category_id', '=', 'categories.category_id')
        ->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')->first();
        
        $categories = DB::table('categories')->orderBy('nama_kategori', 'asc')->get();

        return view('user.produk')->with('products', $products)->with('product_info', $product_info)->with('categories', $categories)->with('kategori_produk_id', $kategori_produk_id);
    }

    public function produk_kategori($kategori_produk_id) {
        $toko = Session::get('toko');

        if($toko){
            // $products = DB::table('products')->where('merchant_id', $toko)->orderBy('product_id', 'desc')
            // ->join('categories', 'products.category_id', '=', 'categories.category_id')->get();

            // return view('user.toko.produk')->with('products', $products);
        }

        else{
            $products = DB::table('products')->where('products.category_id', $kategori_produk_id)->orderBy('product_id', 'desc')
            ->join('categories', 'products.category_id', '=', 'categories.category_id')
            ->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')->get();
            
            $product_info = DB::table('products')->orderBy('product_id', 'desc')->join('categories', 'products.category_id', '=', 'categories.category_id')
            ->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')->first();
            
            $categories = DB::table('categories')->orderBy('nama_kategori', 'asc')->get();

            $nama_kategori = DB::table('categories')->where('category_id', $kategori_produk_id)->first();

            return view('user.produk')->with('products', $products)->with('product_info', $product_info)->with('categories', $categories)->with('kategori_produk_id', $kategori_produk_id)
            ->with('nama_kategori', $nama_kategori);
        }
    }

    public function pilih_kategori() {
        $categories = DB::table('categories')->orderBy('nama_kategori', 'asc')->get();

        return view('user.toko.pilih_kategori')->with('categories', $categories);
    }

    public function tambah_produk($kategori_produk_id) {
        $category_type_specifications = DB::table('category_type_specifications')
        ->join('categories', 'category_type_specifications.category_id', '=', 'categories.category_id')
        ->join('specification_types', 'category_type_specifications.specification_type_id', '=', 'specification_types.specification_type_id')
        ->where('category_type_specifications.category_id', $kategori_produk_id)->orderBy('category_type_specification_id', 'asc')->get();

        $specifications = DB::table('specifications')->orderBy('nama_spesifikasi', 'asc')->get();

        return view('user.toko.tambah_produk')->with('category_type_specifications', $category_type_specifications)->with('specifications', $specifications)
        ->with('kategori_produk_id', $kategori_produk_id);
    }

    public function PostTambahProduk(Request $request, $kategori_produk_id) {
        // $request -> validate([
        //     'merchant_id' => 'required',
        //     'product_name' => 'required',
        //     'price' => 'required',
        //     'product_image' => 'required',
        //     'specification_id' => 'required',
        // ]);

        $toko = Session::get('toko');
        $product_name = $request -> product_name;
        $product_description = $request -> product_description;
        $price = $request -> price;
        $product_image = $request -> file('product_image');

        $nama_product_image = time().'_'.$product_image->getClientOriginalName();
        $tujuan_upload = './asset/u_file/product_image';
        $product_image->move($tujuan_upload,$nama_product_image);
        
        $specification_id = $request -> specification_id;
        
        $stok = $request -> stok;

        // dd($toko, $product_name, $price, $nama_product_image, $specification_id);

        DB::table('products')->insert([
            'merchant_id' => $toko,
            'category_id' => $kategori_produk_id,
            'product_name' => $product_name,
            'product_description' => $product_description,
            'price' => $price,
            'product_image' => $nama_product_image,
        ]);
        
        // $product_id = DB::table('products')->select('product_id')->pluck('product_id');
        $product_id = DB::table('products')->select('product_id')->orderBy('product_id', 'desc')->first();
 
        $jumlah_specification_id_dipilih = count($specification_id);
 
        for($x=0;$x<$jumlah_specification_id_dipilih;$x++){
            DB::table('product_specifications')->insert([
                'product_id' => $product_id->product_id,
                'specification_id' => $specification_id[$x],
            ]);
        }

        DB::table('stocks')->insert([
            'product_id' => $product_id->product_id,
            'stok' => $stok,
        ]);

        return redirect('./produk');
    }
    

    public function edit_produk($product_id) {
        $toko = Session::get('toko');

        $product = DB::table('products')->where('products.merchant_id', $toko)->where('product_id', $product_id)
        ->join('categories', 'products.category_id', '=', 'categories.category_id')->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')->get();
        
        $stock = DB::table('stocks')->where('product_id', $product_id)->first();
        
        $product_specifications = DB::table('product_specifications')->where('product_id', $product_id)
        ->join('specifications', 'product_specifications.specification_id', '=', 'specifications.specification_id')->get();

        return view('user.toko.edit_produk')->with('product', $product)->with('stock', $stock)->with('product_id', $product_id)->with('product_specifications', $product_specifications);
    }

    public function PostEditProduk(Request $request, $product_id) {
        $product_name = $request -> product_name;
        $product_description = $request -> product_description;
        $price = $request -> price;
        $product_image = $request -> file('product_image');
        $stok = $request -> stok;

        if(!$product_image){
            DB::table('products')->where('product_id', $product_id)->update([
                'product_name' => $product_name,
                'product_description' => $product_description,
                'price' => $price,
            ]);

            DB::table('stocks')->where('product_id', $product_id)->update([
                'stok' => $stok,
            ]);
        }

        if($product_image){
            $products_lama = DB::table('products')->where('product_id', $product_id)->first();
            $asal_gambar = 'asset/u_file/product_image/';
            $product_image_lama = public_path($asal_gambar . $products_lama->product_image);

            if(File::exists($product_image_lama)){
                File::delete($product_image_lama);
            }

            $nama_product_image = time().'_'.$product_image->getClientOriginalName();
            $tujuan_upload = './asset/u_file/product_image';
            $product_image->move($tujuan_upload,$nama_product_image);
            
            DB::table('products')->where('product_id', $product_id)->update([
                'product_name' => $product_name,
                'product_description' => $product_description,
                'price' => $price,
                'product_image' => $nama_product_image,
            ]);

            DB::table('stocks')->where('product_id', $product_id)->update([
                'stok' => $stok,
            ]);
        }

        return redirect('../produk');
    }

    public function lihat_produk($product_id) {
        $product = DB::table('products')->where('product_id', $product_id)->join('categories', 'products.category_id', '=', 'categories.category_id')
        ->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')->orderBy('product_id', 'desc')->get();

        // $product_category_id = DB::table('products')->select('category_id')->where('product_id', $product_id)->first();
        
        $product_specifications = DB::table('product_specifications')
        ->join('products', 'product_specifications.product_id', '=', 'products.product_id')
        ->join('specifications', 'product_specifications.specification_id', '=', 'specifications.specification_id')
        ->join('specification_types', 'specifications.specification_type_id', '=', 'specification_types.specification_type_id')
        ->where('product_specifications.product_id', $product_id)->get();
        
        // $category_type_specifications = DB::table('category_type_specifications')
        // ->join('categories', 'category_type_specifications.category_id', '=', 'categories.category_id')
        // ->join('specification_types', 'category_type_specifications.specification_type_id', '=', 'specification_types.specification_type_id')
        // ->where('category_type_specifications.category_id', $product_category_id->category_id)->orderBy('category_type_specification_id', 'asc')->get();

        // $specification_types = DB::table('specification_types')->orderBy('nama_jenis_spesifikasi', 'asc')->get();

        $stocks = DB::table('stocks')->where('product_id', $product_id)->first();
        
        $reviews = DB::table('reviews')->where('product_id', $product_id)->join('profiles', 'reviews.user_id', '=', 'profiles.user_id')->orderBy('review_id', 'desc')->get();
        $jumlah_review = DB::table('reviews')->where('product_id', $product_id)->count();
        
        if(Auth::check()){
            $user_id = Auth::user()->id;
            $cek_review = DB::table('reviews')->where('user_id', $user_id)->where('product_id', $product_id)->first();
        }
        
        else{
            $cek_review = "";
        }

        return view('user.lihat_produk', compact(['product', 'product_specifications', 'stocks', 'reviews', 'cek_review', 'jumlah_review']));
    }

    public function produk_toko() {        
        $products = DB::table('products')->orderBy('product_id', 'desc')->join('categories', 'products.category_id', '=', 'categories.category_id')
        ->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')->get();
        
        $product_specifications = DB::table('product_specifications')
        ->join('products', 'product_specifications.product_id', '=', 'products.product_id')
        ->join('specifications', 'product_specifications.specification_id', '=', 'specifications.specification_id')
        ->join('specification_types', 'specifications.specification_type_id', '=', 'specification_types.specification_type_id')->get();
        
        $stocks = DB::table('stocks')->get();
        
        return view('admin.produk_toko')->with('products', $products)->with('product_specifications', $product_specifications)->with('stocks', $stocks);
    }

}
