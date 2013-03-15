<?php $this->load->view("public/layout/header") ?>

<?php if ($nav == "public/profile/nav") : ?>
    <ul class="breadcrumb">
        <li><?php echo anchor("profile", "My Profile") ?> <span class="divider">/</span></li>
        <li class="active">Edit account details</li>
    </ul>
<?php else : ?>
	<?php $this->load->view($nav) ?>
<?php endif ?>

<h3>Edit account details.</h3>

<?php
if (element("user_type", $user_details) == "provider")
{
    $sections = array(
        "geo" => "Jurisdictions & geography",
        "alert" => "Lead alerts",
        "interest" => "Areas of interest",
        "user" => "User Details"
    );

    echo '<ul class="nav nav-tabs" id="toggle_tab_list" style="clear:both">';

    foreach ($sections as $key => $name)
    {
        echo '<li style="float:right" ' . ($active == $key ? "class='active'" : "" ) .
        '><a class="tab_toggle" id="toggle_' . $key . '_div" href="javascript:;">' . $name . '</a></li>';
    }

    echo "</ul>";
	
	if (!isset($error) && $is_firm_admin && !$this->firm_model->is_firm_paid($firm_id))
	{
		echo "<div class='alert alert-error'>The account hasn't been paid yet. You can't do modifications on your account details until we receive the payment.</div>";
	}
}
?>

<?php $this->load->view("public/user/edit_user/user_details") ?>

<?php if (element("user_type", $user_details) == "provider") : ?>
    <input type="hidden" id="firm_id" name="firm_id" value="<?php echo $firm_id ?>" />
    <input type="hidden" id="user_id" name="user_id" value="<?php echo element("user_id", $user_details) ?>" />

    <?php echo form_hidden("is_provider", 1) ?>

    <?php $this->load->view("public/user/edit_user/areas_of_interest") ?>
    <?php $this->load->view("public/user/edit_user/lead_alerts") ?>

    <?php
    if ($is_firm_admin)
    {
        $this->load->view("public/user/edit_user/jurisdiction_admin");
    }
    else
    {
        $this->load->view("public/user/edit_user/jurisdiction_user");
    }
    ?>
<?php endif ?>

<?php if (element("user_type", $user_details) == "provider" && $is_firm_admin && !$this->firm_model->is_firm_paid($firm_id)) : ?>
	<p><?php echo form_submit("", "Save button disabled", "class='btn btn-primary btn-large' disabled='disabled'") ?></p>
<?php else : ?>
	<p><?php echo form_submit($form_properties["submit"], element("button", $form_properties, "Save " . $form_properties["name"]), "class='btn btn-primary btn-large' id='submit_change_user'") ?></p>
<?php endif ?>

<?php echo form_close() ?>

<?php $this->load->view("public/layout/footer") ?>