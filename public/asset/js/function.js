$.ajax({
    type: "GET",
    dataType: "json",
    url: "/ambil_lokasi",
    data: "url=" + "province",
    complete: function (data) {
        console.log(data);
        data.then((result) => {
            var _data = $.parseJSON(result);
            _data["rajaongkir"]["results"].forEach((province) => {
                $("#province").append($('<option>', {
                    value: province["province_id"],
                    text: province["province"],
                }));
            });
        })
    }
});


$("#province").change(function (data) {
    console.log($(this).val());
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/ambil_lokasi",
        data: "url=" + "city?province=" + $(this).val(),
        complete: function (data) {
            console.log(data);

            $("#city").empty();
            $("#city").append('<option value="" disabled selected>Pilih Kabupaten/Kota</option>');

            $("#subdistrict").empty();
            $("#subdistrict").append('<option value="" disabled selected>Pilih Kecamatan</option>');

            data.then((result) => {
                var _data = $.parseJSON(result);

                _data["rajaongkir"]["results"].forEach((city) => {
                    $("#city").append($('<option>', {
                        value: city["city_id"],
                        text: city["city_name"],
                    }))
                });
            })
        }
    });
});

$("#city").change(function (data) {
    console.log($(this).val());
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/ambil_lokasi",
        data: "url=" + "subdistrict?city=" + $(this).val(),
        complete: function (data) {
            console.log(data);

            $("#subdistrict").empty();
            $("#subdistrict").append('<option value="" disabled selected>Pilih Kecamatan</option>');

            data.then((result) => {
                var _data = $.parseJSON(result);
                _data["rajaongkir"]["results"].forEach((subdistrict) => {
                    $("#subdistrict").append($('<option>', {
                        value: subdistrict["subdistrict_id"],
                        text: subdistrict["subdistrict_name"],
                    }))
                });
            })
        }
    });
});

$("#voucher_pembelian").change(function (data) {
    console.log($(this).val());
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/ambil_voucher_pembelian",
        data: { voucher: $(this).val(), merchant_id: $merchant_id, total_harga_checkout: $total_harga_checkout },
        success: function (data) {
            console.log(data)

            $("#disabled_service").remove();
            $("#disabled_service").append('<option value="" id="disabled_service" disabled selected>Pilih Servis</option>');
            
            $("#disabled_voucher_ongkir").remove();
            $("#voucher_ongkos_kirim").append('<option value="" id="disabled_voucher_ongkir" disabled selected>Pilih Voucher Ongkos Kirim</option>');
            $("#voucher_ongkir_table").hide();

            function format_rupiah(nominal){
                var  reverse = nominal.toString().split('').reverse().join(''),
                     ribuan = reverse.match(/\d{1,3}/g);
                 return ribuan	= ribuan.join('.').split('').reverse().join('');
            }

            // $total_harga_checkout_mentah = parseInt(data);
            
            $potongan_pembelian = parseInt(data["potongan"]);
            $target_metode_pembelian = data["target_metode_pembelian"];

            if($target_metode_pembelian == "ambil_ditempat"){
                $("#tr_ambil_ditempat").show();
                $("#tr_pesanan_dikirim").hide();
                $("#alamat_table").hide();
                $("#province_address_row").hide();
                $("#province_address").empty();
                $("#province_address").append('<option value="" disabled selected>Pilih Provinsi</option>');
                $("#city_address_row").hide();
                $("#city_address").empty();
                $("#city_address").append('<option value="" disabled selected>Pilih Kabupaten/Kota</option>');
                $("#subdistrict_address_row").hide();
                $("#subdistrict_address").empty();
                $("#subdistrict_address").append('<option value="" disabled selected>Pilih Alamat Kecamatan</option>');
                $("#pengiriman_table").hide();
                $("#service_row").hide();
                $("#disabled_voucher_ongkir").remove();
                $("#voucher_ongkos_kirim").append('<option value="" id="disabled_voucher_ongkir" disabled selected>Pilih Voucher Ongkos Kirim</option>');
                $("#voucher_ongkir_table").hide();
    
                function format_rupiah(nominal){
                    var  reverse = nominal.toString().split('').reverse().join(''),
                         ribuan = reverse.match(/\d{1,3}/g);
                     return ribuan	= ribuan.join('.').split('').reverse().join('');
                }

                $("#invoice_ongkir").empty();
                $("#total_potongan_ongkir").empty();

                $ongkir = 0;
                $potongan_ongkir = 0;
                
                $("#total_harga_checkout").empty();
                $("#total_harga_checkout").append($('<a>', { text: "Rp."+ format_rupiah(parseInt($total_harga_checkout) + parseInt($ongkir) - parseInt($potongan_pembelian) - parseInt($potongan_ongkir)), }))
    
                $("#checkout").empty();
            }

            else if($target_metode_pembelian == "pesanan_dikirim"){
                $("#tr_pesanan_dikirim").show();
                $("#tr_ambil_ditempat").hide();

                $("#alamat_table").show();

                $("#disabled_alamat").remove();
                $("#street_address").append('<option value="" id="disabled_alamat" disabled selected>Pilih Alamat Pengiriman</option>');

                $("#checkout").empty();
            }

            else if($target_metode_pembelian == null){
                $("#tr_pesanan_dikirim").show();
                $("#tr_ambil_ditempat").show();
            }

            $("#jumlah_potongan_subtotal").empty();
            $("#jumlah_potongan_subtotal").append($('<input>', { 
                name: "potongan_pembelian",
                value: $potongan_pembelian,
                hidden: "hidden",
            }))

            $("#jumlah_potongan_subtotal").append($('<td>', { text: "Voucher Pembelian: " }))
            $("#jumlah_potongan_subtotal").append($('<td>', { text: "- Rp."+ format_rupiah($potongan_pembelian), }))

            $("#total_harga_checkout").empty();
            $("#total_harga_checkout").append($('<a>', { text: "Rp."+ format_rupiah(parseInt($total_harga_checkout) + parseInt($ongkir) - parseInt($potongan_pembelian) - parseInt($potongan_ongkir)), }))
            // $("#total_harga_checkout").append($('<a>', { text: data["tipe_pembelian"] }))
            
            $("#checkout").empty();
        }
    });
});

