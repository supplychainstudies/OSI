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
		$CI =& get_instance();    
	        $CI->config->load('arc');		
		$this->arc_config = $CI->config->item("arc_lr_info");		
	}	
	
	/**
	 * This function is a generic call to the db.
	 * @return Array of triples.
	 * @param $q string - query string.
	 */

		public function executeRemoteQuery($remote_endpoint, $q) {
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
		
		
		public function executeQuery($q) {
			$store = $this->arc->getStore($this->arc_config);

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
					echo $error;
				}
			}

		}
		
		public function dumpAll() {
			$cached = array(
				array(
					'http://www.qudt.org/qudt/owl/1.0.0/nist-constants.owl'				
				),
				array(
					'http://www.qudt.org/qudt/owl/1.0.0/qudt-spin.owl'
				),
				array(
					'http://www.qudt.org/qudt/owl/1.0.0/qudt-dbpedia.owl'
				),
				array (
					'http://www.qudt.org/qudt/owl/1.0.0/qudt.owl',
					'http://data.nasa.gov/qudt/owl/qudt#'
				),
				array(
					'http://www.qudt.org/qudt/owl/1.0.0/unit.owl',
					'http://data.nasa.gov/qudt/owl/unit#'
				),
				array(
					'http://www.qudt.org/qudt/owl/1.0.0/quantity.owl',
					'http://data.nasa.gov/qudt/owl/quantity#'
				),
				array(
					'http://www.qudt.org/qudt/owl/1.0.0/dimension.owl',
					'http://data.nasa.gov/qudt/owl/dimension#'
				),
				array(
					'http://www.qudt.org/qudt/owl/1.0.0/nist-constants.owl',
					'http://physics.nist.gov/cuu/'
				),
				array(
					'http://osi/schemas/Earthster/alloc.n3',
					'http://ontology.earthster.org/eco/alloc#'
				),				
				array(
					'http://osi/schemas/Earthster/attribute.n3',
					'http://ontology.earthster.org/eco/attribute#'
				),
				array(
					'http://osi/schemas/Earthster/biboBridge.n3',
					'http://ontology.earthster.org/eco/biboBridge#'
				),
				array(
					'http://osi/schemas/Earthster/bridges.n3',
					'http://ontology.earthster.org/eco/bridges#'
				),
				array(
					'http://osi/schemas/Earthster/cml2001.ttl',
					'http://ontology.earthster.org/eco/cml2001#'
				),
				array(
					'http://osi/schemas/Earthster/core.n3',
					'http://ontology.earthster.org/eco/core#'
				),
				array(
					'http://osi/schemas/Earthster/ecodl.n3',
					'http://ontology.earthster.org/eco/ecodl#'
				),
				array(
					'http://osi/schemas/Earthster/ecofull.n3',
					'http://ontology.earthster.org/eco/ecofull#'
				),				
				array(
					'http://osi/schemas/Earthster/ecoinvent.ttl',
					'http://ontology.earthster.org/eco/ecoinvent#'
				),
				array(
					'http://osi/schemas/Earthster/ecospold.n3',
					'http://ontology.earthster.org/eco/ecospold#'
				),
				array(
					'http://osi/schemas/Earthster/fasc.n3',
					'http://ontology.earthster.org/eco/fasc#'
				),
				array(
					'http://osi/schemas/Earthster/foafBridge.n3',
					'http://ontology.earthster.org/eco/foafBridge#'
				),
				array(
					'http://osi/schemas/Earthster/fullAxioms.n3',
					'http://ontology.earthster.org/eco/fullAxioms#'
				),
				array(
					'http://osi/schemas/Earthster/goodRelationsBridge.n3',
					'http://ontology.earthster.org/eco/goodRelationsBridge#'
				),
				array(
					'http://osi/schemas/Earthster/ilcd.ttl',
					'http://ontology.earthster.org/eco/ilcd#'
				),
				array(
					'http://osi/schemas/Earthster/impact.n3',
					'http://ontology.earthster.org/eco/impact#'
				),
				array(
					'http://osi/schemas/Earthster/impact2002Plus.n3',
					'http://ontology.earthster.org/eco/impact2002Plus#'
				),
				array(
					'http://osi/schemas/Earthster/sumoBridge.n3',
					'http://ontology.earthster.org/eco/sumoBridge#'
				),
				array(
					'http://osi/schemas/Earthster/timeBridge.n3',
					'http://ontology.earthster.org/eco/timeBridge#'
				),
				array(
					'http://osi/schemas/Earthster/uncertaintyDistribution.n3',
					'http://ontology.earthster.org/eco/uncertaintyDistribution#'
				),
				array(
					'http://osi/schemas/Earthster/unit.ttl',
					'http://ontology.earthster.org/eco/unit#'
				),
			);
			
			foreach($cached as $onto) {
				if (count($onto) == 1) {
					$q = "LOAD <" . $onto[0] . "> INTO <" . $onto[0] . ">";
				} elseif (count($onto) == 2) {
					$q = "LOAD <" . $onto[0] . "> INTO <" . $onto[1] . ">";
				}
				$results = $this->executeLocalQuery($q);	
			}
		}
		/*
		public function qudGetUnits() {
			$remote_endpoint = "http://www.qudt.org/qudt/owl/1.0.0/qudt.owl";
			$q = "select ?uri where { ?uri <http://data.nasa.gov/qudt/owl/qudt#Unit> ?label . }";
			$results = $this->executeQuery($remote_endpoint, $q);
			var_dump($results);
		}
		*/

		public function qudGetUnits() {
			$q = "select ?uri where { ?uri <http://data.nasa.gov/qudt/owl/qudt#Unit> ?label . }";
			$results = $this->executeQuery($remote_endpoint, $q);
			var_dump($results);
		}		
		
		public function getLabel($uri) {
			$xarray = explode(":", $uri);
			
			$q = "select ?label where { " .
				"'" . $this->arc_config['ns'][$xarray[0]] . $xarray[1] . "' '" . $this->arc_config['ns']['rdfs'] . "label'" . " ?label . " . 				
				"}";
			$results = $this->executeQuery($q);
			if (count($results) != 0) {
				$return[0]['label'];
			}
			return $results;		
		}
		
		public function getURIFromLabel($label) {		
			$q = "select ?uri where { " .
				"?uri '" . $this->arc_config['ns']['rdfs'] . "label'" . "'" . $label . "' . " . 				
				"}";
			$results = $this->executeQuery($q);
			if (count($results) != 0) {
				return $results[0]['uri'];
			}		
		}
		/*
		public function getLabel($uri) {
			if (strpos($uri, "dbpedia") !== false) {
				$remote_endpoint = "http://dbpedia.org/sparql";
			}
			$q = "select distinct ?label where { <".$uri."> <http://www.w3.org/2000/01/rdf-schema#label> ?label . }";
			$results = $this->executeQuery($remote_endpoint, $q);
			return $results[0]['label'];
		}
		*/
		
} // End of Class

?>