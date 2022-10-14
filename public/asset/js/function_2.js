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