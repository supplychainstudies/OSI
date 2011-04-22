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



class Lca extends SM_Controller {
	public function Lca() {
		parent::SM_Controller();
		$this->load->model(Array('arcmodel', 'arcremotemodel', 'mysqlmodel'));	
		$this->load->library(Array('form_extended', 'name_conversion', 'xml'));
		$obj =& get_instance();    
        $obj->load->library(array('xml'));
        $this->ci =& $obj;
	}
	public $URI;
	public $data;
	public $post_data;
	public $tooltips;
	
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
		$model_node = $this->name_conversion->toURI("lca", $post_data['name_']); 
		$bibliography_node = $this->name_conversion->toURI("bibliography", $post_data['title_']); 
		$person_node = $this->name_conversion->toURI("person", $post_data['author_']); 
		$process_node = $this->name_conversion->toBNode("process");
		$product_node = $this->name_conversion->toBNode("product");
		
		if (strpos($post_data['author_'], ",") !== false) {
			$name_array = explode (",", $post_data['author_']);
			$post_data['firstName_'] = trim($name_array[1]);
			$post_data['lastName_'] = trim($name_array[0]);
		} elseif (strpos($post_data['author_'], " ") !== false) {
			$name_array = explode(" ", $post_data['author_']);
			$post_data['firstName_'] = trim($name_array[0]);
			$post_data['lastName_'] = trim($name_array[1]);
		} elseif ($post_data['author_'] == "") {
			$post_data['firstName_'] = "";
			$post_data['lastName_'] = "";
		} else {
			$post_data['firstName_'] = "";
			$post_data['lastName_'] = $post_data['author_'];
		}
		if ($post_data['email_'] != "") {
			$datasets['person'] = array (
					'firstName_' => $post_data['firstName_'],
					'lastName_' => $post_data['lastName_'],
					'email_' => $post_data['email_']
				);	
		}
		
		if ($post_data["title_"] != "" || $post_data["link_"] != "") {
		$datasets['bibliography'] = array (
				"title_" => $post_data["title_"],
				"link_" => $post_data["link_"],
				"authorList_" => $person_node
			);
		}

		$datasets['process'] = array (
				'name_' => $post_data['name_'],
				'description_' => $post_data['description_']	
			);
		$datasets['product'] = array (
				'name_' => $post_data['productServiceName_']
			);
		
		$exchange_node = array();
		for ($i = 0; $i< count($post_data['io_']); $i++) {
			$exchange_node[] = $this->name_conversion->toBNode("exchange");
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
			$impactAssessment_node[] = $this->name_conversion->toBNode("impactassessment");
			$datasets['impactAssessment'][] = array (
					"computedFrom_" => $model_node,
					"assessmentOf_" => $process_node,
					"impactCategory_" => $post_data['impactCategory_'][$i],
					"impactIndicator_" => $post_data['impactIndicator_'][$i],
					"quantity_" => $post_data['assessmentQuantity_'][$i],
					"unit_" => $post_data['assessmentUnit_'][$i]
				);
		}	
		
