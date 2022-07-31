(function ($) {
    $(document).ready(function () {
        $('#tabs').tabs();

        function srpwp_show_admin_notice(message, type) {
            let html = `
                <div class='srpwp_show_admin_notice notice notice-${type} is-dismissible'>
                    <p>${message}</p>
                </div>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
            `;
            return html;
        }

        $('form.srpwp-feature-request-form').submit(function (e) {
            e.preventDefault();
            var srpwp_publishable_key = $('input[name="srpwp_publishable_key"]').val();
            var srpwp_secret_key = $('input[name="srpwp_secret_key"]').val();

            console.log(srpwp_publishable_key, srpwp_secret_key);

            var data = {
                action: 'srpwp_admin_panel_datas',
                // nonce: nonce,
                srpwp_publishable_key: srpwp_publishable_key,
                srpwp_secret_key: srpwp_secret_key,
            };
            console.log(data);
            $.ajax({
                dataType: 'json',
                type: 'post',
                url: srpwp_admin_panel_datas.ajax_url,
                data: data,
                beforeSend: function (response) {
                    // $('.woocommerce').addClass('adding-to-cart');
                    console.log('before send');
                },
                complete: function (response) {
                    // $('.woocommerce').removeClass('adding-to-cart');
                    console.log('completed');
                },
                success: function (response) {
                    if (response.status == 'success') {
                        $('.srpwp-admin-wrapper').find('h1').after(srpwp_show_admin_notice(response.message, 'success'));
                    }
                },
            });
        });
    });
})(jQuery);

// Cat hover come from bottom
// point 3 config the products