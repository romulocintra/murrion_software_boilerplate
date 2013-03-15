<?php $this->load->view("public/layout/header") ?>

<?php $this->load->view("public/home/today") ?>

<h3>User Sign In.</h3>

<?php echo form_open("user/login") ?>

<table class="nostyle">
    <tr>
        <td><label for="user_email" class="control-label">Your e-mail address&nbsp;&nbsp;</label></td>
        <td><input type="text" value="<?php echo $this->input->post("user_email") ?>" name="user_email" id="user_email" class="span3" /></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><label for="user_password" class="control-label">Your Password</label></td>
        <td><input type="password" value="" name="user_password" id="user_password" class="span3" /></td>
        <td>&nbsp;&nbsp;<?php echo anchor("user/forgot", "Forgotten your password") ?></td>
    </tr>
    <tr>
        <td colspan="2" align="right">
            <p></p><?php echo form_submit("submit_login", "Sign In", "style='width:150px;' class='btn btn-primary'") ?></p>
        </td>
        <td>&nbsp;</td>
    </tr>

</table>

<?php echo form_close() ?>

<p><?php echo anchor($loginUrl, "Login Using Facebook", "class='btn btn-primary'") ?></p>

<h3>I am a new user</h3>

<br />
<p>
    <?php echo anchor("user/register", "Join For Free", "class='btn btn-primary'") ?>
    &nbsp;&nbsp;&nbsp;
    <?php echo anchor($loginUrl, "Sign up Using Facebook", "class='btn btn-primary'") ?>
</p>

 <p>It only takes 2 minutes to create an account, and its free. With an account you can review advice for free and post your legal issues for quotation</p>

<?php $this->load->view("public/layout/footer") ?>