<?php

/**
 * Representation of transport methods as model
 * 
 * @package sourcemap
 * @subpackage models
 */

class ArcRemoteModel extends Model{
	
	/**
	 * @ignore
	 */
	function ArcRemoteModel(){
		parent::Model();
		$this->load->library('arc2/ARC2', '', 'arc');			
	}	
	
	/**
	 * This function is a generic call to the db.
	 * @return Array of triples.
	 * @param $q string - query string.
	 */

		public function executeQuery($remote_endpoint, $q) {
			$config = array(
			  /* remote endpoint */
			  'remote_store_endpoint' => $remote_endpoint
			);

			$store = $this->arc->getRemoteStore($config);

			if (!$store->isSetUp()) {
	  			$store->setUp();
			}
			
			$rs = $store->query($q, '', '', true);

			if (!$store->getErrors()) {
				if(isset($rs['result']['rows']))
					return $rs['result']['rows'];
			}
			else {
				$errors = $store->getErrors();
				foreach ($errors as $error) {
					//echo $error;
				}
			}

		}
		
		public function getLabel($uri) {
			if (strpos($uri, "dbpedia") !== false) {
				$remote_endpoint = "http://dbpedia.org/sparql";
			}
			$q = "select distinct ?label where { <".$uri."> <http://www.w3.org/2000/01/rdf-schema#label> ?label . }";
			$results = $this->executeQuery($remote_endpoint, $q);
			return $results[0]['label'];
		}
		
} // End of Class

?>