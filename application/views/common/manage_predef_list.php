<?php page_heading($type_name . " List"); ?>

<?php 
if (isset($message))
{
	echo "<div class='alert-message success'>" . $message . "</div>";
}
else if ($this->session->flashdata('error'))
{
	echo "<div class='alert-message error'>" . $this->session->flashdata('error') . "</div>";
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

<p><?php echo anchor($new_method, "Create ".$type_name, "class='btn primary large'") ?></p>

<table class="zebra-striped bordered-table">
<tr>
	<th>Name</th>
	<?php if (isset($description) && $description) : ?>
		<th><?php echo $description ?></th>
	<?php endif ?>
	<?php if (isset($extra_text) && $extra_text) : ?>
		<th><?php echo $extra_text ?></th>
	<?php endif ?>
	<?php if (isset($check) && $check) : ?>
		<th><?php echo $check ?></th>
	<?php endif ?>
	<?php if (isset($number) && $number) : ?>
		<th><?php echo $number ?></th>
	<?php endif ?>
	<th>Date Created</th>
	<th>&nbsp;</th>
</tr>

<?php if ($item_list) : ?>
	<?php foreach ($item_list as $item) : ?>
	<tr>
		<td><?php echo $item["item_name"] ?></td>		
		<?php if (isset($description) && $description) : ?>
			<td><?php echo $item["item_description"] ?></td>
		<?php endif ?>
		<?php if (isset($extra_text) && $extra_text) : ?>
			<td><?php echo $item["item_text1"] ?></td>
		<?php endif ?>
		<?php if (isset($check) && $check) : ?>
			<td><?php echo $item["item_check"] ? "Yes" : "No" ?></td>
		<?php endif ?>
		<?php if (isset($number) && $number) : ?>
			<td><?php echo $item["item_num"] ?></td>
		<?php endif ?>
		<td><?php echo my_format_date($item["item_created_date"]) ?></td>
		<td>
		<?php echo anchor($edit_method . $item['item_id'], img('images/icons/edit.gif', FALSE, 'Edit'), "class='btn' title='Edit'") ?>
		<?php echo anchor($delete_method . $item['item_id'], img('images/icons/delete.gif', FALSE, 'Delete'), 'class="confirm btn" title="delete this '.$type_name.'"') ?>
		</td>
	</tr>
	<?php endforeach ?>
<?php else : ?>
	<tr>
		<td colspan="21">No item found</td>
	</tr>
<?php endif ?>
</table>

<?php $this->load->view('footer') ?>