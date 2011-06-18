<?php
/**
 * Controller for environmental information structures
 * 
 * @version 0.8.0
 * @author info@footprinted.org
 * @package opensustainability
 * @subpackage controllers
 * @uses 
 */



class Foaf extends SM_Controller {
	public function Foaf() {
		parent::SM_Controller();
		$this->load->model(Array('arcmodel', 'arcremotemodel', 'mysqlmodel'));	
		$this->load->library(Array('form_extended', 'name_conversion'));
	}
	public $URI;
	public $data;
	public $post_data;
	
	
	public function lookup() {
	/***
    * @public
    * Generates a form, or, in the case where post data is passed, submits the data to the DB
    */
		$values = $_REQUEST;
		$foaf_array = array();
		if (isset($values['firstName']) == true) {
			$foaf_array['firstName'] = $values['firstName'];		
		}
		if (isset($values['lastName']) == true) {
			$foaf_array['lastName'] = $values['lastName'];
		}
		if (isset($values['email']) == true) {
			$foaf_array['email'] = $values['email'];	
		}
		@$results = $this->arcmodel->searchFoaf($foaf_array);
		echo json_encode($results);
	}
	
	/***
    * @public
    * Generates a form, or, in the case where post data is passed, submits the data to the DB
    */
	public function create() {
		$this->check_if_logged_in();
		if($_POST) {
			$data = $this->form_extended->load('foaf'); 
			$uri = "http://footprinted.org/rdfspace/people/" . trim($_POST['firstName_']) . trim($_POST['lastName_']) . rand(1000000000,10000000000);
			@$triples = $this->form_extended->build_triples($uri, $_POST, $data);	
			var_dump($triples);
			@$this->arcmodel->addTriples($triples);
			// Show the whole entry
			//$this->view($URI);	
		} else {
			$data = $this->form_extended->load('foaf'); 
			$the_form = $this->form_extended->build();
			$this->style(Array('style.css', 'form.css'));
			$this->script(Array('form.js', 'toggle.js', 'lookup.js'));
			$this->data("view_string", $the_form);
			$this->display("Form", "view");
		}
	}
}