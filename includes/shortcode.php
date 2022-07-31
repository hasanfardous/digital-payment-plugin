<?php

// Woo Product Table content shortcode
add_action( 'init', 'srpwp_content_shortcode_callback' );

function srpwp_content_shortcode_callback() {
    add_shortcode( 'srpwp', 'srpwp_content_shortcode' );
}
function srpwp_content_shortcode() {
    ob_start();

    // echo '<pre>';
    // print_r($_REQUEST);

    // Plugin default options
    $srpwp_settings_array = get_option('srpwp_settings_datas');

    // echo '<pre>';
    // print_r($srpwp_settings_array);

    $srpwp_publishable_key = isset($srpwp_settings_array['srpwp_publishable_key']) ? sanitize_text_field($srpwp_settings_array['srpwp_publishable_key']) : '';
    $srpwp_secret_key = isset($srpwp_settings_array['srpwp_secret_key']) ? sanitize_text_field($srpwp_settings_array['srpwp_secret_key']) : '';
	?>

    <div class="root digital-payment-gateway-wpplugin">
    <div class="container" style="max-width: 1140px">
      <div class="row">
        <div class="col-12">
          <div class="card border-0 shadow-sm">
            <!-- 🚀 Header -->
            <div
              class="card-header donate-box-header p-5 d-flex flex-column justify-content-center align-items-center row-gap-16">
              <img src="<?php echo STRIPE_PLUGIN_DIR_URL?>assets/images/banner.png" alt="" class="img-fluid img-banner" />
              <h2 class="text-header-primary text-white fw-bold mb-0 text-center">
                Give the Gift of Advocacy
              </h2>
            </div>

            <div class="card-body p-4 p-md-5">
              <h2 class="text-header-secondary mb-4">I want to give</h2>

              <!-- 🚀 Tabs -->
              <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
                <!-- 📌 One Time -->
                <li class="nav-item w-50" role="presentation">
                  <button
                    class="nav-link button-text-primary fw-bold donate-type-button donate-onetime active w-100 p-3 text-color-grey"
                    id="donate-onetime" data-bs-toggle="pill" data-bs-target="#onetime-content" type="button" role="tab"
                    aria-controls="onetime-content" aria-selected="true">
                    One-time
                  </button>
                </li>
                <!-- 📌 Monthly Time -->
                <li class="nav-item w-50" role="presentation">
                  <button
                    class="nav-link button-text-primary fw-bold donate-type-button donate-monthly btn-block w-100 p-3 text-color-grey"
                    id="donate-monthly" data-bs-toggle="pill" data-bs-target="#monthly-content" type="button" role="tab"
                    aria-controls="monthly-content" aria-selected="false">
                    <i class="fa-solid fa-heart text-color-rose mr-3"></i>
                    Monthly
                  </button>
                </li>
              </ul>
              <div class="tab-content mb-4" id="pills-tabContent">
                <!-- 📝 One Time Content-->
                <div class="tab-pane fade show active" id="onetime-content" role="tabpanel"
                  aria-labelledby="donate-onetime">
                  <div class="btn-group donate-amount-group d-flex col-gap-16 row-gap-16" role="group">
                    <input type="radio" class="btn-check" value="50" name="donate-amount-button" id="donate50"
                       />
                    <label
                      class="btn btn-outline-primary w-20 donate-button-label py-2 py-lg-3 px-5 px rounded shadow-sm button-text-secondary border-grey-light text-color-grey"
                      for="donate50" data-donateval="50">$50</label>

                    <input type="radio" class="btn-check" value="100" name="donate-amount-button" id="donate100"
                       />
                    <label
                      class="btn btn-outline-primary donate-button-label py-2 py-lg-3 px-5 rounded shadow-sm button-text-secondary border-grey-light text-color-grey"
                      for="donate100" data-donateval="100">$100</label>

                    <input type="radio" class="btn-check" value="200" name="donate-amount-button" id="donate200"
                       />
                    <label
                      class="btn btn-outline-primary donate-button-label py-2 py-lg-3 px-5 rounded shadow-sm button-text-secondary border-grey-light text-color-grey"
                      for="donate200" data-donateval="200">$200</label>
                    <input type="radio" class="btn-check" value="500" name="donate-amount-button" id="donate500"
                       />
                    <label
                      class="btn btn-outline-primary donate-button-label py-2 py-lg-3 px-5 rounded shadow-sm button-text-secondary border-grey-light text-color-grey"
                      for="donate500" data-donateval="500">
                      $500
                    </label>
                    <div class="input-group other-input-group">
                      <div class="input-group-text px-4" id="donate-ammount-other">
                        <i class="fa-solid fa-dollar-sign button-text-secondary"></i>
                      </div>
                      <input type="number" class="form-control button-text-secondary shadow-sm py-2 py-lg-3"
                        id="onetime-donation-other"
                        value="50"
                        placeholder="Other" aria-label="Donate Other" aria-describedby="donate-ammount-other" />
                    </div>
                  </div>
                </div>
                <!-- 📝 Monthly  -->
                <div class="tab-pane fade" id="monthly-content" role="tabpanel" aria-labelledby="donate-monthly">
                  <div class="btn-group donate-amount-group d-flex col-gap-16 row-gap-16" role="group">
                    <input type="radio" class="btn-check" value="10" name="donate-amount-button" id="donate10m"
                      autocomplete="off" />
                    <label
                      class="btn btn-outline-primary w-20 donate-button-label py-2 py-lg-3 px-5 px rounded shadow-sm button-text-secondary border-grey-light text-color-grey"
                      for="donate10m">$10</label>

                    <input type="radio" class="btn-check" value="25" name="donate-amount-button" id="donate25m"
                      autocomplete="off" />
                    <label
                      class="btn btn-outline-primary donate-button-label py-2 py-lg-3 px-5 rounded shadow-sm button-text-secondary border-grey-light text-color-grey"
                      for="donate25m">$25</label>

                    <input type="radio" class="btn-check" value="50" name="donate-amount-button" id="donate50m"
                      autocomplete="off" />
                    <label
                      class="btn btn-outline-primary donate-button-label py-2 py-lg-3 px-5 rounded shadow-sm button-text-secondary border-grey-light text-color-grey"
                      for="donate50m">$50</label>
                    <input type="radio" class="btn-check" value="100" name="donate-amount-button" id="donate100m"
                      autocomplete="off" />
                    <label
                      class="btn btn-outline-primary donate-button-label py-2 py-lg-3 px-5 rounded shadow-sm button-text-secondary border-grey-light text-color-grey"
                      for="donate100m">
                      $100
                    </label>
                    <div class="input-group other-input-group">
                      <div class="input-group-text px-4" id="donate-ammount-other">
                        <i class="fa-solid fa-dollar-sign button-text-secondary"></i>
                      </div>
                      <input type="number" class="form-control button-text-secondary shadow-sm py-2 py-lg-3"
                        id="monthly-donation-other"
                        placeholder="Other" aria-label="Donate Other" aria-describedby="donate-ammount-other" />
                    </div>
                  </div>
                </div>
              </div>

              <!-- 🚀 Form -->
              <h2 class="text-header-secondary mt-5 mb-4">
                Tell us about yourself
              </h2>
              <form class="row donate-form mb-4">
                <input type="hidden" id="payment_type" value="Onetime">
                <input type="hidden" id="donation_amount" value="50">
                <div class="form-group col-12 col-md-6 mb-3">
                  <label for="first-name" class="form-label d-flex justify-content-between align-items-center">
                    <p class="label mb-0 text-size-primary text-weight-semibold">
                      First Name
                    </p>
                    <p class="help mb-0 text-size-secondary text-color-grey"></p>
                  </label>
                  <input type="text" class="form-control form-control-lg donate-form-field text-size-primary"
                    id="first-name" placeholder="First Name" required />
                </div>

                <div class="form-group col-12 col-md-6 mb-3">
                  <label for="last-name" class="form-label d-flex justify-content-between align-items-center">
                    <p class="label mb-0 text-size-primary text-weight-semibold">
                      Last Name
                    </p>
                    <p class="help mb-0 text-size-secondary text-color-grey"></p>
                  </label>
                  <input type="text" class="form-control form-control-lg donate-form-field text-size-primary"
                    id="last-name" placeholder="Last Name" required />
                </div>

                <div class="form-group col-12 col-md-6 mb-3">
                  <label for="email" class="form-label d-flex justify-content-between align-items-center">
                    <p class="label mb-0 text-size-primary text-weight-semibold">
                      Email
                    </p>
                    <p class="help mb-0 text-size-secondary text-color-grey">
                      for your donation receipt
                    </p>
                  </label>
                  <input type="email" class="form-control form-control-lg donate-form-field text-size-primary"
                    id="email" placeholder="Email" required />
                </div>

                <div class="form-group col-12 col-md-6 mb-3">
                  <label for="tel" class="form-label d-flex justify-content-between align-items-center">
                    <p class="label mb-0 text-size-primary text-weight-semibold">
                      Phone Number
                    </p>
                    <p class="help mb-0 text-size-secondary text-color-grey">
                      (optional)
                    </p>
                  </label>
                  <input type="tel" class="form-control form-control-lg donate-form-field text-size-primary" id="phone"
                    placeholder="Phone Number"/>
                </div>

                <div class="form-group col-12 col-md-6 mb-3">
                  <label for="text" class="form-label d-flex justify-content-between align-items-center">
                    <p class="label mb-0 text-size-primary text-weight-semibold">
                      Address
                    </p>
                    <p class="help mb-0 text-size-secondary text-color-grey">
                      to share how we are making an impact
                    </p>
                  </label>
                  <input type="text" class="form-control form-control-lg donate-form-field text-size-primary"
                    id="address" placeholder="Address" required />
                </div>

                <div class="form-group col-12 col-md-6 mb-3">
                  <label for="text" class="form-label d-flex justify-content-between align-items-center">
                    <p class="label mb-0 text-size-primary text-weight-semibold">
                      Address 2
                    </p>
                    <p class="help mb-0 text-size-secondary text-color-grey">
                      (optional)
                    </p>
                  </label>
                  <input type="text" class="form-control form-control-lg donate-form-field text-size-primary"
                    id="address2" placeholder="Address 2"/>
                </div>

                <div class="form-group col-12 col-md-6 mb-3">
                  <label for="text" class="form-label d-flex justify-content-between align-items-center">
                    <p class="label mb-0 text-size-primary text-weight-semibold">
                      City
                    </p>
                    <p class="help mb-0 text-size-secondary text-color-grey"></p>
                  </label>
                  <input type="text" class="form-control form-control-lg donate-form-field text-size-primary" id="city"
                    placeholder="City" required />
                </div>

                <div class="form-group col-12 col-md-3 mb-3">
                  <label for="text" class="form-label d-flex justify-content-between align-items-center">
                    <p class="label mb-0 text-size-primary text-weight-semibold">
                      State
                    </p>
                    <p class="help mb-0 text-size-secondary text-color-grey"></p>
                  </label>
                  <input type="text" class="form-control form-control-lg donate-form-field text-size-primary" id="state"
                    placeholder="State" required />
                </div>

                <div class="form-group col-12 col-md-3">
                  <label for="zip" class="form-label d-flex justify-content-between align-items-center">
                    <p class="label mb-0 text-size-primary text-weight-semibold">
                      Zip
                    </p>
                    <p class="help mb-0 text-size-secondary text-color-grey"></p>
                  </label>
                  <input type="number" class="form-control form-control-lg donate-form-field text-size-primary" id="zip"
                    placeholder="Zip" required />
                </div>

                <!-- 🚀 Payment Plan -->
                <h2 class="text-header-secondary mt-2 mb-4">
                  Select your payment method
                </h2>
                <div class="btn-group donate-amount-group d-flex col-gap-16 row-gap-16" role="group">
                  <input type="radio" class="btn-check" name="payment-method-button" id="paypal-method" autocomplete="off" value="Paypal"
                    checked />
                  <label
                    class="btn btn-outline-primary py-2 py-lg-3 px-5 px rounded shadow-sm border-grey-light text-color-grey"
                    for="paypal-method">
                    <i class="fa-brands fa-paypal"></i>
                    Paypal</label>

                  <input type="radio" class="btn-check" name="payment-method-button" id="stripe-method"
                    autocomplete="off" value="Stripe" />
                  <label
                    class="btn btn-outline-primary py-2 py-lg-3 px-5 rounded shadow-sm border-grey-light text-color-grey"
                    for="stripe-method">
                    <i class="fa-brands fa-stripe-s fw-light"></i>
                    Stripe</label>
                </div>

                <!-- <div class="form-check mt-4">
                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                  <label class="form-check-label text-size-primary text-weight-semibold" for="flexCheckDefault">
                    My Billing and Contact addresses are the same
                  </label>
                </div> -->

                <div id="paymentResponse" class="hidden"></div>

                <div class="row three-in-one payment-method">
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
                </div>

                <div class="donate-form-submit d-flex justify-content-center mt-4">
                  <button type="submit"
                    class="btn btn-primary donate-submit-button btn-lg btn-block text-size-primary text-weight-semibold py-3 px-5"
                    id="donate-submit">
                    Review Donation
                  </button>
                </div>
              </form>
              <div class="donate-form-submit-form-after d-flex justify-content-center mt-4" style="margin-top:-1.5rem !important">
                
              </div>
            </div>
            <div class="card-footer"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
	<?php
	$form_html = ob_get_clean();
	return $form_html;
}