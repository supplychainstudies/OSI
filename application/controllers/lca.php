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
				var_dump($key);
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


	/***
	* @public
	* Grabs all the triples for a particular URI and shows it in a friendly, human readable way
	*/
	public function view($URI = null) {	
		@$parts['impactAssessments'] = $this->convertImpactAssessments($this->arcmodel->getImpactAssessments("http://db.opensustainability.info/rdfspace/lca/" . $URI));
	
		@$parts['bibliography'] = $this->convertBibliography($this->arcmodel->getBibliography("http://db.opensustainability.info/rdfspace/lca/" . $URI));
	
		@$parts['exchanges'] = $this->convertExchanges($this->arcmodel->getExchanges("http://db.opensustainability.info/rdfspace/lca/" . $URI));	
	
		@$parts['modeled'] = $this->convertModeled($this->arcmodel->getModeled("http://db.opensustainability.info/rdfspace/lca/" . $URI));
	
		@$parts['quantitativeReference'] = $this->convertQR($this->arcmodel->getQR("http://db.opensustainability.info/rdfspace/lca/" . $URI));
		 $links = '<p><a href="/'.$URI.'.rdf">Get this RDF</a></p>
		<p><a href="/'.$URI.'.json">Get this in JSON</a></p>';
		$this->data("links", $links);
		$this->data("URI", $URI);
		$this->data("parts", $parts);
		$this->script(Array('comments.js', 'janrain.js'));
		//$this->data($URI, $view_string);

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
			}
			if (isset($record[$bibo_prefix."authorList"])) {
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
			} 						
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
					$converted_dataset[$key]['unit'] = str_replace("qudt:", "", $unitOfMeasure);
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
							$type = strtolower(str_replace("eco:","",$type));
							$converted_dataset[$type] = $label;
					}				
				}				
			}
		}		
		return $converted_dataset; 
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
					$converted_dataset[$key]['unit'] = str_replace("qudt:", "",$___record);
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