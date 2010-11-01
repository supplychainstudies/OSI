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



class Info extends SM_Controller {
	public function Info() {
		parent::SM_Controller();
		$this->load->model(Array('arcmodel', 'arcremotemodel', 'mysqlmodel'));	
		$this->load->library(Array('form_extended', 'name_conversion','SimpleLoginSecure'));
		if($this->session->userdata('id')) {
			$this->data("header", "loggedin");
			if ($this->session->userdata('user_email') == true) {
				$this->data("id", $this->session->userdata('user_email'));
			} else if ($this->session->userdata('id') == true) {
				$this->data("id", $this->session->userdata('id'));
			}
		} else {
			$this->data("header", "login");
		}
	}
	public $URI;
	public $data;
	public $post_data;
	
	// This var will store all the data types that will be available through opensustainability. For the forseeable future, this will just be LCA data
	public $data_types = array(
		'lca' => array(
			'label' => 'Life Cycle Assessment',
			'description' => 'Life Cycle Assessment is.',
			'stages' => array(
				'processdescription' => array(
						'name' => 'processDescription',
				 		'label' => 'Process Description',
						'path' => 'lifeCycleInventory->process'
					),
				'inputsandoutputs' => array(
						'name' => 'inputsandoutputs',
						'label' => 'Process Inputs and Outputs (Multiple Entries Possible)',
						'path' => 'lifeCycleInventory->process'	
					),
				'modelingandvalidation' => array(	
						'name' => 'modelingandValidation',
						'label' => 'Modeling and Validation Information',
						'path' => 'lifeCycleInventory'
					),
				'administrativeinformation' => array(
						'name' => 'administrativeInformation',
						'label' => 'Administrative Information',
						'path' => 'lifeCycleInventory'
					),
				'impactassessment' => array(
						'name' => 'impactAssessment',
						'label' => 'Impact Assessment (Multiple Entries Possible)',
						'path' => ''
					)
			)
		)
		);
		public $stages = array(
					'processdescription' => array(
							'name' => 'processDescription',
					 		'label' => 'Process Description',
							'path' => 'lifeCycleInventory->process'
						),
					'inputsandoutputs' => array(
							'name' => 'inputsandoutputs',
							'label' => 'Process Inputs and Outputs (Multiple Entries Possible)',
							'path' => 'lifeCycleInventory->process'	
						),
					'modelingandvalidation' => array(	
							'name' => 'modelingandValidation',
							'label' => 'Modeling and Validation Information',
							'path' => 'lifeCycleInventory'
						),
					'administrativeinformation' => array(
							'name' => 'administrativeInformation',
							'label' => 'Administrative Information',
							'path' => 'lifeCycleInventory'
						),
					'impactassessment' => array(
							'name' => 'impactAssessment',
							'label' => 'Impact Assessment (Multiple Entries Possible)',
							'path' => ''
						)
			);
	

