<?php $this->load->view("private/email_templates/email_header") ?>

<p>Hi,</p>
<p><?php echo $this->config->item("site_name") ?> has reset your account password, your temporary password is:<br />
<?php echo $new_password ?> (Please note that this password is case sensitive)</p>
<p>Once logged in you can go <?php echo anchor("profile") ?> to change your password.</p>

<?php $this->load->view("private/email_templates/email_footer") ?>