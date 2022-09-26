@extends('user/toko/layout/main')

@section('title', 'Rumah Kreatif Toba - Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Toko</li>
@endsection

<style>
    .fileUpload {
        position: relative;
        overflow: hidden;
        padding-bottom: 5px;
    }
    
    .fileUpload input.upload {
        position: absolute;
        top: 0;
        right: 0;
        margin: 0;
        padding: 0;
        font-size: 20px;
        cursor: pointer;
        opacity: 0;
        width:200%;
    }
</style>

@section('container')
@foreach($product as $product)
<div class="tab-pane fade show active" id="tab-toko" role="tabpanel" aria-labelledby="tab-toko-link">
    <form action="../PostEditProduk/{{$product_id}}" id="formProduct" method="post" enctype="multipart/form-data">
    @csrf
        <label>Kategori :</label>
        <label>{{$product->nama_kategori}}</label><br>
        
        <label>Deskripsi :</label>
        <label>
            @foreach($product_specifications as $product_specifications)
                {{$product_specifications->nama_spesifikasi}},
            @endforeach
        </label><br>

        <div class="mb-1"></div>

        <label>Nama Produk *</label>
        <input type="text" name="product_name" class="form-control" value="{{$product->product_name}}" required>
        
        <label>Deskripsi Produk *</label>
        <input type="text" name="product_description" class="form-control" value="{{$product->product_description}}" required>
        
        <label>Harga *</label>
        <input type="text" name="price" class="form-control" onkeypress="return hanyaAngka(event)" value="{{$product->price}}" required>
        
        <label>Gambar Produk *</label>
        <div class="fileUpload">
            <input id="uploadBtn" type="file" name="product_image" class="upload" accept="image/*"/>
            <input class="form-control" id="uploadFile" placeholder="{{$product->product_image}}" disabled="disabled"/>
            <small class="form-text">Pastikan gambar yang anda masukkan dapat dilihat dengan jelas.</small>
        </div>
        
        <script>
            document.getElementById("uploadBtn").onchange = function () {
                document.getElementById("uploadFile").value = this.value;
            };
        </script>
        
        <label>Stok *</label>
        <div class="product-details-quantity">
            <input type="number" id="qty" name="stok" class="form-control" min="1" step="1" data-decimals="0" value="{{$stock->stok}}" required>
        </div>

        <button type="submit" class="btn btn-primary btn-round">
            <span>EDIT</span>
        </button>
    </form>
    
    <script>
            function hanyaAngka(event) {
                var angka = (event.which) ? event.which : event.keyCode
                if ((angka < 48 || angka > 57) )
                    return false;
                return true;
            }
        </script>
</div><!-- .End .tab-pane -->
@endforeach

@endsection

