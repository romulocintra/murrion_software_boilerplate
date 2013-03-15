<?php $this->load->view("public/layout/header") ?>

<?php $this->load->view("public/home/today") ?>

<h3>Recover your <?php echo $this->config->item("site_name") ?> Password.</h3>

<?php echo form_open("user/forgot") ?>

<table class="nostyle">
<tr>
    <td><label for="user_email" class="control-label">Enter your email address: &nbsp;&nbsp;</label></td>
    <td><input type="text" value="<?php echo $this->input->post("user_email") ?>" name="user_email" id="user_email" class="span3" /></td>
    <td>&nbsp;</td>
</tr>

<tr>
  <td></td>
	<td><?php echo $captcha ?></td>
</tr>
<tr>
	<td><?php echo form_label("Enter the characters from the image above: ", "security_words") ?></td>
  <td><?php echo form_input("security_words", NULL, "id='security_words' class='span3'") ?></td>
</tr>

<tr>
	<td>&nbsp;</td>
    <td>
        <p><?php echo form_submit("submit_forgot", "Submit", "class='btn btn-primary'") ?></p>        
    </td>
    <td>&nbsp;</td>
</tr>

</table>

<?php echo form_close() ?>

<p class="alert alert-info">Click on the button above to have a new password sent to you. You will then be able to login with this password and you can then later change it to your own password.</p>

<?php $this->load->view("public/layout/footer") ?>