$("#voucher_ongkos_kirim").change(function (data) {
    console.log($(this).val());
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/ambil_voucher_ongkos_kirim",
        data: { voucher_ongkir: $(this).val() },
        success: function (data) {
            console.log(data)

            function format_rupiah(nominal){
                var  reverse = nominal.toString().split('').reverse().join(''),
                     ribuan = reverse.match(/\d{1,3}/g);
                 return ribuan	= ribuan.join('.').split('').reverse().join('');
            }

            $potongan_ongkir = parseInt(data);

            // $potongan_ongkos_kirim = parseInt(data);
            
            $ongkir_hasil_potong = parseInt($ongkir) - parseInt($potongan_ongkir);

            if($ongkir > $potongan_ongkir){
                $potongan_ongkir = parseInt(data);
            }
            
            else if($ongkir <= $potongan_ongkir){
                $potongan_ongkir = $ongkir;
            }

            // if($ongkir_hasil_potong < 0){
            //     $ongkir_hasil_potong = 0;
            // }

            $("#total_harga_checkout").empty();
            
            // if($total_harga_checkout_mentah == 0){
            //     $("#total_harga_checkout").append($('<a>', { text: "Rp."+ format_rupiah(parseInt($total_harga_checkout) + parseInt($ongkir_hasil_potong)), }))
            // }

            // else{
            //     $("#total_harga_checkout").append($('<a>', { text: "Rp."+ format_rupiah(parseInt($total_harga_checkout_mentah) + parseInt($ongkir_hasil_potong)), }))
            // }

            $("#total_harga_checkout").append($('<a>', { text: "Rp."+ format_rupiah(parseInt($total_harga_checkout) + parseInt($ongkir) - parseInt($potongan_pembelian) - parseInt($potongan_ongkir)), }))

            $("#total_potongan_ongkir").empty();
            $("#total_potongan_ongkir").append($('<td>', { text: "Voucher Ongkos Kirim: " }))
            $("#total_potongan_ongkir").append($('<td>', { text: "- Rp."+ format_rupiah($potongan_ongkir), }))

            $("#total_harga_checkout").append($('<input>', { 
                name: "ongkir",
                value: $ongkir,
                hidden: "hidden",
            }))
            
        }
    });
});

