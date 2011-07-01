<?php
class Nacemodel extends FT_Model{
    function Nacemodel(){
		parent::__construct();
		$this->arc_config['store_name'] = "nace";
    }

	public function getURIbyCode($code) {
       $q = "select ?uri where { " .
           " ?uri nace:sectionAndCode '".$code."' . " .
           "}";	

		$results = $this->executeQuery($q);
		if (count($results) != 0) {
			return $results[0]['uri'];
		} else {
			return false;
		}
	}
	
	
	public function dump() {
		$q = "LOAD <http://osi/assets/data/nace.rdf> INTO <http://ec.europa.eu/eurostat/ramon/rdfdata/nace_r2/>";
		$results = $this->executeQuery($q);
	}
	
} // End Class