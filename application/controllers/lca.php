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
	public function Lca() {
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
			$model_node = toURI("lca", $post_data['name_']); 
			$bibliography_node = toURI("bibliography", $post_data['title_']); 
			$person_node = toURI("person", $post_data['author_']); 
			$process_node = toBNode("process");
			$product_node = toBNode("product");
			
			var_dump($model_node);
var_dump($bibliography_node);
var_dump($person_node);
var_dump($product_node);
			// Bibliography
			// First, look to see if they picked the first author, or if its someone new
			if ($post_data['author_'] != "") {
				$person_node = $post_data['author_'];
			} else {
				if (strpos($post_data['author_label_'], ",") !== false) {
					$name_array = explode (",", $post_data['author_']);
					$post_data['firstName_'] = trim($name_array[1]);
					$post_data['lastName_'] = trim($name_array[0]);
				} elseif (strpos($post_data['author_'], " ") !== false) {
					$name_array = explode(" ", $post_data['author_']);
					$post_data['firstName_'] = trim($name_array[0]);
					$post_data['lastName_'] = trim($name_array[1]);
				} elseif ($post_data['author_label_'] == "") {
					$post_data['firstName_'] = "";
					$post_data['lastName_'] = "";
				} else {
					$post_data['firstName_'] = "";
					$post_data['lastName_'] = $post_data['author_'];
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
					"link_" => $post_data["link_"],
					"author_[0]" => $person_node
				);
			}

			$datasets['process'][] = array (
					'name_' => $post_data['name_'],
					'description_' => $post_data['description_']	
				);
			$datasets['product'][] = array (
					'name_' => $post_data['productServiceName_']
				);
		

				$exchange_node = array();
			for ($i = 0; $i< count($post_data['io_']); $i++) {
				$exchange_node[] = toBNode("exchange");
				$datasets['exchange'][] = array (
						"direction_" => $post_data['io_'][$i],
						"exchange_" => $post_data['exchangeType_'][$i],
						"name_" => $post_data['substanceName_'][$i],
						"quantity_" => $post_data['ioQuantity_'][$i],
						"unit_" => $post_data['ioUnit_'][$i]
					);
			}

			$impactAssessment_node = array();
			for ($i = 0; $i< count($post_data['impactCategory_']); $i++) {
				$impactAssessment_node[] = toBNode("impactassessment");
				$datasets['impactAssessment'][] = array (
						"computedFrom_" => $model_node,
						"assessmentOf_" => $process_node,
						"impactCategory_" => $post_data['impactCategory_'][$i],
						"impactCategoryIndicator_" => $post_data['impactCategoryIndicator_'][$i],
						"quantity_" => $post_data['assessmentQuantity_'][$i],
						"unit_" => $post_data['assessmentUnit_'][$i]
					);
			}	

			$triples = array();
			foreach ($datasets as $key=>$dataset) {
				if ($key != "submit_") {
				//if (isset($dataset[0]) == true) {
					foreach ($dataset as $i=>$datasetinstance) {
						$node_name = $key."_node";
						$node_name_array = $$node_name;
						$node = $node_name_array[$i];

						$data = $this->form_extended->load($key); 						
						$triples = array_merge($triples,$this->form_extended->build_triples("", $datasetinstance, $data));
					}
			
				} 

			}
			var_dump($triples);
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
		@$parts['impactAssessments'] = $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments("http://footprinted.org/rdfspace/lca/" . $URI), $this->tooltips);
	
		@$parts['bibliography'] = $this->bibliographymodel->convertBibliography($this->bibliographymodel->getBibliography("http://footprinted.org/rdfspace/lca/" . $URI), $this->tooltips);
		@$parts['exchanges'] = $this->lcamodel->convertExchanges($this->lcamodel->getExchanges("http://footprinted.org/rdfspace/lca/" . $URI), $this->tooltips);	
		@$parts['modeled'] = $this->lcamodel->convertModeled($this->lcamodel->getModeled("http://footprinted.org/rdfspace/lca/" . $URI), $this->tooltips);
		$parts['geography'] = $this->lcamodel->convertGeography($this->lcamodel->getGeography("http://footprinted.org/rdfspace/lca/" . $URI), $this->tooltips);
		@$parts['quantitativeReference'] = $this->lcamodel->convertQR($this->lcamodel->getQR("http://footprinted.org/rdfspace/lca/" . $URI), $this->tooltips);

		$parts['tooltips'] = $this->tooltips;

	 	foreach ($parts as &$part) {
			if ($part == false || count($part) == 0) {
				unset($part);
			}
		}
	
		/* If the functional unit is mass, normalize to 1kg */
		
		if ($parts['quantitativeReference']['unit'] == "qudtu:Kilogram") {
			$ratio = $parts['quantitativeReference']['amount'];
			$parts['quantitativeReference']['amount'] = 1;
			foreach ($parts['exchanges'] as &$exchanges) {
				$exchanges['amount'] = $exchanges['amount'] / $ratio;
			}
			foreach ($parts['impactAssessments'] as &$impactAssessment) {
				$impactAssessment['amount'] = $impactAssessment['amount'] / $ratio;
			}
		}
		if ($parts['quantitativeReference']['unit'] == "qudtu:Gram") {
			$ratio = $parts['quantitativeReference']['amount'] / 1000;
			$parts['quantitativeReference']['unit'] = "qudtu:Kilogram";
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
		$parts['tooltips']["qudtu:Kilogram"]["label"] = "Kilogram";
		$parts['tooltips']["qudtu:Kilogram"]["abbr"] = "Kg";
		$parts['tooltips']["qudtu:Kilogram"]["l"]= "Kg";
		$parts['tooltips']["qudtu:Kilogram"]["quantityKind"] = "Mass";
		foreach ($parts['exchanges'] as $exchange) {
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
			
			$this->tooltips["qudtu:Kilogram"]["label"] = "Kilogram";
			$this->tooltips["qudtu:Kilogram"]["abbr"] = "Kg";
			$this->tooltips["qudtu:Kilogram"]["l"]= "Kg";
			$this->tooltips["qudtu:Kilogram"]["quantityKind"] = "Mass";


			$feature_info = array (
		            'uri' => $URI,
		            'impactAssessments' => $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments("http://footprinted.org/rdfspace/lca/" . $URI),$this->tooltips),
		    		'quantitativeReference' => $this->lcamodel->convertQR($this->lcamodel->getQR("http://footprinted.org/rdfspace/lca/" . $URI),$this->tooltips)
		    );
			if ($feature_info['quantitativeReference']['unit'] == "qudtu:Kilogram") {
				$ratio = $feature_info['quantitativeReference']['amount'];
				$feature_info['quantitativeReference']['amount'] = 1;
				foreach ($feature_info['impactAssessments'] as &$impactAssessment) {
					$impactAssessment['amount'] = $impactAssessment['amount'] / $ratio;
				}
			}
			
			@$parts['tooltips'] = $this->tooltips;
			
			$text = '<p>Footprint of one kilogram of '.$feature_info['quantitativeReference']['name'].'</p>';
			$text .= '<div id="tabs"><ul>';
			
			foreach ($feature_info['impactAssessments'] as $impactAssessment) {
				switch ($impactAssessment['impactCategoryIndicator']) {
				    case 'ossia:waste': $sign = "W"; break;
				    case 'ossia:CO2e': $sign = "CO2";	break;
					case 'ossia:C02e': $sign = "CO2";	break;
				    case "ossia:energy": $sign = "E"; break;
					case "ossia:water":$sign = "H20"; break;
				}
				
				$text .= '<li><a href="#'.$impactAssessment['impactCategoryIndicator'].'">'.$sign.'</a></li>';
			}
			
			$text .= '</ul>';
			
			foreach ($feature_info['impactAssessments'] as $impactAssessment) {
				if($impactAssessment['impactCategoryIndicator'] != ""){
				$text .= '<div class="tabinside" id="'.$impactAssessment['impactCategoryIndicator'].'"><div class="nr"><h1 class="nr">' . round($impactAssessment['amount'],2) . '</h1></div><div class="meta"><p class="unit">'. linkThis($impactAssessment['unit'], $parts["tooltips"], "label") .'</p></div></div>';
				}
			}
			
			$text .= '</div>';	
			$text .= "<br/><a href='/lca/view/".$URI."'>More info >> </a>";
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
		    		'quantitativeReference' => $this->lcamodel->convertQR(@$this->lcamodel->getQR("http://footprinted.org/rdfspace/lca/" . $URI),$this->tooltips)
		    );
			$text = '<p>'.$feature_info['quantitativeReference']['name'].'</p>';
			echo $text;
		}	
				
} // End Class