$("#ambil_ditempat").click(function (data) {
    console.log($(this).val());
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/pilih_metode_pembelian",
        data: { tipe: $(this).val() },
        success: function (data) {
            console.log(data)

            $("#alamat_table").hide();
            $("#province_address_row").hide();
            $("#province_address").empty();
            $("#province_address").append('<option value="" disabled selected>Pilih Provinsi</option>');
            $("#city_address_row").hide();
            $("#city_address").empty();
            $("#city_address").append('<option value="" disabled selected>Pilih Kabupaten/Kota</option>');
            $("#subdistrict_address_row").hide();
            $("#subdistrict_address").empty();
            $("#subdistrict_address").append('<option value="" disabled selected>Pilih Alamat Kecamatan</option>');
            $("#pengiriman_table").hide();
            $("#service_row").hide();
            $("#disabled_voucher_ongkir").remove();
            $("#voucher_ongkos_kirim").append('<option value="" id="disabled_voucher_ongkir" disabled selected>Pilih Voucher Ongkos Kirim</option>');
            $("#voucher_ongkir_table").hide();

            function format_rupiah(nominal){
                var  reverse = nominal.toString().split('').reverse().join(''),
                     ribuan = reverse.match(/\d{1,3}/g);
                 return ribuan	= ribuan.join('.').split('').reverse().join('');
            }


            $("#invoice_ongkir").empty();
            $("#total_potongan_ongkir").empty();
            $ongkir = 0;
            $potongan_ongkir = 0;
            
            $("#total_harga_checkout").empty();

            // if($total_harga_checkout_mentah == 0){
            //     $("#total_harga_checkout").append($('<a>', { text: "Rp."+ format_rupiah($total_harga_checkout), }))
            // }
            // else{
            //     $("#total_harga_checkout").append($('<a>', { text: "Rp."+ format_rupiah($total_harga_checkout_mentah), }))
            // }
            
            $("#total_harga_checkout").append($('<a>', { text: "Rp."+ format_rupiah(parseInt($total_harga_checkout) + parseInt($ongkir) - parseInt($potongan_pembelian) - parseInt($potongan_ongkir)), }))

            $("#checkout").empty();
           
            $("#checkout").append($('<button>', {
                class: "btn btn-primary btn-order btn-block",
                text: "BELI SEKARANG",
            }))
        }
    });
});

$("#pesanan_dikirim").change(function (data) {
    console.log($(this).val());
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/pilih_metode_pembelian",
        data: { tipe: $(this).val() },
        success: function (data) {
            console.log(data)

            $("#alamat_table").show();
            
            $("#disabled_alamat").remove();
            $("#street_address").append('<option value="" id="disabled_alamat" disabled selected>Pilih Alamat Pengiriman</option>');

            $("#checkout").empty();

        }
    });
});

$("#street_address").change(function (data) {
    console.log($(this).val());
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/ambil_jalan",
        data: { id: $(this).val(), merchant_id: $merchant_id },
        success: function (data) {
            console.log(data)

            $location = data["filtered"];
            $shipping_local = data["shipping_local"];

            $("#province_address_row").show();
            $("#province_address").empty();
            $("#city_address_row").show();
            $("#city_address").empty();
            $("#subdistrict_address_row").show();
            $("#subdistrict_address").empty();

            $("#courier").empty();
            $("#courier").append('<option value="" disabled selected>Pilih Kurir</option>');
            
            $("#service_row").hide();
            
            $("#disabled_voucher_ongkir").remove();
            $("#voucher_ongkos_kirim").append('<option value="" id="disabled_voucher_ongkir" disabled selected>Pilih Voucher Ongkos Kirim</option>');
            $("#voucher_ongkir_table").hide();
            
            $("#checkout").empty();

            $("#province_address").append($('<option>', {
                value: $location["province_id"],
                text: $location["province"],
                selected: true,
            }))

            $("#city_address").append($('<option>', {
                value: $location["city_id"],
                text: $location["city"],
                selected: true,
            }))

            $("#subdistrict_address").append($('<option>', {
                value: $location["subdistrict_id"],
                text: $location["subdistrict_name"],
                selected: true,
            }))
            
            $("#pengiriman_table").show();
            $("#courier").append($('<option>', { value: "pos", text: "POS Indonesia", }))
            $("#courier").append($('<option>', { value: "jne", text: "JNE", }))
            // if($shipping_local){
            //     $("#courier").append($('<option>', { id: "pengiriman_lokal", value: $location["shipping_local_id "], text: "Pengiriman Loka Oleh Toko", }))
            // }
            
            $("#servis_row").hide();

            $("#invoice_ongkir").empty();
            $("#total_potongan_ongkir").empty();
            $ongkir = 0;
            $potongan_ongkir = 0;
        }
    });
});