	public function create($data_type = null, $stage = null, $local_URI = null) {
	/***
    * @public
    * Generates a form, or, in the case where post data is passed, submits the data to the DB
    */				
		// Check if there is a data type specified in the url (ex: http://opensustainability.info/info/create/[datatype])
		if ($data_type != null) {
		//There is a data type specified in the url				
			//Check if there is a stage (or section) specified in the url (ex: http://opensustainability.info/info/create/[datatype]/[stage])
			if ($stage != null) {	
			// There is a stage specified in the url				
				// Pre-load the form_extended library
				$data = $this->form_extended->load($stage); 	
				// Check if there is POST data
				if ($post_data = $_POST) {
				// There is POST data
					// Check if there is a URI specified in the url (ex: http://opensustainability.info/info/create/[datatype]/[stage]/[uri])
					if ($local_URI == null) {
					// if there is not a URI identifier specified in the url, generate a random number for a new URI
						$URI = rand(1000000000,10000000000);
					} 
					else {
					// if there is a uri, add the post data under this uri
						$URI = $local_URI;	
					}

					// Initialize array to store data in triple form
					$triples = array();
					
					// if this is a new entry (as opposed to an addition to an existing entry) add a triple which defines its data type
					if ($local_URI == null) {
						$triples[] = Array("subject" => "http://db.opensustainability.info/".$URI, "predicate" => "rdfs:type", "object" => $data_type);
						// If the author is logged in, add their id to the document
						if($this->session->userdata('logged_in')) {
							$triples[] = Array("subject" => "http://db.opensustainability.info/".$URI, "predicate" => "dc:author", "object" => $this->session->userdata('user_email'));							
						}
					}
					
					// Figure out what paths (that lead to the stage) exist for this uri, and which paths must be added
					// For instance, if you're adding a "process description" to an "LCA" record, you must point the uri to the "life cycle inventory", the "life cycle inventory" to the "process" and then point the "process" to the "process description"
					$previous_bnode = "http://db.opensustainability.info/".$URI;
					if ($this->data_types[$data_type]['stages'][$stage]['path'] != "") {
						foreach (explode("->", $this->data_types[$data_type]['stages'][$stage]['path']) as $next) {						
							@$results = $this->arcmodel->getNextBnode($previous_bnode, $next);
							if (count($results) == 0) {
								$next_bnode = $this->name_conversion->toBNode($next);
								$triples[] = Array("subject" => $previous_bnode, "predicate" => "lca:".$this->name_conversion->toLinkedType($next), "object" => $next_bnode);
								$previous_bnode = $next_bnode;	
							}
							else {
								$previous_bnode = $results['next_bnode'][0];							
							}
						}
					}

					if (count($triples) > 0) {
						@$triples = array_merge($triples, $this->form_extended->build_triples($previous_bnode, $post_data, $data));	
					} else {
						@$triples = $this->form_extended->build_triples($previous_bnode, $post_data, $data);	
					}
					
					// Submit the fully generated list of triples to the DB
					@$this->arcmodel->addTriples($triples);
					// Show the whole entry
					$this->view($URI);
				} // end of if ($post_data = $_POST)
				else {
				// There is no POST Data
				// Just generate a form
					// Check if there is a URI specified in the url (ex: http://opensustainability.info/info/create/[datatype]/[stage]/[uri])
					// if so, append it to the form action
					if ($local_URI != null) {
						$URI = $local_URI;	
						$this->form_extended->change_action($URI);
					}
					
			  		$the_form = $this->form_extended->build();
					$this->style(Array('style.css'));
					$this->script(Array('form.js'));
					$this->data("view_string", $the_form);
					$this->display("Form", "view");		
				} 
			}
			else {
			// There is no stage defined
			// List the possible stages for the data type
				$this->stageMenu($data_type);			
			}
		} 
		else {
		// There is no data type defined
		// List all the data types
			$this->datatypeMenu();			
		}
	}	// End of function create

	/***
    * @public
    * If the datatype is not chosen,  print out the list of data types
    */
	function datatypeMenu() {
		$intro = "<div>What kind of data would you like to submit? Select a data type: (Actually, we only have Life Cycle Assessment right now. Email us if you would like to work with us on adding your own.)</div>\n";
		$intro .= "<ul>\n";
		foreach ($this->data_types as $key => $_data_type) {
			$intro .= "<li><h1><a href=\"".$key."/\">".$_data_type['label']."</a></li></h1>\n" . 
			$_data_type['description'] . "\n" . 
			"<div>When creating an LCA Document, you can complete any or all of the sections below</div>" .	
			"<ul>\n";
		
			foreach ($_data_type['stages'] as $_key => $_stage) {
				$intro .= "<li><a href=\"".$key."/".$_key."/\">".$_stage['label']."</a></li>\n";
			}					
			$intro .= "</ul>";			
		}
		$intro .= "</ul>\n";

		$this->data("view_string", $intro);
		$this->display("Menu", "view");		
	}


	/***
    * @public
    * If the datatype is chosen, but not the stage, print out the list of stages
    */	
	function stageMenu($data_type) {
		$intro = "<div>When creating a(n) " . $this->data_types[$data_type]['label'] . " Document, you can complete any or all of the sections below</div>";
		$intro .= "<ul>\n";
		foreach ($this->data_types[$data_type]['stages'] as $_stages) {
			$intro .= "<li><a href=\"".$_stages['name']."/\">".$_stages['label']."</a></li>\n";			
		}
		$intro .= "</ul>\n";
		$this->style(Array('style.css'));
		$this->script(Array());
		$this->data("view_string", $intro);
		$this->display("Menu", "view");
		
	}


