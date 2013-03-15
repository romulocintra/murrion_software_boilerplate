<?php $this->load->view("public/layout/header") ?>

<ul class="breadcrumb">
	<li><?php echo anchor("profile", "My Profile") ?> <span class="divider">/</span></li>
	<li class="active">Edit password</li>
</ul>

<h3>Edit password.</h3>

<?php echo form_open("user/password") ?>

<table class="nostyle">
<tr>
    <td><?php echo form_label("Current password", "current_password") ?></td>
    <td><?php echo form_password("current_password", NULL, "id='current_password'") ?></td>
</tr>
<tr>
    <td><?php echo form_label("New password", "new_password1") ?></td>
    <td><?php echo form_password("new_password1", NULL, "id='new_password1'") ?></td>
</tr>
<tr>
    <td><?php echo form_label("Repeat password", "new_password2") ?></td>
    <td><?php echo form_password("new_password2", NULL, "id='new_password2'") ?></td>
</tr>

<tr>
    <td colspan="2" align="right">
        <p><?php echo form_submit("submit_password", "Change password", "class='btn btn-primary'") ?></p>        
    </td>
</tr>

</table>

<?php echo form_close() ?>

<?php $this->load->view("public/layout/footer") ?>