		$triples = array();
		foreach ($datasets as $key=>$dataset) {
			if ($key != "submit_") {
			if (isset($dataset[0]) == true) {
				foreach ($dataset as $i=>$datasetinstance) {
					$node_name = $key."_node";
					$node_name_array = $$node_name;
					$node = $node_name_array[$i];
					$data = $this->form_extended->load($key); 
					$triples = array_merge($triples,$this->form_extended->build_triples($node, $datasetinstance, $data));
				}
			} else {
				$node_name = $key."_node";
				$node = $$node_name;
				$data = $this->form_extended->load($key); 
				$triples = array_merge($triples,$this->form_extended->build_triples($node, $dataset, $data));
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
		@$rdf = $this->arcmodel->getRDF("http://db.opensustainability.info/rdfspace/lca/".$URI);
		header("Content-Disposition: attachment; filename=\"$URI.rdf\"");
		header('Content-type: text/xml');
		echo $rdf;
	}	


	/***
	* @public
	* Grabs all the triples for a particular URI and shows it in JSON
	*/	
	public function viewJSON($URI = null) {
		@$json = $this->arcmodel->getJSON("http://db.opensustainability.info/rdfspace/lca/".$URI);
		header('Content-type: application/json');
		echo $json;
	}
	
	private function anchor($uri) {
		if (isset($this->tooltips[$uri]) != true) {
			if (strpos($uri,":") !== false) {
				$this->tooltips[$uri] = array();
				$this->tooltips[$uri]['label'] = $this->arcremotemodel->getLabel($uri);	
				$this->tooltips[$uri]['l'] = $this->tooltips[$uri]['label'];
				if (strpos($uri, "qudtu") !== false) {
					$this->tooltips[$uri]['abbr'] = $this->arcremotemodel->getAbbr($uri);
					$this->tooltips[$uri]['l'] = $this->tooltips[$uri]['abbr'];
				} 
				if (strpos($uri, "qudtu") !== false) {
					$this->tooltips[$uri]['quantityKind'] = $this->arcremotemodel->getQuantityKind($uri);
				}				
				if ($this->tooltips[$uri]['l'] == false) { 
					$uri_parts = explode(":", $uri);
					return $uri_parts[1];
				} 
			} 
		}
	}


	/***
	* @public
	* Grabs all the triples for a particular URI and shows it in a friendly, human readable way
	*/
	public function view($URI = null) {	
		$this->tooltips = array();
		@$parts['impactAssessments'] = $this->convertImpactAssessments($this->arcmodel->getImpactAssessments("http://db.opensustainability.info/rdfspace/lca/" . $URI));
	
		@$parts['bibliography'] = $this->convertBibliography($this->arcmodel->getBibliography("http://db.opensustainability.info/rdfspace/lca/" . $URI));
	
		@$parts['exchanges'] = $this->convertExchanges($this->arcmodel->getExchanges("http://db.opensustainability.info/rdfspace/lca/" . $URI));	
		
		@$parts['modeled'] = $this->convertModeled($this->arcmodel->getModeled("http://db.opensustainability.info/rdfspace/lca/" . $URI));
		
		$parts['geography'] = $this->convertGeography($this->arcmodel->getGeography("http://db.opensustainability.info/rdfspace/lca/" . $URI));
	
		@$parts['quantitativeReference'] = $this->convertQR($this->arcmodel->getQR("http://db.opensustainability.info/rdfspace/lca/" . $URI));

		@$parts['tooltips'] = $this->tooltips;
		

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
		$comments = $this->arcmodel->getComments("http://db.opensustainability.info/osi/rdfspace/lca/".$URI);
		$this->data("comments", $comments);
		$this->data("comment", $comment);
		$this->display("View " . $parts['quantitativeReference']['amount'] . " " . $parts['quantitativeReference']['unit'] . " of " . $parts['quantitativeReference']['name'], "viewLCA");		
	}


	private function convertBibliography($dataset){
		$bibo_prefix = "http://purl.org/ontology/bibo/";
		$foaf_prefix = "http://xmls.com/foaf/0.1/";
		$dc_prefix = "http://purl.org/dc/";
		$converted_dataset = array();
		foreach ($dataset as $key=>$record) {
			if (isset($record[$dc_prefix."title"]) == true) {
				foreach($record[$dc_prefix."title"] as $title) {
					$converted_dataset[$key]['title'] = $title;
				}
			} else {
				$converted_dataset[$key]['title'] = "";
			}
			if (isset($record[$bibo_prefix."authorList"]) == true) {
				$person_array = array();
				foreach($record[$bibo_prefix."authorList"] as $author_uri) {
					$person = $this->arcmodel->getTriples($author_uri);
					foreach ($person[$foaf_prefix.'firstName'] as $firstName) {
						$person_array['firstName'] = $firstName;
					} 
					foreach ($person[$foaf_prefix.'lastName'] as $lastName) {
						$person_array['lastName'] = $lastName;
					}						
				}
				$converted_dataset[$key]['authors'][] = $person_array;
			} else {
				
			}
			if (isset($record[$bibo_prefix."uri"]) == true) {
				foreach($record[$bibo_prefix."uri"] as $uri) {
					$converted_dataset[$key]['uri'] = $uri;
				}
			} else {
				$converted_dataset[$key]['uri'] = "";
			} 
			if (isset($record[$dc_prefix."date"]) == true) {
				foreach($record[$dc_prefix."date"] as $date) {
					$converted_dataset[$key]['date'] = $date;
				}
			} else {
				$converted_dataset[$key]['date'] = "";
			}
			/*
			if (isset($record[$bibo_prefix."isbn"]) == true) {
				foreach($record[$bibo_prefix."date"] as $date) {
					$converted_dataset[$key]['date'] = $date;
				}
			} else {
				$converted_dataset[$key]['date'] = "";
			}
			"dc:creator" => $organization_uris,
			"bibo:isbn" => trim($line_array[5]),
			"bibo:volume" => trim($line_array[6]),
			"bibo:issue" => trim($line_array[7]),
			"bibo:doi" => trim($line_array[10]),
			"bibo:chapter" => trim($line_array[13]),
			"bibo:locator" => trim($line_array[14]),	
			*/					
		}
		return $converted_dataset;
	}
	
	private function convertExchanges($dataset){
		$rdfs_prefix = "http://www.w3.org/2000/01/rdf-schema#";
		$eco_prefix = "http://ontology.earthster.org/eco/core#";
		$converted_dataset = array();
		foreach($dataset as $key=>$record) {		
			foreach($record[$eco_prefix."hasEffect"] as $_record) {
				foreach ($_record[$rdfs_prefix."type"] as $__record) {
					if ($__record == "eco:Output" || $__record == "eco:Input") {
						$converted_dataset[$key]["direction"] = str_replace("eco:", "", $__record);
					} 
				}
				foreach($_record[$eco_prefix."hasTransferable"] as $transferable) {
					if (is_array($transferable) == true) {
						foreach($transferable[$rdfs_prefix."label"] as $label) {
							$converted_dataset[$key]['name'] = $label;
						}
					} else {
						$converted_dataset[$key]['name'] = $transferable;
					}
				}
				foreach($_record[$eco_prefix."hasFlowable"] as $flowable) {
					$converted_dataset[$key]['name'] = str_replace("eco", "", $flowable);
				} 								
			}
			foreach ($record[$eco_prefix."hasQuantity"] as $_record) {
				foreach($_record[$eco_prefix."hasMagnitude"] as $magnitude) {
					$converted_dataset[$key]['amount'] = $magnitude;
				} 
				foreach($_record[$eco_prefix."hasUnitOfMeasure"] as $unitOfMeasure) {
					$converted_dataset[$key]['unit'] = $unitOfMeasure;
					$this->anchor($unitOfMeasure);
				} 
			}
		}
		return $converted_dataset; 
	}
	
	private function convertQR($dataset){
		$rdfs_prefix = "http://www.w3.org/2000/01/rdf-schema#";
		$eco_prefix = "http://ontology.earthster.org/eco/core#";
		$converted_dataset = array();		
		foreach($dataset as $key=>$record) {		
			$converted_dataset['name'] = $record['name'];
			$converted_dataset['amount'] = $record['magnitude'];
			$converted_dataset['unit'] = $record['unit'];
			$this->anchor($record['unit']);
		}		
		return $converted_dataset; 
	}	
	
	private function convertModeled($dataset){
		$rdfs_prefix = "http://www.w3.org/2000/01/rdf-schema#";
		$eco_prefix = "http://ontology.earthster.org/eco/core#";
		$converted_dataset = array();
		foreach($dataset as $key=>$record) {		
			if(isset($record[$rdfs_prefix."type"]) == true) {
				foreach($record[$rdfs_prefix."type"] as $type) {
					foreach($record[$rdfs_prefix."label"] as $label) {
							$this->anchor($type);
							$converted_dataset['type'] = $type;
					}				
				}				
			}
		}		
		return $converted_dataset; 
	}
	
	private function convertGeography($dataset){
		$rdfs_prefix = "http://www.w3.org/2000/01/rdf-schema#";
		$eco_prefix = "http://ontology.earthster.org/eco/core#";
		$converted_dataset = array();
		if ($dataset != false) {
			foreach($dataset as $geo) {
				$converted_dataset[] = $this->arcremotemodel->getPointGeonames($geo['geo_uri']);
			}
			return $converted_dataset;
		} else {
			return false;
		}
	}

	private function convertImpactAssessments($dataset){
		$rdfs_prefix = "http://www.w3.org/2000/01/rdf-schema#";
		$eco_prefix = "http://ontology.earthster.org/eco/core#";
		$converted_dataset = array();		
		foreach($dataset as $key=>$_record) {	
			foreach ($_record[$eco_prefix."hasImpactAssessmentMethodCategoryDescription"] as $__record) {
				foreach($__record[$eco_prefix."hasImpactCategory"] as $___record) {
					$converted_dataset[$key]['impactCategory'] = $___record;
					$this->anchor($___record);
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
					$this->anchor($___record);
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
	}