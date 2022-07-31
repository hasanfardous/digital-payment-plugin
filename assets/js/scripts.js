(function ($) {
    $(document).ready(function () {
        // Paypal
        const userDetailForm = $("form#srpwp-donation-form");
        const paypalPaymentForm = $("form.srpwp-paypal-payment-form");
        $('#srpwp_first_name').focusout(function () {
            console.log($(this).val());
            paypalPaymentForm.find('#item_name').val($(this).val() + ' Donation');
            paypalPaymentForm.find('#amount').val(userDetailForm.find('#srpwp_amount_val').val());
            paypalPaymentForm.find('input[name="first_name"]').val($(this).val());
        });
        $('#srpwp_last_name').focusout(function () {
            console.log($(this).val());
            paypalPaymentForm.find('input[name="last_name"]').val($(this).val());
        });
        $('#srpwp_email').focusout(function () {
            console.log($(this).val());
            paypalPaymentForm.find('input[name="email"]').val($(this).val());
        });


        paypalPaymentForm.find('input[name=cmd]').val('_xclick');
        // let srpwp_payment_type = 'onetime';
        $(':radio[name="srpwp_payment_type"]').change(function () {
            var srpwp_payment_type = $(this).filter(':checked').val();
            // var srpwp_amount_val = $("#srpwp_amount_val").val();
            console.log(srpwp_payment_type);
            if (srpwp_payment_type == 'srpwp_onetype') {
                srpwp_payment_type = 'onetime';
                $("#srpwp_amount_val").val(200);
                // paypalPaymentForm.find('input[name=cmd]').val('_xclick');
                paypalPaymentForm.find('input[name="custom"]').val(srpwp_payment_type);
            } else {
                srpwp_payment_type = 'monthly';
                $("#srpwp_amount_val").val(50);
                // paypalPaymentForm.find('input[name=cmd]').val('_xclick-subscriptions');
                paypalPaymentForm.find('input[name="custom"]').val(srpwp_payment_type);
            }
        });

        // New markup
        $('.digital-payment-gateway-wpplugin button#donate-onetime').on('click', function () {
            // console.log('Onetime');
            $('.digital-payment-gateway-wpplugin .card-body form.donate-form input#payment_type').val('Onetime');
        });
        $('.digital-payment-gateway-wpplugin button#donate-monthly').on('click', function () {
            // console.log('Monthly');
            $('.digital-payment-gateway-wpplugin .card-body form.donate-form input#payment_type').val('Monthly');
        });
        $('.digital-payment-gateway-wpplugin .tab-content input#onetime-donation-other, .digital-payment-gateway-wpplugin .tab-content input#monthly-donation-other').focusin(function () {
            var srpwp_payment_type = $('.digital-payment-gateway-wpplugin .tab-content :radio[name="donate-amount-button"]');
            console.log(srpwp_payment_type.prop('checked', false));

        });
        $('.digital-payment-gateway-wpplugin .tab-content input#onetime-donation-other, .digital-payment-gateway-wpplugin .tab-content input#monthly-donation-other').focusout(function () {
            // console.log($(this).val());
            $('.digital-payment-gateway-wpplugin .card-body form.donate-form input#donation_amount').val($(this).val());

        });
        $('.digital-payment-gateway-wpplugin .tab-content :radio[name="donate-amount-button"]').change(function () {
            var srpwp_payment_type = $(this).filter(':checked').val();
            // console.log(srpwp_payment_type);
            $('.digital-payment-gateway-wpplugin .card-body form.donate-form input#donation_amount').val(srpwp_payment_type);
            $('.digital-payment-gateway-wpplugin .tab-content input#onetime-donation-other, .digital-payment-gateway-wpplugin .tab-content input#monthly-donation-other').val('');
        });


        // Select subscription form element
        const subscrFrm = document.querySelector(".digital-payment-gateway-wpplugin .card-body form.donate-form");

        // Attach an event handler to subscription form
        subscrFrm.addEventListener("submit", handleSubscrSubmit);

        const stripe = Stripe(srpwp_admin_datas.srpwp_publishable_key);
        let elements = stripe.elements();
        var style = {
            base: {
                lineHeight: "30px",
                fontSize: "16px",
                border: "1px solid #ced4da",
            }
        };
        let cardElement = elements.create('card', { style: style });
        cardElement.mount('#card-element');

        cardElement.on('change', function (event) {
            displayError(event);
        });

        function displayError(event) {
            if (event.error) {
                showMessage(event.error.message);
            }
        }

        // Get API Key
        // let STRIPE_PUBLISHABLE_KEY = document.currentScript.getAttribute(srpwp-publishable-key);
        // console.log(srpwp_admin_datas.srpwp_plugin_dir_url);
        // Create an instance of the Stripe object and set your publishable API key
        // const stripe = Stripe(srpwp_admin_datas.srpwp_publishable_key);

        // let elements = stripe.elements();
        // var style = {
        //     base: {
        //         lineHeight: "30px",
        //         fontSize: "16px",
        //         border: "1px solid #ced4da",
        //     }
        // };
        // let cardElement = elements.create('card', { style: style });
        // cardElement.mount('#card-element');

        // cardElement.on('change', function (event) {
        //     displayError(event);
        // });

        // let srpwp_payment_type = document.querySelector("input[name='srpwp_payment_type']:checked").value;
        // let srpwp_amount_val = document.getElementById("srpwp_amount_val").value;
        // srpwp_payment_type.on('change', function () {
        //     if (srpwp_payment_type == 'srpwp_onetype') {
        //         srpwp_amount_val.value = '200';
        //     } else {
        //         srpwp_amount_val.value = '50';
        //     }
        // });

        // let srpwp_payment_type = 'onetime';
        $(':radio[name="srpwp_payment_type"]').change(function () {
            var srpwp_payment_type = $(this).filter(':checked').val();
            // var srpwp_amount_val = $("#srpwp_amount_val").val();
            console.log(srpwp_payment_type);
            if (srpwp_payment_type == 'srpwp_onetype') {
                srpwp_payment_type = 'onetime';
                $("#srpwp_amount_val").val(200);
            } else {
                srpwp_payment_type = 'monthly';
                $("#srpwp_amount_val").val(50);
            }
        });

        // Select payment method show payment button
        var strpFormEl = $('.digital-payment-gateway-wpplugin .card-body form.donate-form');


        strpFormEl.find(':radio[name="payment-method-button"]').change(function () {
            let strpFormPaymentMethod = $(this).filter(':checked').val();
            // console.log(strpFormPaymentMethod)
            var strpFormPaymentType = strpFormEl.find('input#payment_type').val();
            var strpFormDonationAmount = strpFormEl.find('input#donation_amount').val();
            var strpFormFirstName = strpFormEl.find('input#first-name').val();
            var strpFormLastName = strpFormEl.find('input#last-name').val();
            var strpFormEmail = strpFormEl.find('input#email').val();
            var strpFormPhone = strpFormEl.find('input#phone').val();
            var strpFormAddress = strpFormEl.find('input#address').val();
            var strpFormAddress2 = strpFormEl.find('input#address2').val();
            var strpFormCity = strpFormEl.find('input#city').val();
            var strpFormState = strpFormEl.find('input#state').val();
            var strpFormZip = strpFormEl.find('input#zip').val();

            let data = {
                action: 'srpwp_admin_datas',
                // nonce: nonce,
                srpwp_payment_method: strpFormPaymentMethod,
                srpwp_payment_type: strpFormPaymentType,
                srpwp_amount_val: strpFormDonationAmount,
                customer_name: strpFormFirstName + ' ' + strpFormLastName,
                customer_email: strpFormEmail,
                srpwp_phone_number: strpFormPhone,
                srpwp_address: strpFormAddress,
                srpwp_address2: strpFormAddress2,
                srpwp_city: strpFormCity,
                srpwp_state: strpFormState,
                srpwp_zip: strpFormZip,
            };

            // console.log(strpFormPaymentType, strpFormDonationAmount, strpFormFirstName, strpFormLastName, strpFormEmail, strpFormPhone, strpFormAddress, strpFormAddress2, strpFormCity, strpFormState, strpFormZip, strpFormPaymentMethod);
            if (strpFormPaymentMethod == 'Paypal') {
                $('.three-in-one.payment-method').hide();
                $.ajax({
                    dataType: 'json',
                    type: 'post',
                    url: srpwp_admin_datas.ajax_url,
                    data: data,
                    beforeSend: function (response) {
                        // $('.woocommerce').addClass('adding-to-cart');
                        console.log('before send');
                        strpFormEl.find('.donate-form-submit button').hide();
                        let afterFormEl = $('.digital-payment-gateway-wpplugin .card-body');
                        let loadingEl = `<div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>`;
                        afterFormEl.find('.donate-form-submit-form-after').html(loadingEl);
                    },
                    complete: function (response) {
                        // $('.woocommerce').removeClass('adding-to-cart');
                        console.log('completed');
                    },
                    success: function (response) {
                        console.log(response);
                        let afterFormEl = $('.digital-payment-gateway-wpplugin .card-body');
                        afterFormEl.find('.donate-form-submit-form-after').html(response);
                    }
                })
            } else if (strpFormPaymentMethod == 'Stripe') {
                // Empty the paypal button content
                let afterFormEl = $('.digital-payment-gateway-wpplugin .card-body');
                afterFormEl.find('.donate-form-submit-form-after').empty();

                // Display the stripe payment method
                $('.three-in-one.payment-method').show();
                strpFormEl.find('.donate-form-submit button').hide();
                let loadingEl =
                    `<div class="row three-in-one payment-method">
                    <div class="form-group col-md-12 mb-3">
                      <label for="srpwp_credit_card_number" class="form-label d-flex justify-content-between align-items-center">Credit Card Number</label>
                      <div id="card-element"></div>
                      <p class="payment-info"><i class="fa-solid fa-lock"></i> We use the highest level of PCI certification and 256-bit encryption to keep your payment information safe.</p>
                    </div>
    
                    <div class="form-group col-md-12 mb-3 text-center">
                      <button name="payment_submit" type="submit" class="btn btn-primary donate-submit-button btn-lg btn-block text-size-primary text-weight-semibold py-3 px-5" id="donate-submit">
                        Donate Now
                      </button>
                    </div>
                    </div>`;
                // afterFormEl.find('.donate-form-submit-form-after').html(loadingEl);

                // $.ajax({
                //     dataType: 'json',
                //     type: 'post',
                //     url: srpwp_admin_datas.ajax_url,
                //     data: data,
                //     beforeSend: function (response) {
                //         console.log('before send');
                //     },
                //     complete: function (response) {
                //         console.log('completed');
                //     },
                //     success: function (response) {
                //         console.log(response);
                //         // let afterFormEl = $('.digital-payment-gateway-wpplugin .card-body');
                //         // afterFormEl.find('.donate-form-submit-form-after').html(response);

                //         // Create payment method and confirm payment intent.
                //         stripe.confirmCardPayment(response.datas.clientSecret, {
                //             payment_method: {
                //                 card: cardElement,
                //                 billing_details: {
                //                     name: strpFormFirstName + ' ' + strpFormLastName,
                //                 },
                //             }
                //         }).then((result) => {
                //             if (result.error) {
                //                 showMessage(result.error.message);

                //                 // setProcessing(false);
                //                 // setLoading(false);
                //             } else {
                //                 // subscrFrm.reset();
                //                 // cardElement.clear();
                //                 alert('Thank you so much for your donation, we just received it!');
                //             }
                //         });
                //     }
                // });
            }
        });

        // Form submission handling
        function handleSubscrSubmit(e) {
            e.preventDefault();
            // setLoading(true);     
            var strpFormPaymentType = strpFormEl.find('input#payment_type').val();
            var strpFormDonationAmount = strpFormEl.find('input#donation_amount').val();
            var strpFormFirstName = strpFormEl.find('input#first-name').val();
            var strpFormLastName = strpFormEl.find('input#last-name').val();
            var strpFormEmail = strpFormEl.find('input#email').val();
            var strpFormPhone = strpFormEl.find('input#phone').val();
            var strpFormAddress = strpFormEl.find('input#address').val();
            var strpFormAddress2 = strpFormEl.find('input#address2').val();
            var strpFormCity = strpFormEl.find('input#city').val();
            var strpFormState = strpFormEl.find('input#state').val();
            var strpFormZip = strpFormEl.find('input#zip').val();
            let strpFormPaymentMethod = strpFormEl.find(':radio[name="payment-method-button"]').filter(':checked').val();

            let data = {
                action: 'srpwp_admin_datas',
                // nonce: nonce,
                srpwp_payment_method: strpFormPaymentMethod,
                srpwp_payment_type: strpFormPaymentType,
                srpwp_amount_val: strpFormDonationAmount,
                customer_name: strpFormFirstName + ' ' + strpFormLastName,
                customer_email: strpFormEmail,
                srpwp_phone_number: strpFormPhone,
                srpwp_address: strpFormAddress,
                srpwp_address2: strpFormAddress2,
                srpwp_city: strpFormCity,
                srpwp_state: strpFormState,
                srpwp_zip: strpFormZip,
            };

            if (strpFormPaymentMethod == 'Paypal') {

                // console.log(data);

                // console.log(strpFormPaymentType, strpFormDonationAmount, strpFormFirstName, strpFormLastName, strpFormEmail, strpFormPhone, strpFormAddress, strpFormAddress2, strpFormCity, strpFormState, strpFormZip, strpFormPaymentMethod);
                $.ajax({
                    dataType: 'json',
                    type: 'post',
                    url: srpwp_admin_datas.ajax_url,
                    data: data,
                    beforeSend: function (response) {
                        // $('.woocommerce').addClass('adding-to-cart');
                        console.log('before send');
                        strpFormEl.find('.donate-form-submit button').hide();
                        let afterFormEl = $('.digital-payment-gateway-wpplugin .card-body');
                        let loadingEl = `<div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>`;
                        afterFormEl.find('.donate-form-submit-form-after').html(loadingEl);
                    },
                    complete: function (response) {
                        // $('.woocommerce').removeClass('adding-to-cart');
                        console.log('completed');
                    },
                    success: function (response) {
                        console.log(response);
                        let afterFormEl = $('.digital-payment-gateway-wpplugin .card-body');
                        afterFormEl.find('.donate-form-submit-form-after').html(response);
                    }
                })

                // console.log('Paypal monthly');

            } else {


                // Check the payment type
                if (strpFormPaymentType == 'Onetime') {
                    console.log('Stripe onetime');
                } else {
                    console.log('Stripe monthly');
                }

                $.ajax({
                    dataType: 'json',
                    type: 'post',
                    url: srpwp_admin_datas.ajax_url,
                    data: data,
                    beforeSend: function (response) {
                        $('.three-in-one.payment-method').hide();
                        let loadingEl = `<div class="spinner-border" role="status">
                            <span class="visually-hidden" style="margin: 0 auto;display:inherit;">Loading...</span>
                        </div>`;
                        $(loadingEl).insertBefore('.donate-form-submit-form-after');
                    },
                    complete: function (response) {
                        console.log('completed');
                    },
                    success: function (response) {
                        console.log(response);

                        // let afterFormEl = $('.digital-payment-gateway-wpplugin .card-body');
                        // afterFormEl.find('.donate-form-submit-form-after').html(response);

                        // Create payment method and confirm payment intent.
                        stripe.confirmCardPayment(response.datas.clientSecret, {
                            payment_method: {
                                card: cardElement,
                                billing_details: {
                                    name: strpFormFirstName + ' ' + strpFormLastName,
                                },
                            }
                        }).then((result) => {
                            console.log(result);
                            if (result.error) {
                                // showMessage(result.error.message);

                                strpFormEl.reset();
                                cardElement.clear();
                                // setProcessing(false);
                                // setLoading(false);
                            } else {
                                strpFormEl.trigger('reset');
                                cardElement.clear();
                                $('.spinner-border').hide();
                                showMessage('Thank you so much for your donation, we just received it!');
                            }
                        });
                    }
                });

            }


            // console.log(strpFormPaymentType, strpFormDonationAmount, strpFormFirstName, strpFormLastName, strpFormEmail, strpFormPhone, strpFormAddress, strpFormAddress2, strpFormCity, strpFormState, strpFormZip, strpFormPaymentMethod);

            // let subscrPaymentType = document.querySelector("input[name='srpwp_payment_type']:checked").value;
            // let srpwp_amount_val = document.getElementById("srpwp_amount_val").value;
            // let srpwp_first_name = document.getElementById("srpwp_first_name").value;
            // let srpwp_last_name = document.getElementById("srpwp_last_name").value;
            // let customer_email = document.getElementById("srpwp_email").value;
            // let srpwp_phone_number = document.getElementById("srpwp_phone_number").value;
            // let srpwp_address = document.getElementById("srpwp_address").value;
            // let srpwp_address2 = document.getElementById("srpwp_address2").value;
            // let srpwp_city = document.getElementById("srpwp_city").value;
            // let srpwp_state = document.getElementById("srpwp_state").value;

            // console.log(subscrPaymentType, srpwp_amount_val, subscr_plan_id, customer_name, customer_email);
            // var data = {
            //     action: 'srpwp_admin_datas',
            //     // nonce: nonce,
            //     srpwp_payment_type: subscrPaymentType == 'srpwp_onetype' ? 'onetime' : 'month',
            //     srpwp_amount_val: srpwp_amount_val,
            //     customer_name: srpwp_first_name + ' ' + srpwp_last_name,
            //     customer_email: customer_email,
            //     srpwp_phone_number: srpwp_phone_number,
            //     srpwp_address: srpwp_address,
            //     srpwp_address2: srpwp_address2,
            //     srpwp_city: srpwp_city,
            //     srpwp_state: srpwp_state,
            // };
            // console.log(data);
            // $.ajax({
            //     dataType: 'json',
            //     type: 'post',
            //     url: srpwp_admin_datas.ajax_url,
            //     data: data,
            //     beforeSend: function (response) {
            //         // $('.woocommerce').addClass('adding-to-cart');
            //         console.log('before send');
            //     },
            //     complete: function (response) {
            //         // $('.woocommerce').removeClass('adding-to-cart');
            //         console.log('completed');
            //     },
            //     success: function (response) {
            //         console.log(response);

            //         // Create payment method and confirm payment intent.
            //         stripe.confirmCardPayment(response.datas.clientSecret, {
            //             payment_method: {
            //                 card: cardElement,
            //                 billing_details: {
            //                     name: srpwp_first_name + ' ' + srpwp_last_name,
            //                 },
            //             }
            //         }).then((result) => {
            //             if (result.error) {
            //                 showMessage(result.error.message);

            //                 setProcessing(false);
            //                 setLoading(false);
            //             } else {
            //                 subscrFrm.reset();
            //                 cardElement.clear();
            //                 alert('Thank you so much for your donation, we just received it!');
            //             }
            //         });
            //         // paymentProcess(response.subscriptionId, response.clientSecret, response.customerId);
            //         // response.fragments;
            //         // if (response.error & response.product_url) {
            //         //     window.location = response.product_url;
            //         //     return;
            //         // } else {
            //         // $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
            //         // }
            //     },
            // })
            // .then(
            //     $.ajax({
            //         dataType: 'json',
            //         type: 'post',
            //         url: srpwp_admin_datas.ajax_url,
            //         data: data,
            //         beforeSend: function (response) {
            //             // $('.woocommerce').addClass('adding-to-cart');
            //             console.log('before send');
            //         },
            //         complete: function (response) {
            //             // $('.woocommerce').removeClass('adding-to-cart');
            //             console.log('completed');
            //         },
            //         success: function (response) {
            //             console.log(response);
            //         }
            //     })
            // );
        }

        function paymentProcess(subscriptionId, clientSecret, customerId) {
            // setProcessing(true);
            $.ajax({
                dataType: 'json',
                type: 'post',
                url: srpwp_admin_datas.ajax_url,
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
                    console.log(response);
                }
            })
        }

        // Display message
        function showMessage(messageText) {
            const messageContainer = document.querySelector("#paymentResponse");

            messageContainer.classList.remove("hidden");
            messageContainer.textContent = messageText;

            setTimeout(function () {
                messageContainer.classList.add("hidden");
                messageText.textContent = "";
            }, 15000);
        }

        // Show a spinner on payment submission
        function setLoading(isLoading) {
            if (isLoading) {
                // Disable the button and show a spinner
                document.querySelector("#srpwp-donate-now").disabled = true;
                // document.querySelector("#spinner").classList.remove("hidden");
                // document.querySelector("#buttonText").classList.add("hidden");
            } else {
                // Enable the button and hide spinner
                document.querySelector("#srpwp-donate-now").disabled = false;
                // document.querySelector("#spinner").classList.add("hidden");
                // document.querySelector("#buttonText").classList.remove("hidden");
            }
        }

        // Show a spinner on payment form processing
        function setProcessing(isProcessing) {
            if (isProcessing) {
                subscrFrm.classList.add("hidden");
                document.querySelector("#frmProcess").classList.remove("hidden");
            } else {
                subscrFrm.classList.remove("hidden");
                document.querySelector("#frmProcess").classList.add("hidden");
            }
        }

    });
})(jQuery);