	/***
    * @public
    * Grabs all the triples for a particular URI and shows it in a friendly, human readable way
    */
	// Showing a single data point? What does this function do?
	public function view($URI = null) {	
		// Get the data
		@$data_type = $this->arcmodel->getDataType("http://opensustainability.info/".$URI);
		$stages = $this->stages;
		// Get all the Impacts 		
		@$impacts = $this->arcmodel->getImpacts("http://opensustainability.info/".$URI);
		// For each impact
		@$set = array();
		foreach ($impacts as $impact) {		
			// append impacts to the correct record in the set variable
			foreach ($impact as $__key => $_field) {
				// if its a uri, get the label and store that instead
				// rewrite this into a better function later
				if (strpos($_field, "dbpedia") !== false) {
					@$set[$impact['impactCategory']][$__key] = $this->getLabel($_field, 'rdfs:type');
				} else {
					$set[$impact['impactCategory']][$__key] = $_field;
				}					
			}
		}
		
		$this->data("set", $set);
		$this->data("URI", $URI);
		$this->data("triples", $triples);
		$this->data("type", $data_type);
		$this->script(Array('comments.js'));
		$comment_data = $this->form_extended->load('comment');
		$comment = $this->form_extended->build();
		$comments = $this->arcmodel->getComments("http://opensustainability.info/".$URI);
		$this->data("comments", $comments);
		$this->data("comment", $comment);
		$this->display("View", "data_view");		
	}	

	public function viewRDF($URI = null) {
		@$rdf = $this->arcmodel->getRDF("http://opensustainability.info/".$URI);
		header('Content-type: text/xml');
		$this->data("rdf", $rdf);
		$this->display("View", "view");
	}


	/***
    * @public
    * Grabs all the triples for a particular URI and shows it in JSON
    */	
	public function viewJSON($URI = null) {
		@$json = $this->arcmodel->getJSON("http://opensustainability.info/".$URI);
		header('Content-type: application/json');
		echo $json;
	}


	/***
    * @public
    * Checks to see if a reference to a remote uri has already been cached in the local mysql table. if so, return it, if not, retrieve the value from the remote endpoint, store it locally, then return it
    */
	public function getLabel($field, $type) {
		if ($this->mysqlmodel->getCachedValue($field, $type) != false) {
			return $this->mysqlmodel->getCachedValue($field, $type);
		} else {
			$value = $this->arcremotemodel->getLabel($field);
			$this->mysqlmodel->addCachedValue($field, $type, $value);
			return $value;
		}
	}
	


	/***
    * @public
    * Shows all data entries
	* This is not functional for non-LCA entries and does not have search or filter capabilities yet
    */
	// Public function for exploring the repository
	public function index() {
		
		// Querying the database for all records		
		@$records = $this->arcmodel->getRecords();
		// Initializing array
		$set = array();

		// Filling the arry with the records
		foreach ($records as $key => $record) {	
			// Go through each field
			foreach ($record as $_key => $field) {
				// if its a uri, get the label and store that instead 
				// rewrite this into a better function later
				if (strpos($field, "dbpedia") !== false) {
					$set[$key][$_key] = $this->getLabel($field, 'rdfs:type');		
				} else {
					$set[$key][$_key] = $field;
				}
			}
			/*/ Get all the Impacts 		
			@$impacts = $this->arcmodel->getImpacts($record['link']);
			// For each impact
			foreach ($impacts as $impact) {
				// Create a list of all the distinct impact categories
				if (in_array($impact['impactCategory'], $impact_categories) == false) {
					$impact_categories[] = $impact['impactCategory'];
				}		
				// append impacts to the correct record in the set variable
				foreach ($impact as $__key => $_field) {
					// if its a uri, get the label and store that instead
					// rewrite this into a better function later
					if (strpos($_field, "dbpedia") !== false) {
						@$set[$key][$impact['impactCategory']][$__key] = $this->getLabel($_field, 'rdfs:type');
					} else {
						$set[$key][$impact['impactCategory']][$__key] = $_field;
					}					
				}
			}	*/	
			
	
		
		}
		// Send data to the view
		$this->data("set",$set);
		$this->display("Browse","browse_view");		
	}
	
	/***
    * @public
    * Allows you to edit an entry
	* This is not functional yet
    */	
	public function edit($URI, $stage = null) {
		
		@$data_type = $this->arcmodel->getDataType("http://db.opensustainability.info/".$URI);
		$stages = $this->data_types[$data_type]['stages']; 
		$view_string = "";
		foreach ($stages as $key => $_stage) {
			@$xarray = $this->arcmodel->getStage("http://db.opensustainability.info/".$URI, $_stage['path'], $_stage['name']);
			if ($xarray != false) {
				@$data = $this->form_extended->load($key);	
				$view_string .= $this->form_extended->build_edit($xarray, $data)."<br>";
			}				
		}
		$this->script(Array('form.js', 'toggle.js'));
		$this->data("URI", $URI);
		$this->data("view_string", $view_string);
		
		$this->display("View", "view");		
	}	

}
