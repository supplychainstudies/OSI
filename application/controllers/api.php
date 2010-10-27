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



class API extends SM_Controller {
	public function API() {
		parent::SM_Controller();
		$this->load->model(Array('arcmodel', 'arcremotemodel', 'mysqlmodel'));	
		$this->load->library(Array('form_extended', 'name_conversion','SimpleLoginSecure'));
		if($this->session->userdata('logged_in')) {
			$this->data("header", "loggedin");
			if ($this->session->userdata('user_email') == true) {
				$this->data("id", $this->session->userdata('user_email'));
			} else if ($this->session->userdata('openid_email') == true) {
				$this->data("id", $this->session->userdata('openid_email'));
			}
		} else {
			$this->data("header", "login");
		}
	}
	public $URI;
	public $data;
	public $post_data;
	
	/***
    * @public
    * Allows you to edit an entry
	* This is not functional yet
    */	
	public function search($field, $keyword, $encoding = 'json') {
		$URIs = @$this->arcmodel->simpleSearch($field, $keyword);		
		if ($encoding == 'json') {
			header('Content-type: application/json');
			echo json_encode($URIs);
		} else if ($encoding == 'rdf') {
			header('Content-type: text/xml');
			echo $URIs;			
		} else if ($encoding == 'xml') {
			header('Content-type: text/xml');
			echo $URIs;
		}
	}	
}
