<form action="<?php echo PAYPAL_URL; ?>" class="srpwp-paypal-payment-form" method="post"> 
    <input type="hidden" name="cmd" value="<?php echo $srpwp_payment_type == 'Onetime' ? '_xclick' : '_xclick-subscriptions';?>"> 
    <input type="hidden" name="business" value="sb-lllhk12940024@business.example.com"> 
    <input type="hidden" id="item_name" name="item_name" value="<?php echo $customer_name.' Donations'?>"> 
    <input type="hidden" name="item_number" value="1"> 
    <input type="hidden" name="quantity" value="1"> 
    <input type="hidden" name="currency_code" value="USD">
    <?php if ($srpwp_payment_type == 'Monthly') { ?>
        <input type="hidden" name="a3" id="item_price" value="<?php echo $srpwp_amount_val!=''?$srpwp_amount_val:'50'?>">
        <input type="hidden" name="p3" id="interval_count" value="12">
        <input type="hidden" name="t3" id="interval" value="M">
    <?php } else { ?>
    <input type="hidden" id="amount" name="amount" value="<?php echo $srpwp_amount_val!=''?$srpwp_amount_val:'50'?>"> 
    <?php } ?>
    <input type="hidden" name="rm" value="2">
    <input type="hidden" name="notify_url" value="<?php echo PAYPAL_NOTIFY_URL?>">
    <input type="hidden" name="return" value="<?php echo PAYPAL_NOTIFY_URL?>">
    <input type="hidden" name="first_name" value="John">
    <input type="hidden" name="last_name" value="Doe">
    <input type="hidden" name="email" value="youremail@gmail.com">
    <input type="hidden" name="custom" value="Onetime">
    <button name="payment_submit" type="submit" class="btn btn-primary donate-submit-button btn-lg btn-block text-size-primary text-weight-semibold py-3 px-5" id="donate-submit">
        Donate Now
    </button>
</form>