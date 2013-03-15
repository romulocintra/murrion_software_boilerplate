            <div id="header"><?php echo $this->lang->line( 'realex_response_failure_header' );?></div>
            <p>
                <?php echo $this->lang->line( 'realex_response_failure_description' );?>: <br />
                <span class="warning"><strong><?php echo $failure_message ?></strong></span><br />
                <br />
			</p>
			<?php if( $error == 'PAYMENT_FAILURE'): ?>
			<table>
				<tr>
				<td colspan="2"><div class="subheader"><?php echo $this->lang->line( 'realex_response_failure_subheader' );?></div></td>
				</tr>
				<tr>
				<td class="fieldLabel"><?php echo $this->lang->line( 'realex_response_order_id_label' );?>
				<td class="fieldData"><?php if (isset($order_id)) echo $order_id ?></td>
				</tr>
				<tr>
				<td class="fieldLabel"><?php echo $this->lang->line( 'realex_response_amount_label' );?>:</td>
				<td class="fieldData"><?php if (isset($amount)) echo $amount ?></td>
				</tr>
			</table>
			<?php endif; ?>
