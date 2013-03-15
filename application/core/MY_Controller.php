<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	function save_item($entity_id, $parameters=array(), &$error)
	{
		if (!$error)
		{
			$error = NULL;
		}
		
		$details_index = $parameters["details"];
		$model = $parameters["model"];
		$model = $this->$model;
		
		if ($this->input->post($parameters["submit"]))
		{
			$fields = $parameters["fields"];
			
			$details = array();
			
			$upload_fields = array();

			foreach ($fields as $field_name => $field_properties)
			{				
				if (element("type", $field_properties) != "upload" && element("rules", $field_properties))
				{
					$this->form_validation->set_rules($field_name, element("name", $field_properties, $field_name), $field_properties["rules"]);
				}
				
				if (element("type", $field_properties) == "fixed_value")
				{
					$details[$field_name] = $field_properties["value"];
				}
				else if (element("type", $field_properties) == "date")
				{
					$details[$field_name] = $this->input->post($field_name) ? normal_date_to_mysql($this->input->post($field_name)) : NULL;
				}
				else if (element("type", $field_properties) == "int")
				{
					if (element("null", $field_properties))
					{
						$value = $this->input->post($field_name) ? (int) $this->input->post($field_name) : NULL;
					}
					else
					{
						$value = (int) $this->input->post($field_name);
					}
					
					$details[$field_name] = $value;
				}
				else if (element("type", $field_properties) == "password")
				{
					$details[$field_name] = md5($this->input->post($field_name));
				}
				else if (element("type", $field_properties) == "upload")
				{
					$upload_fields[$field_name] = $field_properties;
				}
				else
				{
					$details[$field_name] = $this->input->post($field_name);
				}
				
				if (element("process_url", $field_properties))
				{
					if ($details[$field_name] && stripos($details[$field_name], "http") !== 0)
					{
						$details[$field_name] = "http://" . $details[$field_name];
					}
				}
				
				if (element("ignore_if_empty", $field_properties) && isset($details[$field_name]) && !$this->input->post($field_name))
				{
					unset($details[$field_name]);
				}
			}

			if ($error)
			{
				if ($entity_id)
				{
					$details[$parameters["id"]] = $entity_id;
				}
			}
			else if ($this->form_validation->run())
			{
				if ($entity_id)
				{
					$details[$parameters["id"]] = $entity_id;
				}
				
				$store_method = "store_".$parameters["type"];
				
				foreach ($upload_fields as $field_name => $field_properties)
				{
					if (!element("required", $field_properties) && (!isset($_FILES[$field_name]) || !$_FILES[$field_name]["name"]))
					{
						continue;
					}

					$upload_folder = rtrim(element("upload_folder", $field_properties, "uploads"), DIRECTORY_SEPARATOR);
					
					// create folders if needed
					$config['upload_path'] = my_absolute_path($upload_folder, NULL, DIRECTORY_SEPARATOR, TRUE, "folder");
					
					$config["allowed_types"] = $field_properties["allowed_types"];
					
					if (!element("encrypt_filename", $field_properties))
					{					
						$filename = substr($_FILES[$field_name]["name"], 0, strrpos($_FILES[$field_name]["name"], "."));
						$ext = strtolower(substr($_FILES[$field_name]["name"], strrpos($_FILES[$field_name]["name"], ".") + 1));
						$config["file_name"] = url_title($filename, "underscore", TRUE) . "." . $ext;
					}
					else
					{
						$config["encrypt_name"] = TRUE;
					}
		
					$this->load->library('upload');
					$this->upload->initialize($config);
					
					if (!$this->upload->do_upload($field_name))
					{
						$error = $this->upload->display_errors();
					}
					else
					{
						$upload_data = $this->upload->data();
						
						if (element("resize_to", $field_properties))
						{
							$img_path = $upload_data["full_path"];
							
							$resize = $field_properties["resize_to"];
							
							$config = array();
							$config['image_library'] = 'gd2';
							$config['source_image'] = $img_path;
							$config['create_thumb'] = TRUE;
							$config['maintain_ratio'] = TRUE;
							$config['width'] = $resize["width"];
							$config['height'] = $resize["height"];

							$this->load->library('image_lib');
							
							$this->image_lib->initialize($config);
				
							$this->image_lib->resize();
				
							$thumb_file = substr($img_path, 0, strlen($img_path) - 4) . "_thumb" . substr($img_path, strlen($img_path) - 4);
							
							@unlink($img_path);
							rename($thumb_file, $img_path);
						}
						
						$details[$field_name] = str_replace(DIRECTORY_SEPARATOR, "/", $upload_folder)."/".$upload_data["file_name"];
					}
				}
				
				if (!$error)
				{				
					$return = $model->$store_method($details);
					
					if (isset($parameters["callback_after"]))
					{
						if (is_callable(array($this, $parameters["callback_after"])))
						{
							call_user_func(array($this, $parameters["callback_after"]), $return);
						}
					}
					else
					{
						if ($return)
						{
							if (!isset($parameters["dont_redirect"]))
							{
								if (isset($parameters["flash_message"]))
								{
									$this->session->set_flashdata("message", substr($parameters["flash_message"], 0, 250));
								}
								else
								{
									$this->session->set_flashdata("message", $parameters["name"]." saved");
								}
	
								redirect($parameters["redirect_to"]);
							}
						}
						else
						{
							$error = "Unable to save the ".$parameters["name"];
						}
					}
				}
			}
			else
			{
				$error = validation_errors();
				
				if ($entity_id)
				{
					$details[$parameters["id"]] = $entity_id;
				}

			}
		}
		else if ($entity_id)
		{
			$get_method = "get_".$parameters["type"];
			
			$details = $model->$get_method($entity_id);
		}
		else
		{
			$details = array();
		}
		
		return $details;
	}
	function item_list($parameters=array())
	{
        if ($this->input->get('clear_filters'))
        {
            redirect($parameters["redirect_to"]);
        }
	}
	function delete_item($parameters=array())
	{
		extract($parameters);

		$delete_method = "delete_".$type;
		if ($this->$model->$delete_method($item_id) > 0)
		{
			$this->session->set_flashdata("message", $name." Deleted");
		}
		else
		{
			$this->session->set_flashdata("error", "Unable to delete the ".$name);
		}
		
		if (isset($redirect_to))
		{
			redirect($redirect_to);
		}

	}
}
?>