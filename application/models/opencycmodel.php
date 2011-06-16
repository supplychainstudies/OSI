<?php
class Opencycmodel extends FT_Model{
    function opencycmodel(){
        parent::__construct();
		$this->arc_config['store_name'] = "opencyc";
    }

	public function getOpenCycLabel($uri){
		$q = "SELECT ?label from <".$uri."> WHERE { <".$uri."> rdfs:label ?label }";
		$results = $this->executeQuery($q);
		if (count($results) > 0) {
			return $results[0]['label'];
		} else {
			$this->dumpOpencycConcept($uri);
			$q = "SELECT ?label from <".$uri."> WHERE { <".$uri."> rdfs:label ?label }";
			$results = $this->executeQuery($q);
			if (count($results) > 0) {
				return $results[0]['label'];
			} else {
				return false;
			}
		}
	}
	
	public function dumpOpencycConcept($uri){
		$q = "LOAD <" . $uri . "> INTO <" . $uri . ">";
		$this->executeQuery($q);
	}
	
	public function getOpenCycSameAs($uri) {
		$q = "SELECT ?uri from <".$uri."> WHERE { ?s owl:sameAs ?uri }";
		$results = $this->executeQuery($q);
		if (count($results) != 0) {
			return $results;
		}
	}
	
	public function getDbpedia($uri) {
		return $this->getAll($uri,'');
	}
	
	public function getOpenCycSearchCategories($uri) {
		$results = $this->getSomeURIs($uri, "rdfs:subClassOf");
		$rs = array();
		foreach($results as $result) {
			$rs[] = array(
				"uri" => $result,
				"label" => $this->getLabel($result)
			);
		}
		return $rs;
	}
	/*
	public function getOpenCycCategories($uri) {
		$stuff = $this->getOpenCycType($uri);
	}
	
	public function getOpenCycType($uri) {
		$the_array = array();
		$results = $this->getSomeThings($uri, "rdf:type");
		if (count($results) > 0) {
			foreach ($results as $result) {
				if (strpos($result,"opencyc") !== false) {
					$the_array[$result]["label"] = $this->getOpenCycLabel($result);
					$rs = $this->getOpenCycType($result);
					if (count($rs) > 0) {
						$the_array[$result]["paths"] = $rs;
					}
				}
			}
			if (count($the_array) > 0) {
				return $the_array;
			} else {
				return null;
			}
		} else {
			return null;
		}
	}	
	*/
	public function getSuggestedPages($strings) {
		$this->arc_config['store_name'] = "openCyc";
		$results = array(); 
		foreach ($strings as $string) {
			$rs = $this->getURIbyLabel($string);
			if (is_array($rs) && count($rs) > 0) {
				$results = array_merge($results, $rs);
			}
		}
		return $results;	
	}
	
}