$("#courier").change(function (data) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/cek_ongkir",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: "origin=" + $subdistrict_id + "&originType=subdistrict" + "&destinationType=subdistrict" + "&destination=" + $("#subdistrict_address").val() + "&weight=" + $total_berat + "&courier=" + $("#courier").val(),
        complete: function (data) {
            console.log(data);
            $("#checkout").empty();

            $("#service").empty();
            $("#service").append('<option value="" disabled selected>Pilih Servis</option>');

            $("#disabled_voucher_ongkir").remove();
            $("#voucher_ongkos_kirim").append('<option value="" id="disabled_voucher_ongkir" disabled selected>Pilih Voucher Ongkos Kirim</option>');
            $("#voucher_ongkir_table").hide();

            data.then((result) => {
                var _data = $.parseJSON(result);
                _data["rajaongkir"]["results"].forEach((costs, indexC) => {

                    costs["costs"].forEach((cost, indexCC)=>{
                      
                        cost["cost"].forEach((price)=>{
                            $("#service_row").show();
                            $("#service").append($('<option>', {
                                value: cost["description"],
                                text: cost["description"] + " ( " + price["etd"] + " hari )",
                                tarif: price["value"],
                            }))

                            $('#service').change(function() {

                                $("#disabled_voucher_ongkir").remove();
                                $("#voucher_ongkos_kirim").append('<option value="" id="disabled_voucher_ongkir" disabled selected>Pilih Voucher Ongkos Kirim</option>');
                                $("#voucher_ongkir_table").show();

                                $("#total_potongan_ongkir").empty();
                                $potongan_ongkir = 0;

                                $("#checkout").empty();
                                $("#checkout").append($('<button>', {
                                    class: "btn btn-primary btn-order btn-block",
                                    text: "BELI SEKARANG",
                                }))

                                var ongkir = $(this).find('option:selected').attr('tarif')

                                $("#total_harga_checkout").empty();

                                // if($total_harga_checkout_mentah == 0){
                                //     $("#total_harga_checkout").append($('<a>', { text: "Rp."+ format_rupiah(parseInt($total_harga_checkout) + parseInt(ongkir)), }))
                                // }

                                // else{
                                //     $("#total_harga_checkout").append($('<a>', { text: "Rp."+ format_rupiah(parseInt($total_harga_checkout_mentah) + parseInt(ongkir)), }))
                                // }

                                
                                $("#total_harga_checkout").append($('<a>', { text: "Rp."+ format_rupiah(parseInt($total_harga_checkout) + parseInt($ongkir) - parseInt($potongan_pembelian) - parseInt($potongan_ongkir)), }))
                                
                                $ongkir = parseInt(ongkir);

                                
                                $("#invoice_ongkir").empty();
                                $("#invoice_ongkir").append($('<td>', { text: "Ongkos Kirim: " }))
                                $("#invoice_ongkir").append($('<td>', { text: "Rp."+ format_rupiah(ongkir), }))

                                $("#total_harga_checkout").append($('<input>', { 
                                    name: "ongkir",
                                    value: $ongkir,
                                    hidden: "hidden",
                                }))
                            })
                        
                            function format_rupiah(nominal){
                                var  reverse = nominal.toString().split('').reverse().join(''),
                                     ribuan = reverse.match(/\d{1,3}/g);
                                 return ribuan	= ribuan.join('.').split('').reverse().join('');
                            }
                        });
                    });
                });
            })
        }
    });
});