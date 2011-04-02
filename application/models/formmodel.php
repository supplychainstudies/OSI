<?php
/**
 * Controller for dealing with processes
 * 
 * @version 0.8.0
 * @author info@opensustainability.info
 * @package opensustainability
 * @subpackage controllers
 * @uses 
 */
class FormModel extends Model{

	function FormModel(){
		parent::Model();
	}
	function generateForm($form_array, $action) {
		$form = "<h1>".$form_array['form-name']."</h1>\n<p>".$form_array['description']."</p>";
		$form .= form_open($action);
		foreach ($form_array['fields'] as $field) {
			$attributes = Array(
					'name' => $field['name'],
					'id' => $field['name']
				);
			$form .= "<label name=\"".$field['name']."\">".$field['label']."</label>".form_input($attributes)."<p>".$field['notes']."</p><br />\n";
		}
		$form .= "</div>"
		$form .= form_submit("submit_form", "Submit").form_close();
		return $form; 
	}

}

?>