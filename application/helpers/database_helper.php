<?php
function update_or_insert($table, $primary, $where, $insert)
{
	$CI = &get_instance();

	foreach ($where as $field => $value)
	{
		$CI->db->where($field, $value);
	}
	$exists = $CI->db->get($table)->row_array();
	
	if($exists)
	{
		$CI->db->where($primary, element($primary, $exists))
				 ->update($table, $insert);

		return element($primary, $exists);
	}
	else
	{
		$CI->db->insert($table, $insert);

		return $CI->db->insert_id();
	}
}