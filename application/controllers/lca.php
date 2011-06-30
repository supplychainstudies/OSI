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
    * @public (For logged in users)
    * Generates a form, or, in the case where post data is passed, submits the data to the DB
    */
	public function create() {
		$this->check_if_logged_in();
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
	public function viewEcoRDF($URI = null) {
		$parts['uri'] = $URI;
		$parts['title'] = $this->lcamodel->getTitle("http://footprinted.org/rdfspace/lca/" . $URI);
		$parts['impactAssessments'] = $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments("http://footprinted.org/rdfspace/lca/" . $URI));
		$parts['bibliography'] = $this->bibliographymodel->convertBibliography($this->bibliographymodel->getBibliography("http://footprinted.org/rdfspace/lca/" . $URI));
		$parts['exchanges'] = $this->lcamodel->convertExchanges($this->lcamodel->getExchanges("http://footprinted.org/rdfspace/lca/" . $URI));	
		$parts['modeled'] = $this->lcamodel->convertModeled($this->lcamodel->getModeled("http://footprinted.org/rdfspace/lca/" . $URI));
		$parts['geography'] = $this->lcamodel->convertGeography($this->lcamodel->getGeography("http://footprinted.org/rdfspace/lca/" . $URI));
		$parts['quantitativeReference'] = $this->lcamodel->convertQR($this->lcamodel->getQR("http://footprinted.org/rdfspace/lca/" . $URI));
		$parts['sameAs'] = $this->lcamodel->convertLinks($this->lcamodel->getSameAs("http://footprinted.org/rdfspace/lca/" . $URI));
		$parts['categoryOf'] = $this->lcamodel->getCategories("http://footprinted.org/rdfspace/lca/" . $URI);
		header("Content-Disposition: attachment; filename=\"$URI.rdf\"");
		header('Content-type: text/xml');
		$this->normalize($parts);
		var_dump($parts);
	}	
	public function viewRDF($URI = null) {
		$rdf = $this->lcamodel->getRDF("http://footprinted.org/rdfspace/lca/".$URI);
		header("Content-Disposition: attachment; filename=\"$URI.rdf\"");
		header('Content-type: text/xml');
		var_dump($rdf);
	}	

	/***
	* @public
	* Grabs all the triples for a particular URI and shows it in JSON
	*/	
	public function viewJSON($URI = null) {
		$parts['uri'] = $URI;
		$parts['title'] = $this->lcamodel->getTitle("http://footprinted.org/rdfspace/lca/" . $URI);
		$parts['impactAssessments'] = $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments("http://footprinted.org/rdfspace/lca/" . $URI));
		$parts['bibliography'] = $this->bibliographymodel->convertBibliography($this->bibliographymodel->getBibliography("http://footprinted.org/rdfspace/lca/" . $URI));
		$parts['exchanges'] = $this->lcamodel->convertExchanges($this->lcamodel->getExchanges("http://footprinted.org/rdfspace/lca/" . $URI));	
		$parts['modeled'] = $this->lcamodel->convertModeled($this->lcamodel->getModeled("http://footprinted.org/rdfspace/lca/" . $URI));
		$parts['geography'] = $this->lcamodel->convertGeography($this->lcamodel->getGeography("http://footprinted.org/rdfspace/lca/" . $URI));
		$parts['quantitativeReference'] = $this->lcamodel->convertQR($this->lcamodel->getQR("http://footprinted.org/rdfspace/lca/" . $URI));
		$parts['sameAs'] = $this->lcamodel->convertLinks($this->lcamodel->getSameAs("http://footprinted.org/rdfspace/lca/" . $URI));
		$parts['categoryOf'] = $this->lcamodel->getCategories("http://footprinted.org/rdfspace/lca/" . $URI);
		header('Content-type: application/json');
		$this->normalize($parts);
		var_dump($parts);
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
		$this->normalize($parts);
		
		$parts['year']= "";
		foreach ($parts['bibliography'] as $b) { 
			$parts['year'] = substr_replace($b['date'], '', 4); 
			$parts['ref'] = $b["title"] . "Authors: ";
			if (isset($b['authors']) == true) {
				foreach ($b['authors'] as $author) {
					$parts['ref'] .=  $author['lastName'] . ", " .$author['firstName'] . "; ";
				}
			}
		}
		
		
		// Turns exchanges into input and output array divided into categories 
		if (isset($parts['exchanges']) == true) {
			foreach ($parts['exchanges'] as $exchange) {
				if ($exchange['unit']['quantityKind'] != "") {
					$parts[$exchange['direction']][$exchange['unit']['quantityKind']][] = $exchange;				
				} else {
					$parts[$exchange['direction']]['Misc'][] = $exchange;
				}
			}
			/* Crunches the data to create the graphics and total calculations */
			$totalinput = 0; 
			if (isset($parts['Input']["Mass"]) == true) { 
			foreach ($parts['Input']["Mass"] as $i) {
				$totalinput += $i['amount'];
			}}
			$misctotal = 0; 
			if (isset($parts['Input']["Misc"]) == true) { 
			foreach ($parts['Input']["Misc"] as $i) {
				$misctotal += $i['amount'];
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
			$this->data("misctotal", $misctotal);		
		}	
		$this->script(Array('comments.js', 'janrain.js'));
		$comment_data = $this->form_extended->load('comment');
		$comment = $this->form_extended->build();
		$comments = $this->commentsmodel->getComments("http://footprinted.org/osi/rdfspace/lca/".$URI);
		$this->data("comments", $comments);
		$this->data("comment", $comment);
		$this->display("View " . $parts['quantitativeReference']['amount'] . " " . $parts['quantitativeReference']['unit'] . " of " .  $parts['quantitativeReference']['name'], "viewLCA");		
	}

	/*
	Private function that normalizes to 1 functional unit and to kilograms if possible
	*/
	private function normalize(&$parts){
		/* Normalize to 1 */
		$oldamount = $parts['quantitativeReference']['amount'];
		$ratio = $parts['quantitativeReference']['amount'];
		$parts['quantitativeReference']['amount'] = 1;
		// If grams	
		if (strpos("Gram", $parts['quantitativeReference']['unit']['label']) !== false) {
			$ratio = $oldamount / 1000;
			$parts['quantitativeReference']['unit']['label'] = "Kilogram"; $parts['quantitativeReference']['unit']['abbr'] = "kg";
		}	
		// If ounces
		if ($parts['quantitativeReference']['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Ounce" ) {
			$ratio = $oldamount * 0.028345;
			$parts['quantitativeReference']['unit']['label'] = "Kilogram"; $parts['quantitativeReference']['unit']['abbr'] = "kg";
		}
		// If pounds
		if ($parts['quantitativeReference']['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Pound") {
			$ratio = $oldamount * 0.45359237;
			$parts['quantitativeReference']['unit']['label'] = "Kilogram";$parts['quantitativeReference']['unit']['abbr'] = "kg";
		}
		if ($parts['quantitativeReference']['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Pound") {
			$ratio = $oldamount * 0.45359237;
			$parts['quantitativeReference']['unit']['label'] = "Kilogram";$parts['quantitativeReference']['unit']['abbr'] = "kg";
		}
		if ($parts['quantitativeReference']['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#TableSpoon") {
			$ratio = $oldamount * 0.0147867648;
			$parts['quantitativeReference']['unit']['label'] = "Liter";$parts['quantitativeReference']['unit']['abbr'] = "L";
		}
		if ($parts['quantitativeReference']['unit']['label'] == "Ton Mile") {
			$ratio = $oldamount * 1.609344;
			$parts['quantitativeReference']['unit']['label'] = "Liter";$parts['quantitativeReference']['unit']['abbr'] = "Ton Km";
		}
		if ($parts['quantitativeReference']['unit']['label'] == "Per Person Per Mile") {
			$ratio = $oldamount * 1.609344;
			$parts['quantitativeReference']['unit']['label'] = "Liter";$parts['quantitativeReference']['unit']['abbr'] = "Person Km";
		}
		// Normalizes the flows
		if (isset($parts['exchanges']) == true) {
			foreach ($parts['exchanges'] as &$exchanges) {
				$exchanges['amount'] = $exchanges['amount'] / $ratio;
				if ($exchanges['unit']['label'] == "Gram") {
					$exchanges['amount']/=1000; $exchanges['unit']['label'] = "Kilogram"; $exchanges['unit']['abbr'] = "kg";
				}
				if ($exchanges['unit']['label'] == "Pound Mass") {
					$exchanges['amount'] = $exchanges['amount'] * 0.45359237; 
					$exchanges['unit']['label'] = "Kilogram"; $exchanges['unit']['abbr'] = "kg";
				}
			}
		}
		// Normalizes the impacts
		if (isset($parts['impactAssessments']) == true) {
			foreach ($parts['impactAssessments'] as &$impactAssessment) {
				$impactAssessment['amount'] = $impactAssessment['amount'] / $ratio;
				if ($impactAssessment['unit']['label'] == "Gram") { 
					$impactAssessment['amount']/=1000; 
					$impactAssessment['unit']['label'] = "Kilogram"; $impactAssessment['unit']['abbr'] = "kg";
				}
				if ($impactAssessment['unit']['label'] == "Pound Mass") { 
					$impactAssessment['amount']*=0.45359237; 
					$impactAssessment['unit']['label'] = "Kilogram"; $impactAssessment['unit']['abbr'] = "kg";
				}
			}
		}
	}
		
		/***
	    * @public
	    * Shows the homepage
		* This is not functional for non-LCA entries and does not have search or filter capabilities yet
		* Public function for exploring the repository
	    */
		public function featured() {
			// Querying the database for all featured URIs		
			$this->db->select('uri');
			$this->db->order_by("uri", "ASC"); 
			$featured = $this->db->get('featured');
			
			
			$all = $this->db->get('footprints');
			$nr = count($all->result()); 
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
			$this->data("nr", $nr);
			$this->display("Browse","homepage_view");		
		}
		
				
		private function addSameAs() {
			parse_str($_SERVER['QUERY_STRING'],$_GET); 
			$ids = $_GET;
			$this->lcamodel->addSameAs($ids['ft_id'],$ids['opencyc_id']);
			
		}
		private function addDbpedia() {
			parse_str($_SERVER['QUERY_STRING'],$_GET); 
			$ids = $_GET;
			$this->lcamodel->addDbpedia($ids['ft_id'],$ids['db_id']);
			
		}
		private function addCategory() {
			parse_str($_SERVER['QUERY_STRING'],$_GET); 
			$ids = $_GET;
			$this->lcamodel->addCategory($ids['ft_id'],$ids['opencyc_id']);
			
		}
		
}