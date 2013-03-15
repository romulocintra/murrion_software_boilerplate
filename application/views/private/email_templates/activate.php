<?php $this->load->view("private/email_templates/email_header") ?>

<p>Hi,</p>
<p>Thank you for registering with <?php echo $this->config->item("site_name") ?>. Your account has been created. In order to verify the account, please click on the link below (or copy and paste the URL into your browser): <br />
<?php echo anchor("user/activate/". $user_details["user_activation_token"]) ?></p>
<p>If you did not setup such an account, please forward this e-mail to <?php echo $this->config->item("info_email") ?>.</p>

<?php $this->load->view("private/email_templates/email_footer") ?>
