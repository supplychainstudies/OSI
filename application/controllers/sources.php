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



class Sources extends SM_Controller {
	public function __construct() {
		parent::SM_Controller();
		$this->load->model(Array('arcmodel', 'arcremotemodel', 'mysqlmodel'));	
		$this->load->library(Array('form_extended', 'name_conversion'));
	}
	public $URI;
	public $data;
	public $post_data;
	
	
	public function lookup($value) {
	/***
    * @public
    * Generates a form, or, in the case where post data is passed, submits the data to the DB
    */
		if (strstr($value, "@") !== false) {
			@$results = $this->arcmodel->searchFoafviaEmail($value);
		} elseif (strstr($value, " ") !== false) {
			$name = explode(" ", $value);
			$name_array = array("firstName" => $name[0], "lastName" => $name[1]);
			@$results = $this->arcmodel->searchFoaf($name_array);
		} 
		//var_dump($results);
		echo json_encode($results);
	}
	
	public function create() {
	/***
    * @public
    * Generates a form, or, in the case where post data is passed, submits the data to the DB
    */
	$this->check_if_logged_in();
		if($_POST) {
			$data = $this->form_extended->load('bibliography'); 
			$uri = "http://footprinted.org/rdfspace/sources/" . trim($_POST['title_']) . rand(1000000000,10000000000);
			@$triples = $this->form_extended->build_triples($uri, $_POST, $data);	
			var_dump($triples);
			//@$this->arcmodel->addTriples($triples);
			// Show the whole entry
			//$this->view($URI);	
		} else {
			$data = $this->form_extended->load('bibliography'); 
			$the_form = $this->form_extended->build();
			$this->style(Array('style.css', 'form.css'));
			$this->script(Array('form.js', 'toggle.js', 'lookup.js'));
			$this->data("view_string", $the_form);
			$this->display("Form", "view");
		}
	}
}