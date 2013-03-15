<?php $this->load->view("public/layout/header") ?>


<?php $this->load->view("public/home/today", array("cms_key" => "user_register")) ?>

<div style="margin-left:400px">

<h4>Register as a new user</h4>

<p>If you have already registered for <?php echo $this->config->item("site_name") ?>, <a href="javascript:;" onclick="$('#login-dropdown-form').show()">click here to login</a></p>

<?php echo form_open("user/register") ?>

<table class="nostyle">
<tr>
    <td><?php echo form_label("First name", "user_name") ?></td>
    <td width="65%"><?php echo form_input("user_name", NULL, "class='span3' id='user_name'") ?></td>
</tr>
<tr>
    <td><?php echo form_label("Last name", "user_last_name") ?></td>
    <td><?php echo form_input("user_last_name", NULL, "class='span3' id='user_last_name'") ?></td>
</tr>
<tr>
    <td><?php echo form_label("Email address", "user_email") ?></td>
    <td><?php echo form_input("user_email", NULL, "class='span3' id='user_email'") ?></td>
</tr>
<tr>
    <td><?php echo form_label("Repeat email address", "user_email2") ?></td>
    <td><?php echo form_input("user_email2", NULL, "class='span3' id='user_email2'") ?></td>
</tr>
<tr>
    <td><?php echo form_label("Contact telephone", "user_phone") ?></td>
    <td><?php echo form_input("user_phone", NULL, "class='span3' id='user_phone'") ?></td>
</tr>
<tr>
    <td><?php echo form_label("Country", "user_country") ?></td>
    <td><?php echo $county_dropdown["country"] ?></td>
</tr>
<tr>
    <td><?php echo form_label("County", "user_county") ?></td>
    <td><?php echo $county_dropdown["county"] ?></td>
</tr>
<tr>
    <td><?php echo form_label("Town/City", "user_town") ?></td>
    <td><?php echo form_input("user_town", NULL, "class='span3' id='user_town'") ?></td>
</tr>
<tr>
    <td><?php echo form_label("Postcode", "user_postcode") ?></td>
    <td><?php echo form_input("user_postcode", NULL, "class='span3' id='user_postcode'") ?></td>
</tr>
<tr>
    <td><?php echo form_label("Password", "user_password") ?></td>
    <td><?php echo form_password("user_password", NULL, "class='span3' id='user_password' autocomplete='off'") ?></td>
</tr>
<tr>
    <td><?php echo form_label("Repeat password", "user_password2") ?></td>
    <td><?php echo form_password("user_password2", NULL, "class='span3' id='user_password2' autocomplete='off'") ?></td>
</tr>
<tr>
    <td><?php echo form_label("Security question", "user_security_question") ?></td>
    <td><?php echo form_dropdown("user_security_question", get_question_list(), NULL, "class='span3' id='user_security_question'") ?></td>
</tr>
<tr>
    <td><?php echo form_label("Security question answer", "user_security_answer") ?></td>
    <td><?php echo form_input("user_security_answer", NULL, "class='span3' id='user_security_answer'") ?></td>
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
    <label>
      <?php echo form_checkbox("user_email_updates", 1, FALSE, "id='user_email_updates'") ?>
      Receive offers and updates from <?php echo $this->config->item("site_name") ?>
    </label>
	</td>
</tr>

<tr>
  <td></td>
  <td>
    <?php echo form_submit("submit_register", "Join For Free", "class='btn btn-primary' style='margin-top:10px'") ?>
  </td>
</tr>

</table>

<?php echo form_close() ?>

</div>

<?php $this->load->view("public/layout/footer") ?>