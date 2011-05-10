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
		$this->load->model(Array('arcmodel', 'arcremotemodel', 'mysqlmodel'));	
		$this->load->library(Array('form_extended', 'name_conversion','SimpleLoginSecure'));
	}
	public $URI;
	public $data;
	public $post_data;
	public $tooltips = array();

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
				'impactAssessments' => $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments($URI), $this->tooltips),
				'quantitativeReference' => $this->lcamodel->convertQR($this->lcamodel->getQR($URI), $this->tooltips)
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