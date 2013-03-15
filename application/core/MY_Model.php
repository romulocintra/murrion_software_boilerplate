<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {
	// the prefix of each MySQL column name. For example "driver_" or "vehicle_".
	var $column_prefix = "";
	// the main table name that has the data
	var $table = "";
	// the name of the integer autonumeric primary key column
	var $primary_key = "";
	// whether to fully delete the records or to mark them as "deleted"
	var $fully_delete = FALSE;
	// whether the rows have a "created_date" field or not. It will set this value upon insert if so.
	var $has_created_date = TRUE;
	// the colum or columns to use as "name" of the row. It can be one column of many if you want to indicate name + surname or something custom.
	var $name_columns = "";

	/**
		Get_item_list
		Returns a list of values from the DB table specified.
		
		The point of this function is to have all the possible filtering at the same place.
		This has advantages like controlling possible user restrictions, "marker as deleted" rows, etc.
		at the same place so one change in the permissions logic would affect just to one piece of code.		
		
		@param array $parameters -> A variable list of optional parameters
		
		Explanation of the possible parameters:
		
		[limit] => (int) The number of rows to return. Useful for pagination
		[offset] => (int) The start row. Useful for pagination and only used with a "limit" value.
		
		[output] => (string) One of the most important parameters.
		
		It determines the way the data will be returned.
		
		Possible values:
		
		- "count" => It does all the needed filtering and it will return and integer with the number of rows.
		- "assoc" => It returns an associative array that can be used in dropdown menus, checkboxes, 
			radio buttons and multi select boxes. It associates the primary key with a customizable name
		- Empty => If the parameter is left empty, it will return a normal multi-dimensional array with each row.
		
		--- Parameters for the "assoc" output : ----
		
		[output_default] => (array) Used with the "assoc" output type. It can be used to set the first value of the associative array,
			it's useful to set the 'default' value of the dropdowns.
		
		--- Parameters for the default list output: ---
		
		[order] => (string) The colum to use to order the list. If left empty it will order by primary key DESC.
		[order_type] => (string) "ASC/DESC". It's used with the "order" parameter and it determines the type of order you want to use.
			If you use the "order" parameter and you leave the "order_type" parameter empty, it will use "DESC" by default.
		
	*/
	function get_item_list($parameters=array())
	{
		$this->db->from($this->table);
		
		if (!$this->fully_delete && !element("include_deleted", $parameters))
		{
			$this->db->where($this->column_prefix."_deleted", 0);
		}

		if (element("where_parameters", $parameters))
		{
			$where_method = $parameters["where_parameters"];
			
			$this->$where_method($parameters);
		}
		else if (isset($this->where_method))
		{
			$where = $this->where_method;
			$this->$where($parameters);
		}

		if (is_array(element("joins", $parameters)))
		{
			foreach ($parameters["joins"] as $join_table => $join_parameters)
			{
				$on = !is_array($join_parameters) ? $join_parameters : $join_parameters["on"];
				$type = !is_array($join_parameters) ? NULL : $join_parameters["type"];
				$this->db->join($join_table, $on, $type);
			}
		}

		if (element("limit", $parameters, 0) > 0)
		{
			$this->db->limit((int) $parameters["limit"], (int) element("offset", $parameters, 0));
		}

		if (element("output", $parameters) == "count")
		{
			return $this->db->count_all_results();
		}
		else if (element("output", $parameters) == "assoc")
		{			
			$this->db->select($this->table . "." . $this->primary_key);
			
			if (is_array($this->name_columns))
			{
				$this->db->select(implode(",", $this->name_columns));
			}
			else
			{
				$this->db->select($this->name_columns);
			}
			
			if (element("custom_order", $parameters))
			{
				// dont do anything
			}
			if (element("order", $parameters))
			{
				$this->db->order_by($parameters["order"], element("order_type", $parameters, "DESC"));
			}
			else if (is_array($this->name_columns))
			{
				$this->db->order_by(implode(",", $this->name_columns));
			}
			else
			{
				$this->db->order_by($this->name_columns);
			}

			$result = $this->db->get()->result_array();
	
			$output_arr = element("output_default", $parameters, array());

			foreach ($result as $row)
			{
				if (!is_array($this->name_columns))
				{
					$value = $row[$this->name_columns];
				}
				else
				{
					$value = array();
					
					foreach ($this->name_columns as $column)
					{
						$value[] = $row[$column];
					}
					
					$value = implode(" ", $value);
				}
				
				$output_arr[$row[$this->primary_key]] = $value;
			}
			return $output_arr;
		}
		else if (element("output", $parameters) == "ids")
		{
			$this->db->select($this->table . "." . $this->primary_key);

			$result = $this->db->get()->result_array();
	
			$output_arr = element("output_default", $parameters, array());

			foreach ($result as $row)
			{
				$output_arr[] = (int) $row[$this->primary_key];
			}
			return $output_arr;
		}
		else if (element("output", $parameters) && method_exists($this, "_output_".element("output", $parameters)))
		{
			$method_name = "_output_".$parameters["output"];
			return $this->$method_name($parameters);
		}
		else
		{
			return $this->_default_output($parameters);
		} // output type
	}
	
	function _default_output($parameters=array())
	{
		$this->db->select($this->table.".*");

		if (element("extra_columns", $parameters))
		{
			$this->db->select(implode(",", $parameters["extra_columns"]));
		}

		if (!element("order", $parameters))
		{
			$parameters["order"] = $this->table.".".$this->primary_key;
		}
		$this->db->order_by($parameters["order"], element("order_type", $parameters, "DESC"));

		return $this->db->get()->result_array();
	}
	
	/**
		Function to store a row into the DB.
		
		The point of this function is to have at the same place the edit and the creation, 
		thus restrictions applied to updates would affect to inserts as well making code changes
		easier.
		
		@param $data array -> The row data to store on the DB. It's an associative array with each colum value.
		@return -> It return the primary key ID in case of success and FALSE if no success. It's useful to retrieve the 
			generated auto increment primary key after the creation.
		
		Note:
		
		If in the $data array it's present the ID value, it will update that record, otherwise it will create a new row
	*/
	function store_item($data)
	{
		if (isset($data[$this->primary_key]))
		{
			if ($this->db->update($this->table, $data, array($this->primary_key => (int) $data[$this->primary_key])))
			{				
				return $data[$this->primary_key];
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			if ($this->has_created_date)
			{
				$data[$this->column_prefix."_created_date"] = my_now();
			}

			if ($this->db->insert($this->table, $data))
			{
				return (int) $this->db->insert_id();
			}
			else
			{
				return FALSE;
			}
		}
	}
	/**
		Deletes a row from the DB
		
		The point of this function is to control the restrictions of deletions in one place.
		It's useful for example, if you want to just "mark as deleted" instead of fully deleting records.
		
		It's also important if some kind of user permission logic is necessary because you can control it here.
		
		@param $item_id (int) -> The ID of the value you want to delete
		@return (int) -> The number of affected rows. If 1, success, if 0, nothing was deleted.		
	*/
	function delete_item($item_id)
	{
		$this->db->where($this->primary_key, $item_id);

		if ($this->fully_delete)
		{
			$this->db->delete($this->table);
		}
		else
		{
			$this->db->set($this->column_prefix."_deleted", 1);
			$this->db->update($this->table);
		}

		return $this->db->affected_rows();
	}
	/**
		Function to get a particular record from the DB.
		
		It will be likely got from the primary key but it can be customized.
		
		@param $parameters (array) -> A flexible array of parameters to filter. Explanations below:
		
		[primary_key_column_name] => (The name of the primary key column, like "driver_id" or "vehicle_id") (int)
			This is the primary key filtering. This is the most common parameter for this function.
		
		[header_sql_done] => This is to avoid the call to select() and from() methods in case the Child Class has its own 
			custom code. This is very common in case you need to do an SQL join, extract extra fields , etc.
			
		@return array() -> An associative array with the values or and empty array if no data found.
	*/
	function get_item($parameters)
	{
		if (!element("header_sql_done", $parameters))
		{
			$this->db->select($this->table.".*");
		}
		$this->db->from($this->table);

		if (!$this->fully_delete && !element("include_deleted", $parameters))
		{
			$this->db->where($this->column_prefix."_deleted", 0);
		}
		
		if (is_numeric($parameters))
		{
			$this->db->where($this->primary_key, $parameters);
		}
		else if (isset($this->where_method))
		{
			$where = $this->where_method;
			
			$this->$where($parameters);
		}
		else
		{
			$this->db->where($this->primary_key, 0); // fix to avoid complexity
		}

		return $this->db->get()->row_array();
	}
	
	protected function _list_fields_from_table($field_name, $parameters, $extra=array())
	{
		$join_with_assignments = FALSE;
			
		$select_field = element("alias", $extra) ? $field_name." AS ".$extra["alias"] : $field_name;

		$select_field .= element("key", $extra) ? ", ".$extra["key"] : "";

		$this->db->select($select_field, FALSE);
		$this->db->distinct();
		$this->db->from($this->table);
			
		if (element("join_table", $extra) && element("join_criteria", $extra))
		{
			$this->db->join($extra["join_table"], $extra["join_criteria"]);
		}

		$not = isset($extra["where_not"]) ? $extra["where_not"] : "";

		if ($not === NULL)
		{
			$this->db->where($field_name." IS NOT NULL", NULL);
		}
		else
		{
			$this->db->where($field_name." !=", $not);
		}

		if (isset($this->where_method))
		{
			$where = $this->where_method;			
			$this->$where($parameters);
		}

		$this->db->order_by($field_name);

		$result = $this->db->get()->result_array();
			
		if (element("output", $parameters) == "result_array")
		{
			return $result;
		}
		else
		{
			$output_arr = element("output_default", $parameters, array());

			if (element("key", $extra))
			{
				if (strpos($extra["key"], ".") !== FALSE)
				{
					$field_index = substr($extra["key"], strpos($extra["key"], ".") + 1);
				}
				else
				{
					$field_index = $extra["key"];
				}
			}
			else if (element("alias", $extra))
			{
				$field_index = $extra["alias"];
			}
			else if (strpos($field_name, ".") !== FALSE)
			{
				$field_index = substr($field_name, strpos($field_name, ".")+1);
			}
			else
			{
				$field_index = $field_name;
			}

			if (element("alias", $extra))
			{
				$field_value_index = element("alias", $extra);
			}
			else
			{
				$field_value_index = $field_index;
			}
			
			foreach ($result as $row)
			{
				if (element("format", $extra) == "date")
				{
					$value = date("d/m/Y", strtotime($row[$field_value_index]));
				}
				else if (element("format", $extra) == "capitalize")
				{
					$value = ucwords(mb_strtolower($row[$field_value_index]));
				}
				else
				{
					$value = $row[$field_value_index];
				}
				
				if (!$row[$field_index])
				{
					// empty values give problems with the default empty value
					continue;
				}

				$output_arr[$row[$field_index]] = $value;
			}
			return $output_arr;
		}
	}
}
