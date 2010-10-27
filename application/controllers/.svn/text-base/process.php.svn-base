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



class Process extends SM_Controller {
	public function Process() {
		parent::SM_Controller();
		$this->load->model(Array('jsonmodel', 'formmodel', 'arcmodel'));	
			
	}
	
	/**
	 * Default controller, redirects appropriately.
	 */
	public function index() {
/*
		$records = $this->arcmodel->browse("20");
		$browse_string = "";
		foreach ($records as $record) {
			$browse_string .= "<a href=\"displayLCA/".str_replace("/", "-", $record['subject'])."\">".$record['object']."</a><br />";
		}
		
		$this->data("browse_string", $browse_string);
		$this->display("Browse", "browse_view");
*/		
	}
	

	public function add($s = null) {
		if($s == null) {		
			$json_array = $this->jsonmodel->getJSONasArray("process");
			$form = $this->formmodel->generateForm($json_array, "/process/add/s");
			$this->data("form_string", $form);
			$this->display("Form", "form_view");
		} else {
					$json_array = $this->jsonmodel->getJSONasArray("process");

					$new_URI = $this->generateUniqueURI();
					$triples = Array();

					$triples[] = Array("subject" => $new_URI, "predicate" => "rdfs:type", "object" => "lca");
					foreach ($json_array['fields'] as $field) {
						$triples[] = Array("subject" => $new_URI, "predicate" => $field['name'], "object" => $_REQUEST[$field['name']]);
					}
					$this->arcmodel->addTriples($triples);						
		}
	}
		
	

	private function generateUniqueURI() {
		$uri_number = rand(100000,1000000);
		$uri = "http://opensustainability.info/".$uri_number;			
		return $uri;		
	}
	
	public function addLCA() {
		echo "boo";
/*		
		$json_array = $this->jsonmodel->getJSONasArray("process");

		$new_URI = $this->generateUniqueURI();
		$triples = Array();

		$triples[] = Array("subject" => $new_URI, "predicate" => "rdfs:type", "object" => "lca");
		foreach ($json_array['fields'] as $field) {
			$triples[] = Array("subject" => $new_URI, "predicate" => $field['name'], "object" => $_REQUEST[$field['name']]);
		}
		$this->arcmodel->addTriples($triples);
*/

	}


/*	
	public function browse() {
		$records = $this->arcmodel->browse("20");
		$browse_string = "";
		foreach ($records as $record) {
			$browse_string .= "<a href=\"displayLCA/".str_replace("/", "-", $record['subject'])."\">".$record['object']."</a><br />";
		}
		
		$this->data("browse_string", $browse_string);
		$this->display("Browse", "browse_view");

	}	
*/

/*
	public function displayLCA($uri) {
		$uri = str_replace("-", "/", $uri);

		$record = $this->arcmodel->getLCA($uri);
		$record_string = "";
		foreach ($record as $attr) {
			$record_string .= $attr['predicate']." - ".$attr['object']."<br />";
		}
		
		$this->data("record_string", $record_string);
		$this->display("Display Data", "data_view");

	}	
*/
	
} // End Auth

