<?php $this->load->view("private/email_templates/email_header") ?>

<p>Hi, </p>
<p><?php echo $this->config->item("site_name") ?> received a request to reset the password for your account <?php echo element("user_email", $user_details) ?></p>
<p>If you want to reset your password, click on the link below (or copy and paste the URL into your browser) and we will send you a temporary password.<br />
<?php echo $reset_password_link ?></p>
<p>If you don't want to reset your password, please ignore this message. Your password will not be reset. If you have any concerns, please contact us.</p>

<?php $this->load->view("private/email_templates/email_footer") ?>
