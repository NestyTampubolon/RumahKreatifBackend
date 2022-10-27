$("#tambah_produk_keranjang").change(function (data) {
    console.log($(this).val());
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/masuk_keranjang",
        data: { produk: $(this).val() },
        success: function (data) {
            console.log(data)

            $("#jumlah_produk_keranjang").empty();

            $("#jumlah_produk_keranjang").append($('<i>', { class: "icon-shopping-cart", }))

            $("#jumlah_produk_keranjang").append($('<span>', {
                class: "cart-count",
                text: data,
            }))

            jQuery("#tambah_produk_keranjang_modal").modal('show');
        }
    });
});

$("#tipe_voucher").change(function (data) {
    console.log($(this).val());
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/pilih_tipe_voucher",
        data: { tipe: $(this).val() },
        success: function (data) {
            console.log(data)

            if(data == "pembelian"){
                $("#div_checkbox_categories").show();
                $("#potongan_div").show();
                $("#minimal_pengambilan_div").show();
                $("#potongan").remove();
                $("#potongan_div").append($('<input>', {
                    type: "number",
                    class: "form-control",
                    name: "potongan",
                    id: "potongan",
                    min: 0,
                    max: 100,
                    placeholder: "Masukkan potongan yang diberikan voucher. (%)",
                    required: "required",
                }))
                
                $("#maksimal_pemotongan_div").show();
                $("#maksimal_pemotongan").remove();
                $("#maksimal_pemotongan_div").append($('<input>', {
                    type: "number",
                    class: "form-control",
                    name: "maksimal_pemotongan",
                    id: "maksimal_pemotongan",
                    min: 0,
                    placeholder: "Masukkan maksimal potongan belanjaan yang didapat. (Rp)",
                    required: "required",
                }))
                $("#tanggal_voucher").show();
            }

            if(data == "ongkos_kirim"){
                $("#div_checkbox_categories").hide();

                $("#potongan_div").show();
                $("#minimal_pengambilan_div").show();
                $("#potongan").remove();
                $("#potongan_div").append($('<input>', {
                    type: "number",
                    class: "form-control",
                    name: "potongan",
                    id: "potongan",
                    placeholder: "Masukkan potongan yang diberikan voucher. (Rp)",
                    required: "required",
                }))
                
                $("#maksimal_pemotongan_div").hide();
                $("#tanggal_voucher").show();
            }
        }
    });
});

// $(".checkbox_categories").change(function (data) {
//     console.log($(this).val());
//     $.ajax({
//         type: "GET",
//         dataType: "json",
//         url: "/pilih_target_kategori_voucher",
//         data: { target_kategori_voucher: $(this).val() },
//         success: function (data) {
//             console.log(data)
//         }
//     });
// });