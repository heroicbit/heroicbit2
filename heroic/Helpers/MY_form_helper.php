<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('set_value'))
{
	/**
	 * Form Value
	 *
	 * Grabs a value from the POST array for the specified field so you can
	 * re-populate an input field or textarea. If Form Validation
	 * is active it retrieves the info from the validation class
	 *
	 * @param	string	$field		Field name
	 * @param	string	$default	Default value
	 * @param	bool	$html_escape	Whether to escape HTML special characters or not
	 * @return	string
	 */
	function set_value($field, $default = '', $html_escape = TRUE)
	{
		// $postdata = ci()->input->post($field, FALSE) ?? ci()->session->flashdata($field);

		// Get from flashdata if exist
		if(ci()->input->post($field, FALSE)){
			$postdata = ci()->input->post($field, FALSE);
		} else {
	        if (strpos($field, '[') === false)
				$postdata = ci()->session->flashdata($field);
	        else {
	        	// Get array session flashdata
				if($temp = ci()->session->flashdata()){
		            $dotted = str_replace(['[','][',']'], ['.','.',''], $field);
		            $keys = explode('.', $dotted);

		            // Make sure there is nested flashdata for nested message form
		            if(isset($temp[$keys[0]]) && is_array($temp[$keys[0]])) {
			            for ($i = 0; $i < count($keys); $i++)
			                if (isset($temp[$keys[$i]])) $temp = $temp[$keys[$i]];
			            $postdata = $temp;
		            } else
			            $postdata = null;
				} else {
					$postdata = null;
				}
	        }
		}

		$value = (isset(ci()->form_validation) && is_object(ci()->form_validation) && ci()->form_validation->has_rule($field))
			? ci()->form_validation->set_value($field, $default)
			: $postdata;

		isset($value) OR $value = $default;
		return ($html_escape) ? html_escape($value) : $value;
	}
}

if ( ! function_exists('clear_value'))
{
	/**
	 * Clear form value from flashdata session
	 * Warning: this will wipe all flashdata. 
	 * Be sure to call this function after inserting data and before set any flashdata message
	 */
	function clear_value()
	{
		$_SESSION['__flash'] = [];
	}
}
