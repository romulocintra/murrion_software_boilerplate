<br />

<h1 class="page-title"><?php echo $this->lang->line('realex_response_success_header'); ?></h1>

<p align="center">

<table class="formTable">
    <tr>
        <td colspan="2"><div class="subheader"><?php echo $this->lang->line('realex_response_success_subheader'); ?></div></td>
    </tr>
    <tr>
        <td class="fieldLabel"><?php echo $this->lang->line('realex_response_order_id_label'); ?>:</td>
        <td class="fieldData"><?php echo $order_id; ?></td>
    </tr>
    <tr>
        <td class="fieldLabel"><?php echo $this->lang->line('realex_response_amount_label'); ?>:</td>
        <td class="fieldData"><?php echo $amount / 100; ?></td>
    </tr>

    <?php if (!empty($comment1)): ?>
        <tr>
            <td class="fieldLabel"><?php echo $this->lang->line('realex_response_comment1_label'); ?>:</td>
            <td class="fieldData"><?php echo $comment1; ?></td>
        </tr>
    <?php endif; ?>
    <?php if (!empty($comment2)): ?>
        <tr>
            <td class="fieldLabel"><?php echo $this->lang->line('realex_response_comment2_label'); ?>:</td>
            <td class="fieldData"><?php echo $comment2; ?></td>
        </tr>
    <?php endif; ?>
    <?php if (!empty($customer_num)): ?>
        <tr>
            <td class="fieldLabel"><?php echo $this->lang->line('realex_response_customer_num_label'); ?>:</td>
            <td class="fieldData"><?php echo $customer_num; ?></td>
        </tr>
    <?php endif; ?>
    <?php if (!empty($product_id)): ?>
        <tr>
            <td class="fieldLabel"><?php echo $this->lang->line('realex_response_product_id_label'); ?>:</td>
            <td class="fieldData"><?php echo $product_id; ?></td>
        </tr>
    <?php endif; ?>
</table>

<h3>Thank you for your payment.</h3>
