<?php
class Geographymodel extends FT_Model{
    function Geographymodel(){
		parent::__construct();
		$this->arc_config['store_name'] = "geonames";
    }

	public function getPointGeonames($uri) {
		// check if this uri is already loaded		
		$this->isLoaded($uri);
       $q = "select ?lat ?long ?name where { " .
           "<" . $uri . "> wgs84_pos:lat ?lat . " .    
           "<" . $uri . "> wgs84_pos:long ?long . " .
           "<" . $uri . "> gn:name ?name . " .
           "}";	

		$results = $this->executeQuery($q);
		if (count($results) != 0) {
			return $results[0];
		} else {
			return false;
		}
	}
	
	public function geoEverything($uri) {
       $q = "select * where { " .
           "<" . $uri . "> ?p ?o . " .    
           "}";	
		$results = $this->executeQuery($q);
		var_dump($results);
	}
	
} // End Class