<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\File;

class CarouselController extends Controller
{
    public function carousel() {
        $carousels = DB::table('carousels')->orderBy('id', 'desc')->get();

        return view('admin.carousel')->with('carousels', $carousels);
    }

    public function PostTambahCarousel(Request $request) {
        $carousel_image = $request -> file('carousel_image');
        $link_carousel = $request -> link_carousel;
        $open_in_new_tab = $request -> open_in_new_tab;

        $nama_carousel_image = time().'_'.$carousel_image->getClientOriginalName();
        $tujuan_upload = './asset/u_file/carousel_image';
        $carousel_image->move($tujuan_upload,$nama_carousel_image);

        DB::table('carousels')->insert([
            'carousel_image' => $nama_carousel_image,
            'link_carousel' => $link_carousel,
            'open_in_new_tab' => $open_in_new_tab,
        ]);

        return redirect('./carousel');
    }

    public function PostEditCarousel(Request $request, $id) {
        $carousel_image = $request -> file('carousel_image');
        $link_carousel = $request -> link_carousel;
        $open_in_new_tab = $request -> open_in_new_tab;

        if(!$carousel_image){
            DB::table('carousels')->where('id', $id)->update([
                'link_carousel' => $link_carousel,
                'open_in_new_tab' => $open_in_new_tab,
            ]);
        }

        if($carousel_image){
            $carousels_lama = DB::table('carousels')->where('id', $id)->first();
            $asal_gambar = 'asset/u_file/carousel_image/';
            $carousel_image_lama = public_path($asal_gambar . $carousels_lama->carousel_image);

            if(File::exists($carousel_image_lama)){
                File::delete($carousel_image_lama);
            }

            $nama_carousel_image = time().'_'.$carousel_image->getClientOriginalName();
            $tujuan_upload = './asset/u_file/carousel_image';
            $carousel_image->move($tujuan_upload,$nama_carousel_image);
            
            DB::table('carousels')->where('id', $id)->update([
                'carousel_image' => $nama_carousel_image,
                'link_carousel' => $link_carousel,
                'open_in_new_tab' => $open_in_new_tab,
            ]);
        }

        return redirect('./carousel');
    }

    public function HapusCarousel($id)
    {
        $carousels_lama = DB::table('carousels')->where('id', $id)->first();
        $asal_gambar = 'asset/u_file/carousel_image/';
        $carousel_image_lama = public_path($asal_gambar . $carousels_lama->carousel_image);

        if(File::exists($carousel_image_lama)){
            File::delete($carousel_image_lama);
        }

        DB::table('carousels')->where('id', $id)->delete();
        
        return redirect('./carousel');
    }
}
