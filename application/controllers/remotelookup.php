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



class Remotelookup extends SM_Controller {
	public function Remotelookup() {
		parent::SM_Controller();
		$this->load->model(Array('arcmodel', 'arcremotemodel', 'mysqlmodel'));	
		$this->load->library(Array('form_extended', 'name_conversion','SimpleLoginSecure'));
	}

	public function search($label) {
		$label = "qudtu:Kilogram";
		$this->arcremotemodel->getLabel($label);
	} // End of Function

} // End of Class