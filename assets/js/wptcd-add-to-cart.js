(function ($) {
    $(document).ready(function () {
        $(document).on('click', '.wptcd-add-to-cart', function (e) {
            e.preventDefault();
            console.log('add to cart js');
            $thisbutton = $(this),
                // $form = $thisbutton.closest('form.cart'),
                // id = $thisbutton.data('product-id'),
                // nonce = $thisbutton.parent().find("#_wpnonce").val(),
                product_qty = $thisbutton.prev('.wptcd-product-quantity').val() || 1,
                product_id = $thisbutton.prev().data('product-id'),
                variation_id = $thisbutton.prev().data('variation-id') || 0;

            console.log(product_qty, product_id, variation_id);

            var data = {
                action: 'wptcd_ajax_datas',
                // nonce: nonce,
                product_id: product_id,
                product_sku: '',
                quantity: product_qty,
                variation_id: variation_id,
            };
            $.ajax({
                dataType: 'json',
                type: 'post',
                url: wptcd_ajax_datas.ajax_url,
                data: data,
                beforeSend: function (response) {
                    $('.woocommerce').addClass('adding-to-cart');
                    console.log('before send');
                },
                complete: function (response) {
                    $('.woocommerce').removeClass('adding-to-cart');
                    console.log('completed');
                },
                success: function (response) {
                    console.log(response);
                    // response.fragments;
                    // if (response.error & response.product_url) {
                    //     window.location = response.product_url;
                    //     return;
                    // } else {
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                    // }
                },
            });
        });
    });
})(jQuery);