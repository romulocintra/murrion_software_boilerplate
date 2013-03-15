<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="<?php echo $this->config->item("business", "paypal") ?>">
    <input type="hidden" name="lc" value="IE">
    <input type="hidden" name="tax_rate" value="0">
    <input type="hidden" name="item_name" value="<?php echo $item_name ?>">
    <input type="hidden" name="image_url" value="<?php echo $this->config->item("image_url", "paypal") ?>">
    
    <input type="hidden" name="item_number" value="mid<?php echo $memorials_id; ?>">
    <input type="hidden" name="amount" value="<?php echo $memorial_price; ?>">
    <input type="hidden" name="tax_rate" value="0">
    
    <input type="hidden" name="currency_code" value="EUR">
    <input type="hidden" name="button_subtype" value="services">
    <input type="hidden" name="no_note" value="0">
    <input type="hidden" name="cn" value="Any special instructions to the seller:">
    <input type="hidden" name="no_shipping" value="1">
    <input type="hidden" name="rm" value="1">
    <input type="hidden" name="return" value="<?php echo site_url("payment/paypal_success"); ?>">
    <input type="hidden" name="cancel_return" value="<?php echo site_url("payment/paypal_cancel"); ?>">
    <input type="hidden" name="notify_url" value="<?php echo site_url("payment/paypal_ipn"); ?>">
    <input type="hidden" name="tax_rate" value="0">
    <input type="hidden" name="shipping" value="0.00">
    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynowCC_LG.gif:NonHosted">
    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
