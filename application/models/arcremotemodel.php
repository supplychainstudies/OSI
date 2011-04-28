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
		$this->config->load('arc');		
		$this->config->load('arcdb');	
		$this->arc_config = array_merge($this->config->item("arc_lr_info"), $this->config->item("db_arc_lr_info"));	
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
				
		public function getSomething($uri, $predicate) {
			if (strpos($uri,":") !== false) {
				$xarray = explode(":", $uri);
				$the_uri = $this->arc_config['ns'][$xarray[0]] . $xarray[1];
			} elseif (strpos($uri,"http://") !== false) {
				$the_uri = $uri;
			}
			if (strpos($predicate,":") !== false) {
				$xarray = explode(":", $predicate);
				$the_predicate = $this->arc_config['ns'][$xarray[0]] . $xarray[1];
			} elseif (strpos($uri,"http://") !== false) {
				$the_predicate = $predicate;
			}
			
			$q = "select ?thing where { " .
				"<" . $the_uri . "> '" . $the_predicate . "' ?thing . " . 				
				"}";
			
			$results = $this->executeQuery($q);
			if (count($results) != 0) {
				return $results[0]['thing'];
			} else {
				return false;
			}			
		}
		
		public function getQuantityKinds($object) {
			if (strpos($object,":") !== false) {
				$xarray = explode(":", $object);
				$the_object = $this->arc_config['ns'][$xarray[0]] . $xarray[1];
			} elseif (strpos($object,"http://") !== false) {
				$the_object = $object;
			} else {
				$the_object = $object;
			}
			
			$q = "select DISTINCT ?uri ?label where { " .
				"?uri '" . $this->arc_config['ns']['qudt'] . "quantityKind' '" . $the_object . "' . " . 	
				"?uri '" . $this->arc_config['ns']['rdfs'] . "label' ?label . " . 	
				"?uri '" . $this->arc_config['ns']['rdf'] . "type' ?type . " . 
				//"?type '" . $this->arc_config['ns']['rdfs'] . "subClassOf' ?stuf . " . 	
				"FILTER regex(?type, '" . $this->arc_config['ns']['qudt'] . "', 'i')" . 		
				"}";
			$results = $this->executeQuery($q);
			if (count($results) != 0) {
				return $results;
			} else {
				return false;
			}			
		}
		
		public function getSomeThings($predicate) {
			if (strpos($predicate,":") !== false) {
				$xarray = explode(":", $predicate);
				$the_predicate = $this->arc_config['ns'][$xarray[0]] . $xarray[1];
			} elseif (strpos($uri,"http://") !== false) {
				$the_predicate = $predicate;
			}
			
			$q = "select ?uri where { " .
				"?uri '" . $the_predicate . "' ?thing . " . 				
				"}";
			$results = $this->executeQuery($q);
			if (count($results) != 0) {
				return $results;
			} else {
				return false;
			}			
		}
		
		public function getLabel($uri) {
			return $this->getSomething($uri, "rdfs:label");	
		}

		public function getAbbr($uri) {
			return $this->getSomething($uri, "qudt:abbreviation");	
		}

		public function getQuantityKind($uri) {
			$kind_uri =  $this->getSomething($uri, "qudt:quantityKind");
			return $this->getLabel($kind_uri);	
		}
						
		public function getDescription($uri) {
			$xarray = explode(":", $uri);
			
			$q = "select ?label where { " .
				"'" . $this->arc_config['ns'][$xarray[0]] . $xarray[1] . "' '" . $this->arc_config['ns']['rdfs'] . "label'" . " ?label . " . 				
				"}";
			$results = $this->executeQuery($q);
			if (count($results) != 0) {
				return $results[0]['label'];
			}		
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
		
		private function isLoaded($uri) {
			$q = "select ?c where { " .
				"<" . $uri . "> ?c ?d . " . 				
				"}";
			$results = $this->executeQuery($q);
			if (count($results) != 0) {
				return true;
			} else {
				return false;
			}
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
			$results = $this->executeQuery($q);
			if (count($results) != 0) {
				return $results[0];
			} else {
				return false;
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