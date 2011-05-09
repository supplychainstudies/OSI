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



class Lca extends SM_Controller {
	public function Lca() {
		parent::SM_Controller();
		$this->load->model(Array('lcamodel', 'geographymodel', 'bibliographymodel','peoplemodel','commentsmodel','ecomodel','arcmodel'));	
		$this->load->library(Array('form_extended', 'xml'));
		$this->load->helper(Array('nameformat_helper'));
		$obj =& get_instance();    
        $obj->load->library(array('xml'));
        $this->ci =& $obj;
	}
	public $URI;
	public $data;
	public $post_data;
	public $tooltips = array();
	
	public function index() {
		$data = $this->form_extended->load("start"); 
		$the_form = $this->form_extended->build();
		$this->style(Array('style.css', 'form.css'));
		$this->script(Array('form.js', 'toggle.js', 'lookup.js'));
		$this->data("view_string", $the_form);
		$this->display("Form", "view");
	}

	public function create() {
		/***
	    * @public
	    * Generates a form, or, in the case where post data is passed, submits the data to the DB
	    */

		if ($post_data = $_POST) {	
			$model_node = toURI("lca", $post_data['name_']); 
			$exchange_node = $model_node;
			$bibliography_node = toURI("bibliography", $post_data['title_']); 
			$person_node = toURI("person", $post_data['author_']); 
			$process_node = $model_node;
			$product_node = $model_node;
			$impactAssessment_node = "";
			
			// Bibliography
			// First, look to see if they picked the first author, or if its someone new
			if ($post_data['author_'] != "") {
				$person_node = $post_data['author_'];
			} else {
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
				if ($post_data['email_'] != "") {
					$datasets['person'][] = array (
							'firstName_' => $post_data['firstName_'],
							'lastName_' => $post_data['lastName_'],
							'email_' => $post_data['email_']
						);	
				}
			}

			if ($post_data["title_"] != "" || $post_data["link_"] != "") {
			$datasets['bibliography'][] = array (
					"title_" => $post_data["title_"],
					"uri_" => $post_data["link_"],
					"author_" => array($person_node)
				);
			}

			$datasets['process'][] = array (
					'name_' => $post_data['name_'],
					'description_' => $post_data['description_']	
				);
			$datasets['product'][] = array (
					'name_' => $post_data['productServiceName_']
				);
		
			$datasets['exchange'][] = array (
					"direction_" => 'eco_Output',
					"exchange_" => 'eco_Transfer',
					"transferable_" => $post_data['productServiceName_'],
					"quantity_" => $post_data['qrQuantity_'],
					"unit_" => $post_data['qrUnit_']
				);
			$change_p['exchange'][] = array('Exchange'=>'eco:hasReferenceExchange');
			for ($i = 0; $i< count($post_data['io_']); $i++) {
				$datasets['exchange'][] = array (
						"direction_" => $post_data['io_'][$i],
						"exchange_" => $post_data['exchangeType_'][$i],
						"transferable_" => $post_data['substanceName_'][$i],
						"quantity_" => $post_data['ioQuantity_'][$i],
						"unit_" => $post_data['ioUnit_'][$i]
					);
			}

			$datasets['impactAssessment'][] = array (
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
				if ($triple['predicate'] == 'eco:hasTransferable' && $triple['object'] == $post_data['productServiceName_']) {
					$triple['object'] = $_product_node;
				}
				if ($triple['predicate'] == 'eco:hasTransferable' && $triple['object'] == $post_data['productServiceName_']) {
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
			$this->arcmodel->addTriples($triples);
			$this->view(str_replace("http://footprinted.org/rdfspace/lca/","",$model_node));
		}else {
			$this->index();
		}
	}



	/***
	* @public
	* Grabs all the triples for a particular URI and shows it in RDF
	*/
	public function viewRDF($URI = null) {
		@$rdf = $this->arcmodel->getRDF("http://footprinted.org/rdfspace/lca/".$URI);
		header("Content-Disposition: attachment; filename=\"$URI.rdf\"");
		header('Content-type: text/xml');
		echo $rdf;
	}	


	/***
	* @public
	* Grabs all the triples for a particular URI and shows it in JSON
	*/	
	public function viewJSON($URI = null) {
		@$json = $this->arcmodel->getJSON("http://footprinted.org/rdfspace/lca/".$URI);
		header('Content-type: application/json');
		echo $json;
	}
	
	
	/***
	* @private
	* Builds the tooltip array from linked data
	*/


	/***
	* @public
	* Grabs all the triples for a particular URI and shows it in a friendly, human readable way
	*/
	public function view($URI = null) {	
		@$parts['impactAssessments'] = $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments("http://footprinted.org/rdfspace/lca/" . $URI), &$this->tooltips);
	
		@$parts['bibliography'] = $this->bibliographymodel->convertBibliography($this->bibliographymodel->getBibliography("http://footprinted.org/rdfspace/lca/" . $URI), &$this->tooltips);
		@$parts['exchanges'] = $this->lcamodel->convertExchanges($this->lcamodel->getExchanges("http://footprinted.org/rdfspace/lca/" . $URI), &$this->tooltips);	
		@$parts['modeled'] = $this->lcamodel->convertModeled($this->lcamodel->getModeled("http://footprinted.org/rdfspace/lca/" . $URI), &$this->tooltips);
		$parts['geography'] = $this->lcamodel->convertGeography($this->lcamodel->getGeography("http://footprinted.org/rdfspace/lca/" . $URI), &$this->tooltips);
		@$parts['quantitativeReference'] = $this->lcamodel->convertQR($this->lcamodel->getQR("http://footprinted.org/rdfspace/lca/" . $URI), &$this->tooltips);

		$parts['tooltips'] = $this->tooltips;

	 	foreach ($parts as &$part) {
			if ($part == false || count($part) == 0) {
				unset($part);
			}
		}

		/* If the functional unit is mass, normalize to 1kg */
		
		if (strpos("Kilogram", $parts['quantitativeReference']['unit']) !== false) {
			$ratio = $parts['quantitativeReference']['amount'];
			$parts['quantitativeReference']['amount'] = 1;
			foreach ($parts['exchanges'] as &$exchanges) {
				$exchanges['amount'] = $exchanges['amount'] / $ratio;
			}
			foreach ($parts['impactAssessments'] as &$impactAssessment) {
				$impactAssessment['amount'] = $impactAssessment['amount'] / $ratio;
			}
		}
		if (strpos("Gram", $parts['quantitativeReference']['unit']) !== false) {
			$ratio = $parts['quantitativeReference']['amount'] / 1000;
			$parts['quantitativeReference']['unit'] = "Kilogram";
			$parts['quantitativeReference']['amount'] = 1;
			foreach ($parts['exchanges'] as &$exchanges) {
				$exchanges['amount'] = $exchanges['amount'] / $ratio;
				if ($exchanges['unit'] == "qudtu:Gram") { $exchanges['amount']/=1000; //$exchanges['unit'] = "qudtu:Kilogram";
				}
			}
			foreach ($parts['impactAssessments'] as &$impactAssessment) {
				$impactAssessment['amount'] = $impactAssessment['amount'] / $ratio;
				if ($impactAssessment['unit'] == "qudtu:Gram") { $impactAssessment['amount']/=1000; $impactAssessment['unit'] = "qudtu:Kilogram"; }
			}
		} 
		/*
		$parts['tooltips']["qudtu:Kilogram"]["label"] = "Kilogram";
		$parts['tooltips']["qudtu:Kilogram"]["abbr"] = "Kg";
		$parts['tooltips']["qudtu:Kilogram"]["l"]= "Kg";
		$parts['tooltips']["qudtu:Kilogram"]["quantityKind"] = "Mass";
		*/
		var_dump($parts['tooltips']);
		foreach ($parts['exchanges'] as $exchange) {
			var_dump($exchange);
			if (isset($parts['tooltips'][$exchange['unit']]) == true) {
				$parts[$exchange['direction']][$parts['tooltips'][$exchange['unit']]['quantityKind']][] = $exchange;
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
				
		$links = '<p><a href="/'.$URI.'.rdf">Get this RDF</a></p><p><a href="/'.$URI.'.json">Get this in JSON</a></p>';
		$this->data("links", $links);
		$this->data("URI", $URI);
		$this->data("parts", $parts);
		$this->data("totalinput", $totalinput);
		$this->data("totaloutput", $totaloutput);
		$this->data("totalinputliter", $totalinputliter);
		$this->data("totalinputland", $totalinputland);			
		$this->script(Array('comments.js', 'janrain.js'));
		$comment_data = $this->form_extended->load('comment');
		$comment = $this->form_extended->build();
		$comments = $this->commentsmodel->getComments("http://footprinted.org/osi/rdfspace/lca/".$URI);
		$this->data("comments", $comments);
		$this->data("comment", $comment);
		$this->display("View " . $parts['quantitativeReference']['amount'] . " " . $parts['quantitativeReference']['unit'] . " of " . $parts['quantitativeReference']['name'], "viewLCA");		
	}


	
	
	/***
	* @private
	* Does stuff
	*/
	
	


} // End Class