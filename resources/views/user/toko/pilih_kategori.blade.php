@extends('user/toko/layout/main')

@section('title', 'Rumah Kreatif Toba - Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Toko</li>
@endsection

@section('container')
<div class="tab-pane fade show active" id="tab-toko" role="tabpanel" aria-labelledby="tab-toko-link">
    @foreach($product_categories as $product_categories)
        <a href="../tambah_produk/{{$product_categories->category_id}}" class="btn btn-link btn-link-dark" style="margin-bottom:3px">{{$product_categories->nama_kategori}}</a>
    @endforeach
</div><!-- .End .tab-pane -->

@endsection

