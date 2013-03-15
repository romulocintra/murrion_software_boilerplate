<?php echo form_open("payment/remote_realex", array("class" => "realex_form", "name" => "ccform")) ?>

<?php echo form_hidden("product_id", $product_id) ?>
<?php echo form_hidden("price", $price) ?>
<input type="hidden" name="PAYMENTBUTTON" value="false">

<table cellspacing="1" cellpadding="1" border="0" class="realex_table" style="<?php if(!$admin) echo "float:left;" ?>margin-left:auto;margin-right:auto;">
<tbody>
	<tr>
        <td class="cctd">
        	<div align="right">Payment:&nbsp;</div>
        </td>
        <td class="cctd">
        	<?php echo number_format($price, 0) ?> 
		</td>
	</tr>
	<tr> 
        <td class="cctd"> 
        	<div align="right">Card Type:&nbsp;</div>
        </td>
        <td class="cctd">&nbsp; 
        	
        	<?php echo form_dropdown("pas_cctype", realex_credit_cards(), element("pas_cctype", $values), 'class="ccinput eleven" onchange="checkLaserCard();"') ?>
        </td>
    </tr>
    
    <tr> 
	    <td class="cctd"> 
	        <div align="right">Card Number:&nbsp;</div>
	    </td>
	    <td class="cctd">&nbsp; 
	        <input maxlength="19" size="24" name="pas_ccnum" value="<?php echo element("pas_ccnum", $values) ?>" class="ccinput eleven" autocomplete="off">
	    </td>
   </tr>

                    <!--cvc-->
    <tr> 
        <td class="cctd"> 
            <div align="right">Security Code:&nbsp; <br>
            	<font size="1"><span style="display: none;" id="optional_cvn">(Optional)</span></font>
			</div>
        </td>
        <td class="cctd"> &nbsp; 
            <input maxlength="4" size="3" name="pas_cccvc" value="<?php echo element("pas_cccvc", $values) ?>" class="ccinput three" id="pas_cccvcInput">
             <span style="font-size:12px" >(<a href="javascript:showCVN();">About security code</a>)</span>
            <input type="hidden" value="1" name="pas_cccvcind" class="ccinput four">
        </td>
    </tr>
                    
    <tr> 
        <td class="cctd"> 
            <div align="right">Expiry Date:&nbsp;</div>
        </td>
        <td class="cctd"> &nbsp; 
        	<?php echo form_dropdown("pas_ccmonth", realex_expiry_month(), element("pas_ccmonth", $values), 'class="ccinput five"') ?>
        	<?php echo form_dropdown("pas_ccyear", realex_expiry_year(), element("pas_ccyear", $values), 'class="ccinput five"') ?>
     	</td>
    </tr>

    <tr> 
        <td class="cctd"> 
            <div align="right">Cardholder Name:&nbsp;</div>
        </td>
        <td class="cctd"> &nbsp; 
            <input name="pas_ccname" value="<?php echo element("pas_ccname", $values) ?>" class="ccinput eleven">
        </td>
    </tr>
                    
    <tr> 
        <td class="cctd"> 
            &nbsp;
        </td>
        <td class="cctd"> &nbsp; <br>
            <input type="submit" name="pay" value=" Pay Now " id="button" class="large awesome submit" onclick="return validate();">
        </td>
    </tr>
 
</tbody></table>

<?php echo form_close() ?>