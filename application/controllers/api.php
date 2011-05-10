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




class API extends FT_Controller {
	public function API() {
		parent::__construct();
		$this->load->model(Array('lcamodel', 'geographymodel', 'bibliographymodel','peoplemodel','commentsmodel','ecomodel'));		
		$this->load->library(Array('form_extended', 'name_conversion','SimpleLoginSecure'));
		$this->load->helper(Array('linkeddata_helper'));
	}
	public $URI;
	public $data;
	public $post_data;
	public $tooltips = array();

	
	/***
    * @public
    * gets a list that matches your process name
    */	
/*
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
*/	

	public function search($encode = "json") {
		$checked_URIs = array();
		$search_terms = $_GET;
		$limit = 20;
		$offset = 0;
		$value = null;	
		$URIs = array();
		$results = array();
		if (isset($search_terms['limit']) == true) {
			$limit = $search_terms['limit'];
		}
		
		if (isset($search_terms['offset']) == true) {
			$offset = $search_terms['offset'];
		}
		
		if (isset($search_terms['product']) == true) {
			$value = $search_terms['product'];
		}		
		$URIs = $this->lcamodel->simpleSearch($value, $limit, $offset);

		foreach ($URIs as $URI) {
			if (in_array($URI, $checked_URIs) == false) {
				$checked_URIs[] = $URI;
			$results[$URI] = array (
				'uri' => $URI,
				'impactAssessments' => $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments($URI),$this->tooltips),
				'quantitativeReference' => $this->lcamodel->convertQR($this->lcamodel->getQR($URI),$this->tooltips)
				);
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
		$this->load->library('Simplexml');
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
	

	
		
}