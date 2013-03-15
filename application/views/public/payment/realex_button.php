<?php
$this->realex_payments->set_field('form_name', 'realex_form')
        ->set_field('form_id', 'realex_id')
        ->set_field('currency', 'EUR')
        ->set_button_delimiters('<div>', '</div>');
// set specific order details
$this->realex_payments->set_field('customer_num', 'cust123')
        ->set_field('product_id', 'PRO-1234');

echo $this->realex_payments->return_form($item_id, $price_to_pay * 100);
?>