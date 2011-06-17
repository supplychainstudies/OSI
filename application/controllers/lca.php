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

class Lca extends FT_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model(Array('lcamodel', 'geographymodel', 'bibliographymodel','peoplemodel','commentsmodel','ecomodel'));	
		$this->load->library(Array('form_extended', 'xml'));
		$this->load->helper(Array('nameformat_helper'));
		$this->load->helper(Array('linkeddata_helper'));
		$obj =& get_instance();    
        $obj->load->library(array('xml'));
        $this->ci =& $obj;
	}
	public $URI;
	public $data;
	public $post_data;

	public function index() {
		$data = $this->form_extended->load("start"); 
		$the_form = $this->form_extended->build();
		$this->style(Array('style.css', 'form.css'));
		$this->script(Array('form.js', 'toggle.js', 'lookup.js'));
		$this->data("view_string", $the_form);
		$this->display("Form", "view");
	}
	
	/***
    * @public
    * Generates a form, or, in the case where post data is passed, submits the data to the DB
    */
	public function create() {

		if ($post_data = $_POST) {	
			$model_node = toURI("lca", $post_data['productServiceName_']); 
			$exchange_node = $model_node;
			$bibliography_node = toURI("bibliography", $post_data['title_']); 
			$process_node = $model_node;
			$product_node = $model_node;
			$impactassessment_node = "";
			
			// Bibliography
			// First, look to see if they picked the first author, or if its someone new
			if ($post_data['author_'] != "") {
				$person_node = $post_data['author_'];
			} elseif ($post_data['author_label_'] != "") {
				$person_node = toURI("person", $post_data['author_label_']); 
				if (strpos($post_data['author_label_'], ",") !== false) {
					$name_array = explode (",", $post_data['author_label_']);
					$post_data['firstName_'] = trim($name_array[1]);
					$post_data['lastName_'] = trim($name_array[0]);
				} elseif (strpos($post_data['author_label_'], " ") !== false) {
					$name_array = explode(" ", $post_data['author_label_']);
					$post_data['firstName_'] = trim($name_array[0]);
					$post_data['lastName_'] = trim($name_array[1]);
				} elseif ($post_data['author_label_'] == "") {
					$post_data['firstName_'] = "";
					$post_data['lastName_'] = "";
				} else {
					$post_data['firstName_'] = "";
					$post_data['lastName_'] = $post_data['author_label_'];
				}
				$datasets['person'][] = array ();
				if ($post_data["firstName_"] != "") {
					$datasets['person'][0]["firstName_"] = $post_data["firstName_"];
				}
				if ($post_data["lastName_"] != "") {
					$datasets['person'][0]["lastName_"] = $post_data["lastName_"];
				}
				if ($post_data["email_"] != "") {
					$datasets['person'][0]["email_"] = $post_data["email_"];
				}					
			} 

			$datasets['bibliography'] = array();		
			if ($post_data["title_"] != "") {
				$datasets['bibliography'][0]["title_"] = $post_data["title_"];
			}
			if ($post_data["link_"] != "") {
				$datasets['bibliography'][0]["uri_"] = $post_data["link_"];
			}
			if ($post_data["year_"] != "") {
				$datasets['bibliography'][0]["date_"] = $post_data["year_"];
			}
			if (isset($person_node) == true) {
				$datasets['bibliography'][0]["author_"] = array($person_node);
			}
						
			$datasets['process'] = array();
			if ($post_data['productServiceName_'] != "") {
				$datasets['process'][0]["name_"] = $post_data['productServiceName_'];
			}
			if ($post_data['description_'] != "") {
				$datasets['process'][0]["description_"] = $post_data['description_'];
			}
			$datasets['product'] = array();
			if ($post_data['productServiceName_'] != "") {
				$datasets['product'][0]["name_"] = $post_data['productServiceName_'];
			}
			if ($post_data['category_'] != "") {
				$datasets['product'][0]["category_"] = $post_data['category_'];
			}
			if ($post_data['qrUnit_'] == "") 
				$post_data['qrUnit_'] = $post_data['qrUnit_label_'];
			$datasets['exchange'][] = array (
					"direction_" => 'eco_Output',
					"exchange_" => 'eco_Transfer',
					"transferable_" => $post_data['productServiceName_'],
					"quantity_" => $post_data['qrQuantity_'],
					"unit_" => $post_data['qrUnit_']
				);
			$change_p['exchange'][] = array('Exchange'=>'eco:hasReferenceExchange');
			if (isset($post_data['io_']) == true) {
				for ($i = 0; $i< count($post_data['io_']); $i++) {
					if ($post_data['ioUnit_'][$i] == "") 
						$post_data['ioUnit_'][$i] = $post_data['ioUnit_label_'][$i];
					$datasets['exchange'][] = array (
							"direction_" => $post_data['io_'][$i],
							"exchange_" => $post_data['exchangeType_'][$i],
							"transferable_" => $post_data['substanceName_'][$i],
							"quantity_" => $post_data['ioQuantity_'][$i],
							"unit_" => $post_data['ioUnit_'][$i]
						);
				}
			}
			
			foreach ($post_data['assessmentQuantity_'] as $i=>$aunit) {
				if ($post_data['assessmentUnit_'][$i] == "") 
					$post_data['assessmentUnit_'][$i] = $post_data['assessmentUnit_label_'][$i];
				if ($post_data['impactCategory_'][$i] == "") 
					$post_data['impactCategory_'][$i] = $post_data['impactCategory_label_'][$i];
				if ($post_data['impactCategoryIndicator_'][$i] == "") 
					$post_data['impactCategoryIndicator_'][$i] = $post_data['impactCategoryIndicator_label_'][$i];
			}
			$datasets['impactassessment'][] = array (
					"impact_category_indicator_result_counter_0" => $post_data['impact_assessment_counter_0'],
					"computedFrom_" => $model_node,
					"impactCategory_" => $post_data['impactCategory_'],
					"impactCategoryIndicator_" => $post_data['impactCategoryIndicator_'],
					"quantity_" => $post_data['assessmentQuantity_'],
					"unit_" => $post_data['assessmentUnit_']
				);
			$triples = array(
				array(
					'subject' => $model_node,
					'predicate' => 'dcterms:creator',
					'object' => $this->session->userdata('foaf')
				),
				array(
					'subject' => $model_node,
					'predicate' => 'dcterms:created',
					'object' => date('h:i:s-m:d:Y')
				),
				array(
					'subject' => $model_node,
					'predicate' => 'eco:hasDataSource',
					'object' => $bibliography_node
				),
				array(
					'subject' => $model_node,
					'predicate' => 'rdfs:type',
					'object' => "eco:Model"
				),
			);	

			foreach ($datasets as $key=>$dataset) {
				if ($key != "submit_") {
					foreach ($dataset as $i=>$datasetinstance) {
						$node_name = $key."_node";
						$node = $$node_name;
						$data = $this->form_extended->load($key); 	
						if (isset($change_p[$key][$i]) == false) {
							$change_p[$key][$i] = null;
						}					
						$triples = array_merge($triples,$this->form_extended->build_group_triples($node, $datasetinstance, $data,"",0, $change_p[$key][$i]));
					}
				} 
			}
			
			foreach ($triples as $key=>&$triple) {
				if ($triple['predicate'] == 'rdfs:type' && $triple['object'] == 'eco:Product') {
					$_product_node = $triple['subject'];
				}
				if ($triple['predicate'] == 'rdfs:type' && $triple['object'] == 'eco:Process') {
					$_process_node = $triple['subject'];
				}
				if ($triple['predicate'] == 'rdfs:type' && $triple['object'] == 'eco:ImpactAssessment') {
					$_ia_node = $triple['subject'];
				}								
			}
			foreach ($triples as $key=>&$triple) {
				if ($triple['predicate'] == 'eco:hasTransferable' && trim($triple['object']) == trim($post_data['productServiceName_'])) {
					$triple['object'] = $_product_node;
				}								
			}
			if (isset($_product_node) == true) {
				$triples[] = array(
					"subject" => $_ia_node,
					"predicate" => "eco:assessmentOf",
					"object" => $_product_node
				);
			}
			if (isset($_process_node) == true) {
				$triples[] = array(
					"subject" => $_ia_node,
					"predicate" => "eco:assessmentOf",
					"object" => $_process_node
				);
			}
			$this->lcamodel->addTriples($triples);
			redirect('/lca/view/'.str_replace("http://footprinted.org/rdfspace/lca/","",$model_node));
			//$this->view(str_replace("http://footprinted.org/rdfspace/lca/","",$model_node));
		}else {
			redirect('/create/start');
		}
	}


	/***
	* @public
	* Grabs all the triples for a particular URI and shows it in RDF
	*/
	public function viewRDF($URI = null) {
		$rdf = $this->lcamodel->getRDF("http://footprinted.org/rdfspace/lca/".$URI);
		header("Content-Disposition: attachment; filename=\"$URI.rdf\"");
		header('Content-type: text/xml');
		echo $rdf;
	}	

	/***
	* @public
	* Grabs all the triples for a particular URI and shows it in JSON
	*/	
	public function viewJSON($URI = null) {
		$json = $this->lcamodel->getJSON("http://footprinted.org/rdfspace/lca/".$URI);
		header('Content-type: application/json');
		echo $json;
	}
		

	/***
	* @public
	* Grabs all the triples for a particular URI and shows it in a friendly, human readable way
	*/
	public function view($URI = null) {	
		// Gets everything from the linked database
		$parts['uri'] = $URI;
		$parts['impactAssessments'] = $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments("http://footprinted.org/rdfspace/lca/" . $URI));
		$parts['bibliography'] = $this->bibliographymodel->convertBibliography($this->bibliographymodel->getBibliography("http://footprinted.org/rdfspace/lca/" . $URI));
		$parts['exchanges'] = $this->lcamodel->convertExchanges($this->lcamodel->getExchanges("http://footprinted.org/rdfspace/lca/" . $URI));	
		$parts['modeled'] = $this->lcamodel->convertModeled($this->lcamodel->getModeled("http://footprinted.org/rdfspace/lca/" . $URI));
		$parts['geography'] = $this->lcamodel->convertGeography($this->lcamodel->getGeography("http://footprinted.org/rdfspace/lca/" . $URI));
		$parts['quantitativeReference'] = $this->lcamodel->convertQR($this->lcamodel->getQR("http://footprinted.org/rdfspace/lca/" . $URI));
		$parts['sameAs'] = $this->lcamodel->convertLinks($this->lcamodel->getSameAs("http://footprinted.org/rdfspace/lca/" . $URI));
		$parts['categoryOf'] = $this->lcamodel->getCategories("http://footprinted.org/rdfspace/lca/" . $URI);
		$parts['title'] = $this->lcamodel->getTitle("http://footprinted.org/rdfspace/lca/" . $URI);
		//$parts['suggestions'] = $this->lcamodel->getOpenCycSuggestions("http://footprinted.org/rdfspace/lca/" . $URI);
	 	foreach ($parts as $key=>$part) {
			if ($parts[$key] === false || $parts[$key] == false || count($parts[$key]) == 0) {
				unset($parts[$key]);
			}
		}
		/* Normalize to 1 */
		$oldamount = $parts['quantitativeReference']['amount'];
		$ratio = $parts['quantitativeReference']['amount'];
		$parts['quantitativeReference']['amount'] = 1;
		// If grams	
		if (strpos("Gram", $parts['quantitativeReference']['unit']['label']) !== false) {
			$ratio = $oldamount * 1000;
			$parts['quantitativeReference']['unit']['label'] = "Kilogram";
		}	
		// If ounces
		if ($parts['quantitativeReference']['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Ounce" ) {
			$ratio = $oldamount * 0.028345;
			$parts['quantitativeReference']['unit']['label'] = "Kilogram";
		}
		// If pounds
		if ($parts['quantitativeReference']['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Pound") {
			$ratio = $oldamount * 0.45359237;
			$parts['quantitativeReference']['unit']['label'] = "Kilogram";
		}
		if ($parts['quantitativeReference']['unit']['label'] == "Pound Mass") {
			$ratio = $oldamount * 0.45359237;
			$parts['quantitativeReference']['unit']['label'] = "Kilogram";
		}
		if (isset($parts['exchanges']) == true) {
			foreach ($parts['exchanges'] as &$exchanges) {
				$exchanges['amount'] = $exchanges['amount'] / $ratio;
				if ($exchanges['unit']['label'] == "Gram") {
					$exchanges['amount']/=1000; $exchanges['unit']['label'] = "Kilogram";
				}
				if ($exchanges['unit']['label'] == "Pound Mass") {
					$exchanges['amount'] = $exchanges['amount'] * 0.45359237; 
					$exchanges['unit']['label'] = "Kilogram";
				}
			}
		}
		if (isset($parts['impactAssessments']) == true) {
			foreach ($parts['impactAssessments'] as &$impactAssessment) {
				$impactAssessment['amount'] = $impactAssessment['amount'] / $ratio;
				if ($impactAssessment['unit']['label'] == "Gram") { 
					$impactAssessment['amount']/=1000; 
					$impactAssessment['unit']['label'] = "Kilogram"; 
				}
				if ($impactAssessment['unit']['label'] == "Pound Mass") { 
					$impactAssessment['amount']*=0.45359237; 
					$impactAssessment['unit']['label'] = "Kilogram"; 
				}
			}
		}
		
		
		// Turns exchanges into input and output array divided into categories 
		if (isset($parts['exchanges']) == true) {
			foreach ($parts['exchanges'] as $exchange) {
				if (isset($exchange['unit']['quantityKind']) == true) {
					$parts[$exchange['direction']][$exchange['unit']['quantityKind']][] = $exchange;				
				} else {
					$parts[$exchange['direction']]['misc'][] = $exchange;
				}
			}
			/* Crunches the data to create the graphics and total calculations */
			$totalinput = 0; 
			if (isset($parts['Input']["Mass"]) == true) { 
			foreach ($parts['Input']["Mass"] as $i) {
				$totalinput += $i['amount'];
			}}
			$totalinputliter = 0; 
			if (isset($parts['Input']["Liquid Volume"]) == true) { 
			foreach ($parts['Input']["Liquid Volume"] as $i) {
				$totalinputliter += $i['amount'];
			}}
			$totalinputland = 0; 
			if (isset($parts['Input']["Area"]) == true) { 
			foreach ($parts['Input']["Area"] as $i) {
				$totalinputland += $i['amount'];
			}}
			$totaloutput = 0;
			if (isset($parts['Output']["Mass"]) == true) {  
			foreach ($parts['Output']["Mass"] as $i) {
				$totaloutput += $i['amount'];
			}}
		}		
		$links = '<p><a href="/'.$URI.'.rdf">Get this RDF</a></p><p><a href="/'.$URI.'.json">Get this in JSON</a></p>';
		$this->data("links", $links);
		$this->data("URI", $URI);
		$this->data("parts", $parts);
		if (isset($parts['exchanges']) == true) {
			$this->data("totalinput", $totalinput);
			$this->data("totaloutput", $totaloutput);
			$this->data("totalinputliter", $totalinputliter);
			$this->data("totalinputland", $totalinputland);		
		}	
		$this->script(Array('comments.js', 'janrain.js'));
		$comment_data = $this->form_extended->load('comment');
		$comment = $this->form_extended->build();
		$comments = $this->commentsmodel->getComments("http://footprinted.org/osi/rdfspace/lca/".$URI);
		$this->data("comments", $comments);
		$this->data("comment", $comment);
		$this->display("View " . $parts['quantitativeReference']['amount'] . " " . $parts['quantitativeReference']['unit'] . " of " .  $parts['quantitativeReference']['name'], "viewLCA");		
	}




	private function convertImpactAssessments($dataset){
		$rdfs_prefix = "http://www.w3.org/2000/01/rdf-schema#";
		$eco_prefix = "http://ontology.earthster.org/eco/core#";
		$converted_dataset = array();		
		foreach($dataset as $key=>$_record) {	
			foreach ($_record[$eco_prefix."hasImpactAssessmentMethodCategoryDescription"] as $__record) {
				foreach($__record[$eco_prefix."hasImpactCategory"] as $___record) {
					$converted_dataset[$key]['impactCategory'] = $___record;
				} 
				foreach($__record[$eco_prefix."hasImpactCategoryIndicator"] as $___record) {
					$converted_dataset[$key]['impactCategoryIndicator'] =  $___record;
				}					
			} 	
			foreach ($_record[$eco_prefix."hasQuantity"] as $__record) {
				foreach($__record[$eco_prefix."hasMagnitude"] as $___record) {
					$converted_dataset[$key]['amount'] = $___record;
				}
				foreach($__record[$eco_prefix."hasUnitOfMeasure"] as $___record) {
					$converted_dataset[$key]['unit'] = $___record;
				}		
			}	
			if (isset($converted_dataset[$key]['unit']) == false) {
				$converted_dataset[$key]['unit'] = "?";
			}									
			if (isset($converted_dataset[$key]['amount']) == false) {
				$converted_dataset[$key]['amount'] = "?";
			}
			if (isset($converted_dataset[$key]['impactCategory']) == false) {
				$converted_dataset[$key]['impactCategory'] = "?";
			}
			if (isset($converted_dataset[$key]['impactCategoryIndicator']) == false) {
				$converted_dataset[$key]['impactCategoryIndicator'] = "?";
			}
		}
		return $converted_dataset; 
	}
	
		
		/***
	    * @public
	    * Shows the homepage
		* This is not functional for non-LCA entries and does not have search or filter capabilities yet
	    */
		// Public function for exploring the repository
		public function featured() {
			// Querying the database for all featured URIs		
			$this->db->select('uri');
			$this->db->order_by("uri", "ASC"); 
			$featured = $this->db->get('featured');
			// Initializing array
			$set = array();
			foreach ($featured->result() as $feature) {
				//Get the URI
				$uri = $feature->uri;
				// Get the record
				$this->db->where('uri',$uri);
				$footprint = $this->db->get('footprints',1,0);
				$set[$uri] = $footprint->result();
		    }		
			// Send data to the view
			$this->data("set", $set);
			$this->display("Browse","homepage_view");		
		}
		
				
		public function addSameAs() {
			parse_str($_SERVER['QUERY_STRING'],$_GET); 
			$ids = $_GET;
			$this->lcamodel->addSameAs($ids['ft_id'],$ids['opencyc_id']);
			
		}
		public function addDbpedia() {
			parse_str($_SERVER['QUERY_STRING'],$_GET); 
			$ids = $_GET;
			$this->lcamodel->addDbpedia($ids['ft_id'],$ids['db_id']);
			
		}
		public function addCategory() {
			parse_str($_SERVER['QUERY_STRING'],$_GET); 
			$ids = $_GET;
			$this->lcamodel->addCategory($ids['ft_id'],$ids['opencyc_id']);
			
		}
		
		/*
		Caching functions below:
		We cache the most used information in a normal relational database to allow quick access
		*/
		
		// See if there is any new footprints and then save the URI and Name in the cache table
		public function cacheNames(){
			$records = $this->lcamodel->getRecords();
			// Initializing array
			foreach ($records as $r) {
				$uri = str_replace("http://footprinted.org/rdfspace/lca/", "",$r['uri']); 
				$longuri = "http://footprinted.org/rdfspace/lca/" . $uri;
				$title = $this->lcamodel->getTitle($longuri);
				//Search if it's in the db
				$this->db->where('uri', $uri); 
				$rs = $this->db->get('footprints');
				if($rs->result() == false){
					$data = array (
						'uri' => $uri,
						'name' => $title,
					);
					$this->db->insert('footprints', $data);
				}
			}
		}
		
		
		public function cacheImpactsToMasterDB(){
			$rs = $this->db->get('footprints');	
			// Go through all the lcas
			foreach ($rs->result() as $r) {
				$this->db->where('uri',$r->uri);
				$this->db->where('impact',"Carbon Dioxide Equivalent");
				$impact = $this->db->get('impacts',1,0);
				$amount = NULL;
				foreach ($impact->result()as $i) { $amount = $i->amount;}
				$data = array (
					'co2e' => $amount
				);
				$this->db->where('uri', $r->uri);
				$this->db->update('footprints', $data);
			}
		}
		
		
		public function cacheImpacts(){
			$rs = $this->db->get('footprints',200,599);	
			// Go through all the lcas
			foreach ($rs->result() as $r) {
				// Get the impacts
				$uri = $r->uri;
				$parts['impactAssessments'] = $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments("http://footprinted.org/rdfspace/lca/" . $uri));
				$parts['quantitativeReference'] = $this->lcamodel->convertQR($this->lcamodel->getQR("http://footprinted.org/rdfspace/lca/" . $uri));
				/* Normalize to 1 */
				$oldamount = $parts['quantitativeReference']['amount'];
				$ratio = $parts['quantitativeReference']['amount'];
				$parts['quantitativeReference']['amount'] = 1;
				// If grams	
				if (strpos("Gram", $parts['quantitativeReference']['unit']['label']) !== false) {
					$ratio = $oldamount * 1000;
					$parts['quantitativeReference']['unit']['label'] = "Kilogram";
				}	
				// If ounces
				if ($parts['quantitativeReference']['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Ounce" ) {
					$ratio = $oldamount * 0.028345;
					$parts['quantitativeReference']['unit']['label'] = "Kilogram";
				}
				// If pounds
				if ($parts['quantitativeReference']['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Pound") {
					$ratio = $oldamount * 0.45359237;
					$parts['quantitativeReference']['unit']['label'] = "Kilogram";
				}
				if ($parts['quantitativeReference']['unit']['label'] == "Pound Mass") {
					$ratio = $oldamount * 0.45359237;
					$parts['quantitativeReference']['unit']['label'] = "Kilogram";
				}
				foreach ($parts['impactAssessments'] as &$impactAssessment) {
					$impactAssessment['amount'] = $impactAssessment['amount'] / $ratio;
					if ($impactAssessment['unit']['label'] == "Gram") { 
						$impactAssessment['amount']/=1000; 
						$impactAssessment['unit']['label'] = "Kilogram"; 
					}
					if ($impactAssessment['unit']['label'] == "Pound Mass") { 
						$impactAssessment['amount']*=0.45359237; 
						$impactAssessment['unit']['label'] = "Kilogram"; 
					}
					// Create an impact
					$data = array (
						'uri' => $r->uri,
						'amount' => $impactAssessment['amount'],
						'unit' => $impactAssessment['unit']['label'],
						'impact' => $impactAssessment['impactCategoryIndicator']['label'],
						);
					$this->db->insert('impacts', $data);
				}
				// Update the unit for the footprint
				$data2 = array (
					'unit' => $parts['quantitativeReference']['unit']['label']
				);
				$this->db->where('uri', $uri);
				$this->db->update('footprints', $data2);
			}
		}
					
		public function cacheCategories(){
			$rs = $this->db->get('footprints');		
			// Initializing array
			foreach ($rs->result() as $r) {
				$uri = str_replace("http://footprinted.org/rdfspace/lca/", "",$r->uri); 
				$longuri = "http://footprinted.org/rdfspace/lca/" . $uri;
				$categoryOf = $this->lcamodel->getCategories($longuri);
				$category= "";
				if($categoryOf != false){
					foreach ($categoryOf as $c) {
						$category .= $c['uri'] . ';';
					}
				}
				$data = array (
					'category' => $category
				);
				$this->db->where('uri', $uri);
				$this->db->update('footprints', $data);
			}
		}
		
		public function cacheEverything(){ 
			// Querying the database for all records		
			$records = $this->lcamodel->getRecords();
			// Initializing array
			foreach ($records as $r) {
					$uri = str_replace("http://footprinted.org/rdfspace/lca/", "",$r['uri']); 
					$longuri = "http://footprinted.org/rdfspace/lca/" . $uri;
					$impactAssessments = $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments($longuri));
					$bibliography = $this->bibliographymodel->convertBibliography($this->bibliographymodel->getBibliography($longuri));
					$geography = $this->lcamodel->convertGeography($this->lcamodel->getGeography($longuri));
					$quantitativeReference = $this->lcamodel->convertQR($this->lcamodel->getQR($longuri));
					$categoryOf = $this->lcamodel->getCategories($longuri);

					// Get the year
					$year= "";
					foreach ($bibliography as $b) { 
						$year = substr_replace($b['date'], '', 4); 
						$ref = $b["title"] . "Authors: ";
						if (isset($b['authors']) == true) {
							foreach ($b['authors'] as $author) {
								$ref .=  $author['lastName'] . ", " .$author['firstName'] . "; ";
							}
						}
					}
					$category= "";
					if($categoryOf != false){
						foreach ($categoryOf as $c) {
							$category = $c['label'];
						}
					}
					// Get the country
					$country= "";
					if($geography != false){
						foreach ($geography as $g) { $country = $g['name']; }
					}					
					/* Normalize to 1 */
					$ratio = $quantitativeReference['amount'];					
					foreach ($impactAssessments as $impact) {
						if($impact['impactCategoryIndicator']['label'] == "Carbon Dioxide Equivalent"){
							//Normalize to one
							$co2 = $impact['amount'] / $ratio;
							$unit = $quantitativeReference['unit']['label'];
							//Change unit
							if (strpos("Gram", $impact['unit']['label']) !== false) {
								$co2 = $co2*1000;
							}
							if ($impact['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Pound" ) {
								$co2 = $co2*0.45359237;
							}
							if (strpos("Gram", $quantitativeReference['unit']['label']) !== false) {
								$unit = "Kilogram";
								$co2 = $co2/1000;
							}
							if (strpos("Kilogram", $quantitativeReference['unit']['label']) !== false) {
								$unit = "Kilogram";
							}
							if ($quantitativeReference['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Ounce" ) {
								$unit = "Kilogram";
								$co2 = $co2/0.028345;
							}
							if ($quantitativeReference['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Pound" ) {
								$unit = "Kilogram";
								$co2 = $co2/0.45359237;
							}
						}
						
						if($impact['impactCategoryIndicator']['label'] == "Water"){
							//Normalize to one
							$water = $impact['amount'] / $ratio;
							$unit = $quantitativeReference['unit']['label'];
							//Change unit
							if (strpos("Gram", $quantitativeReference['unit']['label']) !== false) {
								$unit = "Kilogram";
								$water = $water/1000;
							}
							if (strpos("Kilogram", $quantitativeReference['unit']['label']) !== false) {
								$unit = "Kilogram";
							}
							if ($quantitativeReference['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Ounce" ) {
								$unit = "Kilogram";
								$water = $water/0.028345;
							}
							if ($quantitativeReference['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Pound" ) {
								$unit = "Kilogram";
								$water = $water/0.45359237;
							}
						}						
						
						if($impact['impactCategoryIndicator']['label'] == "Energy"){
							//Normalize to one
							$energy = $impact['amount'] / $ratio;
							$unit = $quantitativeReference['unit']['label'];
							//Change unit
							if (strpos("Gram", $quantitativeReference['unit']['label']) !== false) {
								$unit = "Kilogram";
								$energy = $energy/1000;
							}
							if (strpos("Kilogram", $quantitativeReference['unit']['label']) !== false) {
								$unit = "Kilogram";
							}
							if ($quantitativeReference['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Ounce" ) {
								$unit = "Kilogram";
								$energy = $energy/0.028345;
							}
							if ($quantitativeReference['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Pound" ) {
								$unit = "Kilogram";
								$energy = $energy/0.45359237;
							}
						}
						
						if($impact['impactCategoryIndicator']['label'] == "Waste"){
							//Normalize to one
							$waste = $impact['amount'] / $ratio;
							$unit = $quantitativeReference['unit']['label'];
							//Change unit
							if (strpos("Gram", $quantitativeReference['unit']['label']) !== false) {
								$unit = "Kilogram";
								$waste = $waste/1000;
							}
							if (strpos("Kilogram", $quantitativeReference['unit']['label']) !== false) {
								$unit = "Kilogram";
							}
							if ($quantitativeReference['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Ounce" ) {
								$unit = "Kilogram";
								$waste = $waste/0.028345;
							}
							if ($quantitativeReference['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Pound" ) {
								$unit = "Kilogram";
								$waste = $waste/0.45359237;
							}
						}
					}

					$data = array (
						'uri' => $uri,
						'name' => $quantitativeReference['name'],
						'unit' => $unit,
						'country' => $country,
						'year' => $year,
						'co2e' => $co2,						
						'water' => $water,
						'waste' => $waste,
						'energy' => $energy,
						'category' => $category,
						'ref' => $ref
						);
					$this->db->insert('footprints', $data); 
				}


		}


} // End Class