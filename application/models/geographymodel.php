<?php
include_once('arcmodel.php');
/**
 * This model uses the Arc2 library to insert, edit, and retrieve rdf data from the arc store 
 * 
 * @package opensustainability
 * @subpackage models
 */
 
class Geographymodel extends ArcModel{
     
    /**
     * @ignore
     */
    function Geographymodel(){
        parent::arcmodel();
 
    }



	public function getPointGeonames($uri) {
		// check if this uri is already loaded
		if ($this->isLoaded($uri) == false) {
			$q = "LOAD <" . $uri . "> INTO <" . $uri . ">";
			$this->executeQuery($q);
		}		
		$q = "select ?lat ?long ?name where { " .
		 	"<" . $uri . "> '" . $this->arc_config['ns']['foaf'] . "primaryTopic' ?bnode . " .  
			"?bnode '" . $this->arc_config['ns']['wgs84_pos'] . "lat' ?lat . " . 	
			"?bnode '" . $this->arc_config['ns']['wgs84_pos'] . "long' ?long . " .
			"?bnode '" . $this->arc_config['ns']['gn'] . "name' ?name . " .
			"}";	
										
		$results = $this->executeQuery($q, "remote");
		if (count($results) != 0) {
			return $results[0];
		} else {
			return false;
		}
	}
	
} // End Class