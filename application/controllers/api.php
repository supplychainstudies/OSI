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
		$this->load->model(Array('lcamodel', 'geographymodel', 'bibliographymodel','peoplemodel','commentsmodel','ecomodel','opencycmodel'));		
		$this->load->library(Array('form_extended','SimpleLoginSecure'));
		$this->load->helper(Array('linkeddata_helper'));
	}
	public $URI;
	public $data;
	public $post_data;
	public $tooltips = array();


	public function search($encode = "json") {
		parse_str($_SERVER['QUERY_STRING'],$_GET);
		$checked_URIs = array();
		$search_terms = $_GET;
		$limit = 20;
		$offset = 0;
		$value = null;	
		$URIs = array();
		$rs = array();
		if (isset($search_terms['limit']) == true) {
			$limit = $search_terms['limit'];
		}
		
		if (isset($search_terms['offset']) == true) {
			$offset = $search_terms['offset'];
		}
		
		if (isset($search_terms['product']) == true) {
			$value = $search_terms['product'];
		}	
		if (isset($search_terms['encode']) == true) {
			$encode = $search_terms['encode'];
		}	
		$results = $this->lcamodel->simpleSearch($value, $limit, $offset);
		foreach ($results as $result) {
			$impacts = array (
				'uri' => $result['uri'],
				'impactAssessments' => $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments($result['uri'])),
				'quantitativeReference' => $this->lcamodel->convertQR($this->lcamodel->getQR($result['uri']))
				);
			//Normalize the results to 1 kg
			$rs[$result['uri']] = $this->normalize($impacts);
			
		}

		if ($encode == 'json') {
			header('Content-type: application/json');
			echo json_encode($rs);
		} else if ($encode == 'rdf') {
			header('Content-type: text/xml');
			echo $rs;			
		} else if ($encode == 'xml') {
			header('Content-type: text/xml');
			echo $this->assocArrayToXML("results",$rs);
		} else if ($encode == 'html') {
			header('Content-type: text/html');
			var_dump($rs);			
		}
		
	}
	
	public function searchByCategory($encode = "json") {
		parse_str($_SERVER['QUERY_STRING'],$_GET);
		$checked_URIs = array();
		$search_terms = $_GET;
		$limit = 20;
		$offset = 0;
		$value = null;	
		$URIs = array();
		$results = array();
		$rs = array();
		if (isset($search_terms['limit']) == true) {
			$limit = $search_terms['limit'];
		}
		
		if (isset($search_terms['offset']) == true) {
			$offset = $search_terms['offset'];
		}
		if (isset($search_terms['encode']) == true) {
			$encode = $search_terms['encode'];
		}
		if (isset($search_terms['category']) == true) {
			$value = array($search_terms['category']);
			$opencyc = $this->opencycmodel->getSuggestedPages($value);
			foreach ($opencyc as $r){
				$opencyc_uris[] = $r['uri'];
			}
		}	
		if (isset($search_terms['opencycref']) == true) {
			$opencyc_uris = array('http://sw.opencyc.org/concept/'.$search_terms['opencycref']);
		}
		
		if (isset($search_terms['category']) == true || isset($search_terms['opencycref']) == true) {		
			foreach ($opencyc_uris as $uri) {
				$results = array_merge($results, $this->lcamodel->getLCAsByCategory($uri));
			}
			$count = 0;
			foreach ($results as $result) {
				if ($count > $limit+$offset) {
					break;
				}
				$tst = $limit+$offset;
				if ($count >= $offset && $count < $limit+$offset) {
				$impacts = array (
					'uri' => $result['uri'],
					'impactAssessments' => $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments($result['uri'])),
					'quantitativeReference' => $this->lcamodel->convertQR($this->lcamodel->getQR($result['uri']))
					);
				$rs[$result['uri']] = $this->normalize($impacts);	
				}
				$count++;
			}
		} else {
			$results = $this->lcamodel->getAllUsedCategories();
			$rs = array();
			foreach ($results as $result) {
				if (strpos($result['uri'],"opencyc.org") !== false) {
					$rs[] = array(
						'opencycref' => str_replace("http://sw.opencyc.org/concept/","",$result['uri']),
						'category' => $result['label']
					);
				}
			}
		}
		if ($encode == 'json') {
			header('Content-type: application/json');
			echo json_encode($rs);
		} else if ($encode == 'rdf') {
			header('Content-type: text/xml');
			echo $rs;			
		} else if ($encode == 'xml') {
			header('Content-type: text/xml');
			echo $this->assocArrayToXML("results",$rs);
		} else if ($encode == 'html') {
			header('Content-type: text/html');
			echo $rs;			
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
	
	private function normalize($parts)
	{
		/* If the functional unit is mass, normalize to 1kg */
		
		if (strpos("Kilogram", $parts['quantitativeReference']['unit']['label']) !== false) {
			$ratio = $parts['quantitativeReference']['amount'];
			$parts['quantitativeReference']['amount'] = 1;
			foreach ($parts['impactAssessments'] as &$impactAssessment) {
				$impactAssessment['amount'] = $impactAssessment['amount'] / $ratio;
			}
		}
		
		if (strpos("Gram", $parts['quantitativeReference']['unit']['label']) !== false) {
			$ratio = $parts['quantitativeReference']['amount'] / 1000;
			// Remember to complete afterwards
			$parts['quantitativeReference']['unit']['label'] = "Kilogram";
			$parts['quantitativeReference']['amount'] = 1;
			foreach ($parts['impactAssessments'] as &$impactAssessment) {
				$impactAssessment['amount'] = $impactAssessment['amount'] / $ratio;
				if (strpos("Gram", $impactAssessment['unit']['label']) !== false) { 
					$impactAssessment['amount']/=1000; 
					$impactAssessment['unit']['label'] = "Kilogram"; 
					}
			}
		}
		
		// If ounces
		if ($parts['quantitativeReference']['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Ounce" ) {
			$ratio = $parts['quantitativeReference']['amount'] / 0.028345;
			// Remember to complete afterwards
			$parts['quantitativeReference']['unit']['label'] = "Kilogram";
			$parts['quantitativeReference']['amount'] = 1;
			foreach ($parts['impactAssessments'] as &$impactAssessment) {
				$impactAssessment['amount'] = $impactAssessment['amount'] / $ratio;
				if ($impactAssessment['unit']['label'] == "Gram") { $impactAssessment['amount']/=1000; $impactAssessment['unit']['label'] = "Kilogram"; }
			}
		}
		
		if ($parts['quantitativeReference']['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Pound" ) {
			$ratio = $parts['quantitativeReference']['amount'] / 0.45359237;
			// Remember to complete afterwards
			$parts['quantitativeReference']['unit']['label'] = "Kilogram";
			$parts['quantitativeReference']['amount'] = 1;
			foreach ($parts['impactAssessments'] as &$impactAssessment) {
				$impactAssessment['amount'] = $impactAssessment['amount'] / $ratio;
				if ($impactAssessment['unit']['label'] == "Gram") { $impactAssessment['amount']/=1000; $impactAssessment['unit']['label'] = "Kilogram"; }
			}
		}
		return $parts;
	}
		
}