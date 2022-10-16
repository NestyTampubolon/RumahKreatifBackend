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
            $("#city").append('<option value="">Pilih Kabupaten/Kota</option>');

            $("#subdistrict").empty();
            $("#subdistrict").append('<option value="">Pilih Kecamatan</option>');

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
            $("#subdistrict").append('<option value="">Pilih Kecamatan</option>');

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

$("#voucher").change(function (data) {
    console.log($(this).val());
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/ambil_voucher",
        data: { voucher: $(this).val(), merchant_id: $merchant_id },
        success: function (data) {
            console.log(data)

            $("#total_harga_checkout").empty();

            $("#total_harga_checkout").append($('<td>', { text: "Total:", }))
            $("#total_harga_checkout").append($('<td>', { text: data, }))
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
            $("#pengiriman_table").hide();

            
            $("#checkout").empty();
           
            $("#checkout").append($('<button>', {
                class: "btn btn-primary btn-order btn-block",
                text: "BELI SEKARANG",
            }))
        }
    });
});

$("#pesanan_dikirim").click(function (data) {
    console.log($(this).val());
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/pilih_metode_pembelian",
        data: { tipe: $(this).val() },
        success: function (data) {
            console.log(data)
            $("#alamat_table").show();
            $("#pengiriman_table").show();
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

            $("#province_address").empty();
            $("#city_address").empty();
            $("#subdistrict_address").empty();

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

            $("#courier").append($('<option>', { value: "jne", text: "JNE", }))
            $("#courier").append($('<option>', { value: "sicepat", text: "SICEPAT", }))
            $("#courier").append($('<option>', { value: "anteraja", text: "ANTERAJA", }))
            $("#courier").append($('<option>', { value: "tiki", text: "TIKI", }))
            $("#courier").append($('<option>', { value: "jnt", text: "J&T", }))
        }
    });
});

$("#courier").change(function (data) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/cek_ongkir",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: "origin=" + $("#merchant_address").val() + "&originType=subdistrict" + "&destinationType=subdistrict" + "&destination=" + $("#subdistrict_address").val() + "&weight=" + $("#weight").val() + "&courier=" + $("#courier").val(),
        complete: function (data) {
            console.log(data);
            $("#checkout").empty();
           
            $("#checkout").append($('<button>', {
                class: "btn btn-primary btn-order btn-block",
                text: "BELI SEKARANG",
            }))
        }
    });
});