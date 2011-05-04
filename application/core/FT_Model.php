<?php

/**
 * This model uses the Arc2 library to insert, edit, and retrieve rdf data from the arc store 
 * 
 * @package opensustainability
 * @subpackage models
 */

class FT_Model extends CI_Model{
	
	/**
	 * @ignore
	 */
	function FT_Model(){
		parent::__construct();
		$this->load->library('arc2/ARC2', '', 'arc');
		//$this->config->load('arc');	  
	    $this->config->load('arc');	
		$this->config->load('arcdb');	
		$this->arc_config = array_merge($this->config->item("arc_info"), $this->config->item("db_arc_info"));
		$this->arc_lr_config = array_merge($this->config->item("arc_lr_info"), $this->config->item("db_arc_lr_info"));
	}	
	
	// Configuration information for accessing the arc store
	
	

	/**
	 * This function is a generic call to the arc store.
	 * @return Array of triples.
	 * @param $q string - query string.
	 */
	public function executeQuery($q,$db="local") {
		$config = $this->arc_config;
		if ($db == "remote") 
			$config = $this->arc_lr_config;
		$store = $this->arc->getStore($config);

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
				error_log($error,0);
			}
		}

	}
	
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

	/**
	 * This function is a generic call to the arc store.
	 * @return Array of triples.
	 * @param $q string - query string.
	 */
	public function endpoint() {
		/* instantiation */
		$ep = $this->arc->getStoreEndpoint($this->arc_config);

		if (!$ep->isSetUp()) {
		  $ep->setUp(); /* create MySQL tables */
		}

		/* request handling */
		$ep->go();
	}

	/**
	 * This function takes an array with subject,predicate,object rows and turns it into a triple store "insert" statement, then executes it
	 * @return Null
	 * @param $triples Array		
	 */
	public function addTriples($triples) {	
		$q = "insert into <http://footprinted.org/> { ";	
		// for each triple				
		foreach ($triples as $triple) {
			// for each value 
			foreach ($triple as $val) {
				// if the value is a uri,url,or blank node, surround it in <>
				if (strstr($val, "http://") != false || strstr($val, "_:") != false) {
					$q .= "<".$val."> ";					
				} 
				// otherwise, put it in quotes
				else {
					$q .= "'" . $val . "' ";
				}
			}
			$q .= " . ";
		}
		$q .= "}";
		$this->executeQuery($q);
	}
	
	
	
	
	public function getSomething($uri, $predicate, $db="local") { 
		//var_dump($uri);
		//var_dump(strpos($uri,"http://"));
		//var_dump(strpos($uri,":"));
		
		if (strpos($uri,"http://") !== false) {
			$the_uri = $uri;
		} elseif (strpos($uri,":") !== false) {
			$xarray = explode(":", $uri);
			$the_uri = $this->arc_config['ns'][$xarray[0]] . $xarray[1];
		}
		
 		if (strpos($uri,"http://") !== false) {
			$the_predicate = $predicate;
		} elseif (strpos($predicate,":") !== false) {
			$xarray = explode(":", $predicate);
			$the_predicate = $this->arc_config['ns'][$xarray[0]] . $xarray[1];
		}
		
		$q = "select ?thing where { " .
			"<" . $the_uri . "> '" . $the_predicate . "' ?thing . " . 				
			"}";
		
		$results = $this->executeQuery($q, $db);
		if (count($results) != 0) {
			return $results[0]['thing'];
		} else {
			return false;
		}			
	}
	
	public function isLoaded($uri) {
		$q = "select ?c where { " .
			"<" . $uri . "> ?c ?d . " . 				
			"}";
		$results = $this->executeQuery($q, "remote");
		if (count($results) != 0) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * This function retrieves the triples for a uri
	 * @return $triples Array
	 * @param $uri string		
	 */
	public function getArcTriples($uri) {
		$q = "select ?p ?o where { <".$uri."> ?p ?o . }";	
		$records = $this->executeQuery($q);	
		$records_next = array();
		$records_all = array();
		foreach ($records as &$record) {
				$record['s'] = $uri;				
				if (strstr($record['o'], "_:") != false) {
					$records_next = $this->getArcTriples($record['o']);				
					if (count($records_next) > 0) {
						if (count($records_all) > 0) { 
							$records_all = array_merge($records_all, $records_next);
						}	
						else {
							$records_all = $records_next;
						}
					}
				}				
			}
		if (count($records_all) > 0 && count($records) > 0) {
			return array_merge($records, $records_all);
		}
		elseif (count($records) > 0) {
			return $records;
		} else {
			return array(0);
		}
	}
	

	/**
	 * This function returns triples in RDF form
	 * @return $doc string
	 * @param $uri string		
	 */	
	public function getRDF($URI) {
		$ser = $this->arc->getRDFXMLSerializer($this->arc_config);
		$doc = $ser->getSerializedTriples($this->getArcTriples($URI));
		return $doc;
	}
	
	
	/**
	 * This function returns triples in JSON+XML
	 * @return $triples Array
	 * @param $uri string		
	 */
	public function getJSON($URI) {
		$ser = $this->arc->getRDFJSONSerializer($this->arc_config);
		$doc = $ser->getSerializedTriples($this->getArcTriples($URI));
		return $doc;
	}


	/**
	 * This function retrieves the triples for a uri, but returns them in a format where a subject will point to an array of all its instances
	 * @return $triples Array
	 * @param $uri string		
	 */	
	public function getTriples($uri) {
		$q = "select ?predicate ?object where { <".$uri."> ?predicate ?object . }";	
		$records = $this->executeQuery($q);	
		$xarray = array();
		$records_next = array();
		$records_all = array();
		foreach ($records as $record) { 			
			if (strstr($record['object'], "_:") != false) {
				$xarray[$record['predicate']][$record['object']] = $this->getTriples($record['object']);		
			} else {
					$xarray[$record['predicate']][$uri] = $record['object'];
			}								
		}
		return $xarray;
	}

	/**
	 * Takes a bnode and returns the root URI.
	 * @return $URI 
	 */
	public function backtrackToURI($bnode) {
		$previous_bnode = $this->getPreviousBnode($bnode);
		var_dump($previous_bnode);
		if (strpos($previous_bnode, "_:") !== false) {
			return $this->backtrackToURI($previous_bnode);
		} else {
			return $previous_bnode;
		}
	}
	
	public function followToBNode($URI, $path) {
		$bnode = $URI;
		$_path = explode("->", $path);
		foreach ($_path as $type) {
			$bnode = $this->getNextBnode($bnode, $type);
			$bnode = $bnode[0]['next_bnode'];
		}
		return $bnode;
	}

	public function getLabel($URI, $db="local") {				
		$record = $this->getSomething($URI, "rdfs:label", $db);
		if ($record != "") {
			return $record;
		} else {				
			$record = $this->getSomething($URI, "rdf:label", $db);
			if ($record != "") {
				return $record;
			} else {
				return $URI;
			}
		}
	}	
	

	/**
	 * Retrieves and returns all the impacts of an existing uri
	 * @return $data_type string	
	 * @param $uri string
	 */		
	public function getDataType($URI) {
		$q = "select ?data_type where { " . 
			" <".$URI."> 'http://www.w3.org/2000/01/rdf-schema#type' ?data_type . " . 
			"}";
		$records = $this->executeQuery($q);	
		return $records[0]['data_type'];
	}


	/**
	 * Gets the blank node(s) of a particular type that is a sub-set of the previous bnode
	 * @return $records Array	
	 * @param $previous_bnode string
	 * @param $next_type string
	 */	
	public function getNextBnode($previous_bnode, $next_type) {
		$q = "select ?next_bnode where { " . 
			" <".$previous_bnode."> ?c ?next_bnode . " . 
			"}";	
		$records = $this->executeQuery($q);	
		return $records;
	}	
	
	
	
	/**
	 * Gets the Parent node of a bnode
	 * @return $records Array	
	 * @param $next_bnode string
	 */	
	public function getPreviousBnode($next_bnode) {
		$q = "select ?previous_bnode where { " . 
			"?previous_bnode ?type " . $next_bnode . " . " . 
			"}";	
		$records = $this->executeQuery($q);	
		return $records[0]['previous_bnode'];
	}
	
	
	public function created() {	
		$q = "select ?uri where { " . 
			"?uri rdfs:type ?x . " . 
			"}";	
		$records = $this->executeQuery($q);	
		$b = 0;	
		foreach($records as $record) {
			$b++;
			$q2 = "insert into <http://opensustainability.info/> { " .	
					"<".$record['uri']."> dcterms:created '".date('j F Y', mktime(0, 0, 0, 7, 1+$b, 2010))."' . " . 
			 		"}";
			$this->executeQuery($q2);			
		}

	}
	
}
?>
