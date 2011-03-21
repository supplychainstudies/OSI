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



class Create extends SM_Controller {
	public function Create() {
		parent::SM_Controller();
		$this->load->model(Array('arcmodel', 'arcremotemodel', 'mysqlmodel'));	
		$this->load->library(Array('form_extended', 'name_conversion'));
	}
	public $URI;
	public $data;
	public $post_data;
	
	
	function _remap($section)
	{
	    $this->index($section);
	}
	
	
	public function index($section) {
	/***
    * @public
    * Generates a form, or, in the case where post data is passed, submits the data to the DB
    */
		if ($post_data = $_POST) {
			$data = $this->form_extended->load($section); 	
			/* if (isset($data['root']) == true) {
				$name = "";
				if ($section == "person") {
					$name = $post_data['firstName'] . $post_data['firstName'];
				}
				elseif ($section == "classification") {
					$name = "";
				}
				elseif ($section == "bibliography") {
					$name = str_replace(" ", "", $post_data['title']);
				}
				$previous_bnode = "http://db.opensustainability.info/" . $section . "/" . $name . rand(1000000000,10000000000);
			} elseif (isset($_GET['parentBNode']) == true) {
				$previous_bnode = $_GET['parentBNode'];
			}	
			@$triples = $this->form_extended->build_triples($previous_bnode, $post_data, $data);
			if (isset($data['root']) == true && isset($_GET['parentBNode']) == true) {
				$triples[] = Array("subject" => $_GET['parentBNode'], "predicate" => "rdfs:type", "object" => $data_type);
			} */
			$triples = $this->form_extended->build_triples("", $post_data, $data);
			var_dump($triples);
		// Submit the fully generated list of triples to the DB
		//@$this->arcmodel->addTriples($triples);
		// Show the whole entry
		//$this->view($URI);
		} else {
			$data = $this->form_extended->load($section); 
			$the_form = $this->form_extended->build();
			$this->style(Array('style.css', 'form.css'));
			$this->script(Array('form.js', 'toggle.js', 'lookup.js'));
			$this->data("view_string", $the_form);
			$this->display("Form", "view");
		}
	}
}