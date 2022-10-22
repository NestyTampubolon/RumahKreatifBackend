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
        data: { voucher: $(this).val(), merchant_id: $merchant_id },
        success: function (data) {
            console.log(data)

            $("#total_harga_checkout").empty();

            // $("#total_harga_checkout").append($('<td>', { text: "Total:", }))
            $("#total_harga_checkout").append($('<a>', { text: data, }))
        }
    });
});

$("#ambil_ditempat").change(function (data) {
    console.log($(this).val());
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/pilih_metode_pembelian",
        data: { tipe: $(this).val() },
        success: function (data) {
            console.log(data)
            
            $("#alamat_table").hide();

            // $("#disabled_alamat").remove();
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
            $("#street_address").append('<option value="" disabled selected id="disabled_alamat">Pilih Alamat Pengiriman</option>');

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
        data: { id: $(this).val() },
        success: function (data) {
            console.log(data)

            $("#province_address_row").show();
            $("#province_address").empty();
            $("#city_address_row").show();
            $("#city_address").empty();
            $("#subdistrict_address_row").show();
            $("#subdistrict_address").empty();

            $("#courier").empty();
            $("#courier").append('<option value="" disabled selected>Pilih Kurir</option>');
            
            $("#checkout").empty();

            $("#province_address").append($('<option>', {
                value: data["province_id"],
                text: data["province"],
                selected: true,
            }))

            $("#city_address").append($('<option>', {
                value: data["city_id"],
                text: data["city"],
                selected: true,
            }))

            $("#subdistrict_address").append($('<option>', {
                value: data["subdistrict_id"],
                text: data["subdistrict_name"],
                selected: true,
            }))
            
            // $("#checkout").append($('<div>', {
            //     class: "btn btn-outline-primary-2 btn-order btn-block",
            //     text: "CHECKOUT",
            // }))
            
            $("#pengiriman_table").show();
            $("#courier").append($('<option>', { value: "pos", text: "POS Indonesia", }))
            $("#courier").append($('<option>', { value: "jne", text: "JNE", }))
            $("#courier").append($('<option>', { value: "jnt", text: "J&T", }))
            
            $("#servis_row").hide();
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

            data.then((result) => {
                var _data = $.parseJSON(result);
                _data["rajaongkir"]["results"].forEach((costs, indexC) => {

                    costs["costs"].forEach((cost, indexCC)=>{
                      
                        cost["cost"].forEach((price)=>{
                            $("#service_row").show();
                            $("#service").append($('<option>', {
                                value: cost["service"],
                                text: cost["description"] + " ( " + price["etd"] + " hari )",
                                tarif: price["value"],
                            }))

                            $("#total_harga_checkout").empty();

                            $("#total_harga_checkout").append($('<a>', { text: "Rp. "+ format_rupiah(parseInt($total_harga_checkout)), }))

                            $('#service').change(function() {

                                $("#checkout").empty();
                                $("#checkout").append($('<button>', {
                                    class: "btn btn-primary btn-order btn-block",
                                    text: "BELI SEKARANG",
                                }))

                                var ongkir = $(this).find('option:selected').attr('tarif')
                                
                                $("#total_harga_checkout").empty();

                                $("#total_harga_checkout").append($('<a>', { text: "Rp. "+ format_rupiah(parseInt($total_harga_checkout) + parseInt(ongkir)), }))
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