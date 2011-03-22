<?php
/**
 * Controller for environmental information structures
 * 
 * @version 0.8.0
 * @author info@opensustainability.info
 * @package opensustainability
 * @subpackage controllers
 * @uses 
 */



class Lookup extends SM_Controller {
	public function Lookup() {
		parent::SM_Controller();
		$this->load->model(Array('arcmodel', 'arcremotemodel', 'mysqlmodel'));	
		$this->load->library(Array('form_extended', 'name_conversion', 'xml'));
		$obj =& get_instance();    
        $obj->load->library(array('xml'));
        $this->ci =& $obj;
	}
	public $URI;
	public $data;
	public $post_data;
	
	public function units() {
	/***
    * @public
    * Generates a form, or, in the case where post data is passed, submits the data to the DB
    */

	 if (! $this->ci->xml->load ("data/conversion/units/units")) {
	        $this->error = "Failed to load form: $name";
	        return false;
	    }
		$data = $this->ci->xml->parse ();
		var_dump($data);
		
	}
}