<?php
class Opencycmodel extends FT_Model{
    function opencycmodel(){
        parent::__construct();
		$this->arc_config['store_name'] = "openCyc";
    }

	public function getOpenCycLabel($uri){
		$results =  $this->getLabel($uri);
		return $results;
	}
	
	public function getOpenCycSameAs($uri) {
		return $this->getSomeThings($uri, "owl:sameAs");
	}
	
	public function getDbpedia($uri) {
		return $this->getAll($uri,'');
	}
	
	public function getCategories($uri) {
		$results = $this->getSomeThings($uri, "rdf:type");
		$new_results = array();
		if (count($results) < 0) {
			foreach ($results as $result) {
				$new_results[] = "";
			}
		}
	}
	
	public function getSuggestedPages($strings) {
		$this->arc_config['store_name'] = "openCyc";
		$results = array(); 
		foreach ($strings as $string) {
			$results = array_merge($results,$this->getURIbyLabel($string));
		}
		return $results;	
	}
	
}
