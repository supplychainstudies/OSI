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



class SIAPI extends SM_Controller {
	public function SIAPI() {
		parent::SM_Controller();
		$this->load->model(Array('arcmodel', 'arcremotemodel', 'mysqlmodel'));	
		$this->load->library(Array('form_extended', 'formats', 'name_conversion','SimpleLoginSecure'));
		if($this->session->userdata('logged_in')) {
			$this->data("header", "loggedin");
			if ($this->session->userdata('user_email') == true) {
				$this->data("id", $this->session->userdata('user_email'));
			} else if ($this->session->userdata('openid_email') == true) {
				$this->data("id", $this->session->userdata('openid_email'));
			}
		} else {
			$this->data("header", "login");
		}
	}
	public $URI;
	public $data;
	public $post_data;
	
	/***
    * @public
    * gets a list that matches your process name
    */	

	public function name($keyword) {
		// We should be able to call to xml files to return the string of parent nodes
		$fields = array(
			"Process Name", 
			"Quantitative Reference Name",
			"Quantitative Reference Unit",
			"Quantitative Reference Type",
			"Quantitative Reference Amount",
			"Impact Category",
			"Impact Category Amount",
			"Impact Category Unit"
			);
		$impacts = array();
		// This next line should perhaps be pointing instead to a model that 1) combines the local arc model and remote endpoint models so that we can include new remote data sets easily 2) uses ISO labels (and converter documents) to call to many different kinds of formats
		$URIs = @$this->arcmodel->simpleSearch("processName", $keyword);
		foreach ($URIs as &$URI) {
			$URI_type = str_replace('lca', 'ISO14048', @$this->arcmodel->getDataType($URI));
			$impacts[$URI] = array();
			foreach ($fields as $field) {
				$path = $this->formats->getPath($this->name_conversion->toFieldName($field), $URI_type);
				$path = str_replace('data_documentation_of_process->', '', $path);
				$new_path = "";
				foreach (explode("->", $path) as $step) {
					$new_path = $new_path . $this->name_conversion->toLinkedType2($step) . "->";
				}
				
				$impacts[$URI][$field] = $this->arcmodel->followToBNode($URI, str_replace('lifeCycleAssessment->', '', substr($new_path, 0, -2)));
			}
		}
		var_dump($impacts);	
	}
	
	public function searchh($field, $keyword, $encoding = 'json') {
		$URIs = @$this->arcmodel->simpleSearch($field, $keyword);		
		if ($encoding == 'json') {
			header('Content-type: application/json');
			echo json_encode($URIs);
		} else if ($encoding == 'rdf') {
			header('Content-type: text/xml');
			echo $URIs;			
		} else if ($encoding == 'xml') {
			header('Content-type: text/xml');
			echo $URIs;
		} else if ($encoding == 'html') {
			header('Content-type: text/html');
			echo $URIs;			
		}
	}
	
	public function search($encode = "json") {
		if ($search_terms = $_GET) {
			$checked_URIs = array();
			$URIs = array();
			$results = array();
			foreach ($search_terms as $field=>$value) {
				$URIs = array_merge($URIs,@$this->arcmodel->simpleSearch($field, $value));
			}
			foreach ($URIs as $URI) {
				if (in_array($URI, $checked_URIs) == false) {
					$checked_URIs[] = $URI;
				$results[$URI] = array (
					'uri' => $URI,
					'impactAssessments' => $this->convertImpactAssessments(@$this->arcmodel->getImpactAssessments($URI)),
					'quantitativeReference' => $this->convertQR(@$this->arcmodel->getQR($URI))
					);
				}
			}
		}
		if ($encode == 'json') {
			header('Content-type: application/json');
			echo json_encode($results);
		} else if ($encode == 'rdf') {
			header('Content-type: text/xml');
			echo $URIs;			
		} else if ($encode == 'xml') {
			header('Content-type: text/xml');
			echo $this->assocArrayToXML("results",$results);
		} else if ($encode == 'html') {
			header('Content-type: text/html');
			echo $URIs;			
		}
		
	}
	
	private function assocArrayToXML($root_element_name,$ar)
	{
	    $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><{$root_element_name}></{$root_element_name}>");
	    $f = create_function('$f,$c,$a','
	            foreach($a as $k=>$v) {
	                if(is_array($v)) {
	                    $ch=$c->addChild($k);
	                    $f($f,$ch,$v);
	                } else {
	                    $c->addChild($k,$v);
	                }
	            }');
	    $f($f,$xml,$ar);
	    return $xml->asXML();
	}
	
	private function convertImpactAssessments($dataset){
		$rdfs_prefix = "http://www.w3.org/2000/01/rdf-schema#";
		$eco_prefix = "http://ontology.earthster.org/eco/core#";
		$converted_dataset = array();		
		foreach($dataset as $key=>$record) {	
			foreach ($record[$eco_prefix."hasImpactCategoryIndicatorResult"] as $_record) {
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
	
		
}