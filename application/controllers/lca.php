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
			$this->index();
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
	* @private
	* Builds the tooltip array from linked data
	*/


	/***
	* @public
	* Grabs all the triples for a particular URI and shows it in a friendly, human readable way
	*/
	public function view($URI = null) {	
		$parts['uri'] = $URI;
		$parts['impactAssessments'] = $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments("http://footprinted.org/" . $URI . ".rdf"));
		$parts['bibliography'] = $this->bibliographymodel->convertBibliography($this->bibliographymodel->getBibliography("http://footprinted.org/" . $URI . ".rdf"));
		$parts['exchanges'] = $this->lcamodel->convertExchanges($this->lcamodel->getExchanges("http://footprinted.org/" . $URI . ".rdf"));	
		$parts['modeled'] = $this->lcamodel->convertModeled($this->lcamodel->getModeled("http://footprinted.org/" . $URI . ".rdf"));
		$parts['geography'] = $this->lcamodel->convertGeography($this->lcamodel->getGeography("http://footprinted.org/" . $URI . ".rdf"));
		$parts['quantitativeReference'] = $this->lcamodel->convertQR($this->lcamodel->getQR("http://footprinted.org/" . $URI . ".rdf"));
		$parts['sameAs'] = $this->lcamodel->convertLinks($this->lcamodel->getSameAs("http://footprinted.org/" . $URI . ".rdf"));
		$parts['categoryOf'] = $this->lcamodel->getCategories("http://footprinted.org/" . $URI . ".rdf");
		//$parts['suggestions'] = $this->lcamodel->getOpenCycSuggestions("http://footprinted.org/rdfspace/lca/" . $URI);
	 	foreach ($parts as $key=>$part) {
			if ($parts[$key] === false || $parts[$key] == false || count($parts[$key]) == 0) {
				unset($parts[$key]);
			}
		}

		/* If the functional unit is mass, normalize to 1kg */
		
		if (strpos("Kilogram", $parts['quantitativeReference']['unit']['label']) !== false) {
			$ratio = $parts['quantitativeReference']['amount'];
			$parts['quantitativeReference']['amount'] = 1;
			foreach ($parts['exchanges'] as &$exchanges) {
				$exchanges['amount'] = $exchanges['amount'] / $ratio;
			}
			foreach ($parts['impactAssessments'] as &$impactAssessment) {
				$impactAssessment['amount'] = $impactAssessment['amount'] / $ratio;
			}
		}
		if (strpos("Gram", $parts['quantitativeReference']['unit']['label']) !== false) {
			$ratio = $parts['quantitativeReference']['amount'] / 1000;
			// Remember to complete afterwards
			$parts['quantitativeReference']['unit']['label'] = "Kilogram";
			$parts['quantitativeReference']['amount'] = 1;
			foreach ($parts['exchanges'] as &$exchanges) {
				$exchanges['amount'] = $exchanges['amount'] / $ratio;
				if (strpos("Gram",$exchanges['unit']['label']) !== false) { $exchanges['amount']/=1000; //$exchanges['unit'] = "qudtu:Kilogram";
				}
			}
			foreach ($parts['impactAssessments'] as &$impactAssessment) {
				$impactAssessment['amount'] = $impactAssessment['amount'] / $ratio;
				if (strpos("Gram", $impactAssessment['unit']['label']) !== false) { $impactAssessment['amount']/=1000; $impactAssessment['unit']['label'] = "Kilogram"; }
			}
		}
		// Turns exchanges into input and output array divided into categories 

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
	
	/*
	Function that given a URI for a resource provides an html for the environmental impacts. 
	Function used for the homepage presentation.
	*/	
	public function getImpacts($URI = null) {
			error_reporting(E_PARSE);  
			
			$feature_info = array (
		            'uri' => $URI,
		            'impactAssessments' => $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments("http://footprinted.org/rdfspace/lca/" . $URI)),
		    		'quantitativeReference' => $this->lcamodel->convertQR($this->lcamodel->getQR("http://footprinted.org/rdfspace/lca/" . $URI))
		    );
			if ($feature_info['quantitativeReference']['unit'] == "qudtu:Kilogram") {
				$ratio = $feature_info['quantitativeReference']['amount'];
				$feature_info['quantitativeReference']['amount'] = 1;
				foreach ($feature_info['impactAssessments'] as &$impactAssessment) {
					$impactAssessment['amount'] = round(($impactAssessment['amount'] / $ratio),2);
				}
			}
						
			$text = '<p>Footprint of one kilogram of '.$feature_info['quantitativeReference']['name'].'</p>';
			$text .= '<div id="tabs"><ul>';
			
			foreach ($feature_info['impactAssessments'] as $impactAssessment) {
				$text .= '<li><a href="#'.$impactAssessment['impactCategoryIndicator']['label'].'"><div style="width:8px; height:8px;margin-left:10px;margin-top: 0px; background:#fff; -moz-border-radius: 40px; -webkit-border-radius:40px;"></div></a></li>';
			}
			
			$text .= '</ul>';
			
			foreach ($feature_info['impactAssessments'] as $impactAssessment) {
				if($impactAssessment['impactCategoryIndicator'] != ""){
				$text .= '<div class="tabinside" id="'.$impactAssessment['impactCategoryIndicator']['label'].'">';
							
				$text .= '<div class="tab_nr"><h1><nrwhite>' . round($impactAssessment['amount'],2) ."</nrwhite> ". $impactAssessment['unit']["l"] . '</h1></div><div class="tab_meta"><p class="unit">'.$impactAssessment['impactCategoryIndicator'].'</p></div></div>';
				}
			}
			
			$text .= '</div>';	
			$text .= "<div class='plus'><a href='/lca/view/".$URI."'><img src='/assets/images/plus.png' height='15px'/></a></div>";
			$text .= '<script>	$(function() { $( "#tabs" ).tabs({ event: "mouseover"	});	});</script>';
			echo $text;
		}
		
		/*
		Function that given a URI for a resource provides its human readable name. 
		Used for the homepage presentation.
		*/	
		public function getName($URI = null) {
 			error_reporting(E_PARSE);    
			$feature_info = array (
		            'uri' => $URI,
		    		'quantitativeReference' => $this->lcamodel->convertQR(@$this->lcamodel->getQR("http://footprinted.org/rdfspace/lca/" . $URI))
		    );
			$text = '<p>'.$feature_info['quantitativeReference']['name'].'</p>';
			echo $text;
		}

		public function getCO2($URI = null) {
 			error_reporting(E_PARSE);    
			$feature_info = array (
		            'uri' => $URI,
		    		'quantitativeReference' => $this->lcamodel->convertQR(@$this->lcamodel->getQR("http://footprinted.org/rdfspace/lca/" . $URI),$this->tooltips)
		    );
			$text = '<p>'.$feature_info['quantitativeReference']['name'].'</p>';
			echo $text;
		}
		
		public function addSameAs() {
			parse_str($_SERVER['QUERY_STRING'],$_GET); 
			$ids = $_GET;
			$this->lcamodel->addSameAs($ids['ft_id'],$ids['opencyc_id']);
			
		}
		
		public function addCategory() {
			parse_str($_SERVER['QUERY_STRING'],$_GET); 
			$ids = $_GET;
			$this->lcamodel->addCategory($ids['ft_id'],$ids['opencyc_id']);
			
		}


} // End Class