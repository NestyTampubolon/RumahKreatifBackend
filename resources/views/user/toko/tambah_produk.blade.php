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
    
    .checkbox_specification_types {
        border: 1px solid rgba(0,0,0,.2);
        height: 150px;
        overflow: scroll;
        margin-bottom: 15px;
    }
    
    .checkbox_specification_types div{
        padding: 2px;
        margin-left: 5px
    }
</style>

@section('container')
<div class="tab-pane fade show active" id="tab-toko" role="tabpanel" aria-labelledby="tab-toko-link">
    <form action="../PostTambahProduk/{{$kategori_produk_id}}" id="formProduct" method="post" enctype="multipart/form-data">
    @csrf
        <label>Nama Produk *</label>
        <input type="text" name="product_name" class="form-control" required>

        
        <label>Harga *</label>
        <input type="text" name="price" class="form-control" onkeypress="return hanyaAngka(event)" required>
        
        <script>
            function hanyaAngka(event) {
                var angka = (event.which) ? event.which : event.keyCode
                if ((angka < 48 || angka > 57) )
                    return false;
                return true;
            }
        </script>
        
        <label>Gambar Produk *</label>
        <div class="fileUpload">
            <input id="uploadBtn" type="file" name="product_image" class="upload" accept="image/*" required/>
            <input class="form-control" id="uploadFile" placeholder="Pilih Foto..." disabled="disabled"/>
            <small class="form-text">Pastikan gambar yang anda masukkan dapat dilihat dengan jelas.</small>
        </div>
        
        <script>
            document.getElementById("uploadBtn").onchange = function () {
                document.getElementById("uploadFile").value = this.value;
            };
        </script>

        @foreach($category_type_specifications as $category_type_specifications)
            <label>{{$category_type_specifications->nama_jenis_spesifikasi}} *</label>
            <div class="checkbox_specification_types">
            @foreach($specifications as $specification)
                @if($specification->specification_type_id == $category_type_specifications->specification_type_id)
                <div>
                    <input type="checkbox" name="specification_id[]" id="divisi[{{$specification->specification_id}}]" value="{{$specification->specification_id}}">
                    <label for="divisi[{{$specification->specification_id}}]">{{$specification->nama_spesifikasi}}</label>
                </div>
                @endif
            @endforeach
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary btn-round">
            <span>KIRIM</span>
        </button>
    </form>
</div><!-- .End .tab-pane -->

@endsection

