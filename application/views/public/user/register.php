<?php $this->load->view("public/layout/header") ?>

<h3>New User Registration</h3>

<?php echo form_open("user/register") ?>

<hr />

<table class="nostyle">
<tr>
    <td><?php echo form_label("First name", "user_name") ?></td>
    <td width="65%"><?php echo form_input("user_name", element("user_name", $user_details, substr($this->input->post("fullName"), 0, strpos($this->input->post("fullName"), " "))), "class='span3' id='user_name'") ?></td>
</tr>
<tr>
    <td><?php echo form_label("Last name", "user_last_name") ?></td>
    <td><?php echo form_input("user_last_name", element("user_last_name", $user_details, substr($this->input->post("fullName"), strpos($this->input->post("fullName"), " ") + 1)), "class='span3' id='user_last_name'") ?></td>
</tr>
<tr>
    <td><?php echo form_label("Email address", "user_email") ?></td>
    <td><?php echo form_input("user_email", element("user_email", $user_details, NULL), "class='span3' id='user_email'") ?></td>
</tr>
<tr>
    <td><?php echo form_label("Repeat email address", "user_email2") ?></td>
    <td><?php echo form_input("user_email2", $this->input->post("user_email2"), "class='span3' id='user_email2'") ?></td>
</tr>
<tr>
    <td><?php echo form_label("Password", "user_password") ?></td>
    <td><?php echo form_password("user_password", $this->input->post("password"), "class='span3' id='user_password'") ?></td>
</tr>
<tr>
    <td><?php echo form_label("Repeat password", "user_password2") ?></td>
    <td><?php echo form_password("user_password2", NULL, "class='span3' id='user_password2'") ?></td>
</tr>
<tr>
    <td></td>
    <td><?php echo $captcha ?></td>
</tr>
<tr>
	<td><?php echo form_label("Please enter the characters from the image above", "security_words") ?></td>
  <td><?php echo form_input("security_words", NULL, "id='security_words' class='span3'") ?></td>
</tr>

<tr>
  <td></td>
  <td>
    <label>
      <?php echo form_checkbox("agree_terms", 1, $this->input->get("agree_terms"), "id='agree_terms' style='margin-top:0'") ?>
      I agree to the <?php echo $this->config->item("site_name") . " " . anchor("page/terms_and_conditions", "terms & conditions") ?>.
    </label>
  </td>
</tr>

<tr>
  <td></td>
  <td>
    <?php echo form_submit("submit_register", "Register", "class='btn btn-primary' style='margin-top:10px'") ?>
  </td>
</tr>

</table>

<?php echo form_close() ?>

<?php $this->load->view("public/layout/footer") ?>