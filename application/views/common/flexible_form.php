<?php
$item_details = element("details", $form_properties);
$item_details = $$item_details;

if (!element("hide_heading", $form_properties))
{
	if (element($form_properties["id"], $item_details))
	{
		echo "<h2>Edit ".$form_properties["name"]."</h2>";
	}
	else
	{
		echo "<h2>Add new ".$form_properties["name"]."</h2>";
	}
}
?>

<?php
if (element("multipart", $form_properties))
{
	echo form_open_multipart($form_properties["save_method"]."/".element($form_properties["id"], $item_details), element("form_attrs", $form_properties, NULL));
}
else
{
	echo form_open($form_properties["save_method"]."/".element($form_properties["id"], $item_details), element("form_attrs", $form_properties, NULL));
}
?>

<table style="width:auto" class="bordered-table">
<?php
$fields = $form_properties["fields"];

foreach ($fields as $key => $properties)
{
	if ($properties == "espace")
	{
		echo "<tr>";
		echo "<td colspan='2'>&nbsp;</td>";
		echo "</tr>";
		continue;
	}
	
	if (element("type", $properties) == "heading")
	{
		echo "<tr>";
		echo "<th colspan='2'>" . $properties["name"] . "</th>";
		echo "</tr>";
		continue;
	}
	
	if (element("type", $properties) == "fixed_value")
	{
		continue;
	}
	
	echo "<tr>";
	
	if (element("type", $properties) == "county_list")
	{
		echo "<th>".form_label("Country", $key)."</th>";
	}
	else
	{
		echo "<th>".form_label($properties["name"], $key)."</th>";
	}

	echo "<td>";
	
	if (element("custom_field", $properties))
	{
		$field = $$properties["custom_field"];

		echo $field;
	}
	else if (element("type", $properties) == "date")
	{
		echo form_input(array(
			"name" => $key,
			"id" => $key,
			"class" => "span2 datepick",
			"value" => isset($item_details[$key]) ? my_format_date($item_details[$key], FALSE, "d/m/Y") : NULL
		));
	}
	else if (element("type", $properties) == "datetime")
	{
		$date_value = isset($item_details[$key]) ? $item_details[$key] : element("default", $properties, NULL);
		
		echo form_input(array(
			"name" => $key,
			"id" => $key,
			"class" => "span2 datepick",
			"value" => $date_value ? my_format_date($date_value, FALSE, "d/m/Y") : NULL
		));
		
		$time_value = substr($date_value, strrpos($date_value, " ")+1);
		
		$hour = $time_value ? substr($time_value, 0, 2) : NULL;
		$minute = $time_value ? substr($time_value, 3, 2) : NULL;
		
		echo " ";
		echo form_dropdown($key."_hour", get_hour_dropdown(), $hour, "style='width:auto'");
		echo ":";
		echo form_dropdown($key."_minute", get_minute_dropdown(), $minute, "style='width:auto'");
		
	}
	else if (element("dropdown", $properties))
	{
		$dropdown = $properties["dropdown"];
		$value = element($key, $item_details);

		if (element("type", $properties) == "int")
		{
			$value = (int) $value;
		}

		echo form_dropdown($key, $$dropdown, $value, "class='".element("class", $properties, "span5")."'");
	}
	else if (element("type", $properties) == "password")
	{
		echo form_password($key, NULL, "class='span5' id='".$key."'");
	}
	else if (element("type", $properties) == "upload")
	{
		echo form_upload($key, NULL, "class=\"span4\" style=\"margin: 10px 0px;\"");

		if (element($key, $item_details))
		{
			echo "<br />Existing file: <br />";
			echo anchor(base_url().element($key, $item_details))."<br />";
			echo "Uploading a new file would replace this one";
		}
		
	}
	else if (element("type", $properties) == "textarea")
	{
		echo form_textarea(array(
			"name" => $key,
			"id" => $key,
			"class" => "span8",
			"value" => element($key, $item_details),
			"cols" => 9,
		));
	}
	else if (element("type", $properties) == "tinymce")
	{
		echo form_textarea(array(
			"name" => $key,
			"id" => $key,
			//"class" => "span8",
			"value" => element($key, $item_details),
			"class" => "tinymce",
			"cols" => 70,
			"rows" => 20
		));
	}
	else if (element("type", $properties) == "basic_tinymce")
	{
		echo form_textarea(array(
			"name" => $key,
			"id" => $key,
			//"class" => "span8",
			"value" => element($key, $item_details),
			"class" => "basic_tinymce",
			"cols" => 70,
			"rows" => 20
		));
	}
	else if (element("type", $properties) == "checkbox")
	{
		echo form_checkbox(array(
			"name" => $key,
			"id" => $key,
			//"class" => "span8",
			"value" => 1,
			"checked" => (bool) element($key, $item_details),
		));
	}
	else if (element("type", $properties) == "int")
	{
		echo form_input(array(
			"name" => $key,
			"id" => $key,
			"class" => "span1",
			"value" => element($key, $item_details, element("default", $properties)),
		));
	}
	else if (element("type", $properties) == "yesno" || element("type", $properties) == "yesnona")
	{
		if (element("type", $properties) == "yesnona")
		{
			$default = 2;
			
			$list = array(
				2 => "N/A",
				0 => "No",
				1 => "Yes",
			);
		}
		else
		{
			$default = 0;
			
			$list = array(
				0 => "No",
				1 => "Yes",
			);
		}
		
		echo form_dropdown($key, $list, element($key, $item_details, element("default", $properties, $default)), "class='span2'");
	}
	else if (element("type", $properties) == "county_list")
	{
		$dropdown_values = $this->area_model->get_county_dropdown(array(
			"field_name" => $key,
			"county_id" => element($key, $item_details, element("default", $properties)),
			"country_classname" => "span5",
			"classname" => "span5",
			"separated" => TRUE
		));
		
		echo $dropdown_values["country"];

		echo "</td>";	
		echo "</tr>";

		echo "<tr>";
		echo "<th>".form_label($properties["name"], $key)."</th>";
		echo "<td>";
		
		echo $dropdown_values["county"];
	}
	else
	{
		echo form_input(array(
			"name" => $key,
			"id" => $key,
			"class" => element("class", $properties, "span5"),
			"value" => element($key, $item_details, element("default", $properties)),
		));
	}
	
	if (element("tip", $properties))
	{
		echo "<p class='help-block'>".element("tip", $properties)."</p>";
	}
	
	echo "</td>";	
	echo "</tr>";
}
?>
</table>

<?php if (!isset($form_properties["close_form"]) || $form_properties["close_form"]) : ?>
	<?php
	$button_title = element("submit_title", $form_properties) ? 
		$form_properties["submit_title"] : 
		element("button", $form_properties, "Save ".$form_properties["name"]);
	?>

	<p><?php echo form_submit($form_properties["submit"], $button_title, "class='btn btn-primary btn-large'") ?></p>
	<?php echo form_close() ?>
<?php endif ?>