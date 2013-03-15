<?php 
if (element("item_id", $item_details))
{
	page_heading("Edit ".$type_name);
}
else
{
	page_heading("Add ".$type_name);
}
?>

<?php
if (isset($message))
{
    echo "<div class='alert-message success'>" . $message . "</div>";
}
else if ($this->session->flashdata('message'))
{
    echo "<div class='alert-message success'>" . $this->session->flashdata('message') . "</div>";
}
else if (isset($error))
{
    echo "<div class='alert-message error'>" . $error . "</div>";
}
?>

<?php echo form_open($method.element("item_id", $item_details)) ?>

<table>
<tr>
	<th>Name: </th>
	<td><?php echo form_input("item_name", element("item_name", $item_details), "class='span5' id='item_name'") ?></td>
</tr>
<?php if (isset($description) && $description) : ?>
	<tr>
		<th><?php echo form_label($description, "item_description") ?>: </th>
		<td><?php echo form_textarea(array(
			"name" => "item_description",
			"value" => element("item_description", $item_details),
			"class" =>"span5",
			"id" => "item_description"
		)) ?></td>
	</tr>
<?php endif ?>
<?php if (isset($extra_text) && $extra_text) : ?>
	<tr>
		<th><?php echo form_label($extra_text, "item_text1") ?>: </th>
		<td><?php echo form_textarea(array(
			"name" => "item_text1",
			"value" => element("item_text1", $item_details),
			"class" =>"span5",
			"id" => "item_text1"
		)) ?></td>
	</tr>
<?php endif ?>
<?php if (isset($check) && $check) : ?>
	<tr>
		<th><?php echo form_label($check, "item_check") ?>: </th>
		<td><?php echo form_checkbox(array(
			"name" => "item_check",
			"value" => 1,
			"checked" => element("item_check", $item_details, FALSE),
			"id" => "item_check"
		)) ?></td>
	</tr>
<?php endif ?>
<?php if (isset($number) && $number) : ?>
	<tr>
		<th><?php echo form_label($number, "item_num") ?>: </th>
		<td><?php echo form_input(array(
			"name" => "item_num",
			"value" => element("item_num", $item_details, 0),
			"id" => "item_num",
			"class" => "span2"
		)) ?></td>
	</tr>
<?php endif ?>
</table>

<p><?php echo form_submit("submit_item", "Save ".$type_name, "class='btn primary large'") ?></p>

<?php echo form_close() ?>

<?php $this->load->view('footer') ?>