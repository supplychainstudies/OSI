<?php

/**
 * This model uses the Arc2 library to insert, edit, and retrieve rdf data from the arc store 
 * 
 * @package opensustainability
 * @subpackage models
 */

class ArcModel extends Model{
	
	/**
	 * @ignore
	 */
	function ArcModel(){
		parent::Model();
		$this->load->library('arc2/ARC2', '', 'arc');			
	}	
	
	// Configuration information for accessing the arc store
	public $config = array(
	  /* db */
	  'db_host' => 'rhodium.media.mit.edu', /* default: localhost */
	  'db_name' => 'opensustainability',
	  'db_user' => 'root',
	  'db_pwd' => 'suppl1ch41n',
	  /* store */
	  'store_name' => 'arc_os',
	  'ns' => array(
<<<<<<< .mine
     'lca' => 'http://opensustainability.info/vocab#',
     'dcterms' => 'http://purl.org/dc/terms/',
	 'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
	 'sioc' => 'http://rdfs.org/sioc/ns'
=======
      'lca' => 'http://opensustainability.info/vocab#',
	  'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#'
>>>>>>> .r21
   ),		    
	  'endpoint_features' => array(
	    'select', 'construct', 'ask', 'describe', // allow read
	    'load', 'insert', 'delete',               // allow update
	    'dump'                                    // allow backup
	  )

	);


	/**
	 * This function is a generic call to the arc store.
	 * @return Array of triples.
	 * @param $q string - query string.
	 */
	public function executeQuery($q) {
		$store = $this->arc->getStore($this->config);

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
	 * This function takes an array with subject,predicate,object rows and turns it into a triple store "insert" statement, then executes it
	 * @return Null
	 * @param $triples Array		
	 */
	public function addTriples($triples) {	
		$q = "insert into <http://opensustainability.info/> { ";	
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
		$ser = $this->arc->getRDFXMLSerializer($this->config);
		$doc = $ser->getSerializedTriples($this->getArcTriples($URI));
		return $doc;
	}
	
	
	/**
	 * This function returns triples in JSON+XML
	 * @return $triples Array
	 * @param $uri string		
	 */
	public function getJSON($URI) {
		$ser = $this->arc->getRDFJSONSerializer($this->config);
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
	 * This function retrieves the triples for a part of a uri by using the $path var to iterate down the hierarchy to the desired section, but returns them in a format where a subject will point to an array of all its instances
	 * @return $triples Array
	 * @param $uri string		
	 * @param $path string
	 * @param $stage string
	 */		
	public function getStage($uri, $path, $stage) {	
		$q = "select ?instance where { ";
		$next_bnode = "<".$uri.">";
		if ($path != "") {
			// iterate down the heirarchy to find the blank node of the desired section
			foreach(explode("->", $path) as $level) {
				$q .= $next_bnode . " 'http://opensustainability.info/vocab#".$level."' ?".$level."_bnode . ";
				$next_bnode = "?".$level."_bnode";
			}
		}
		$q .= $next_bnode . " 'http://opensustainability.info/vocab#".$stage."' ?instance . " . 
			"}";	
		
		$records = $this->executeQuery($q);	
		$instance = array();
		// Retrieve that section
		foreach ($records as $row) {
			$instance[$row['instance']] = $this->getTriples($row['instance']);
		}
		if (count($instance) > 0) {
			return array('http://opensustainability.info/vocab#'.$stage => $instance);
		} else {
			return false;
		}
	}



	/**
	 * Retrieves and returns summary information for all existing records
	 * @return $records Array	
	 */
	public function getRecords() {
		$q = "select ?link ?processName ?qrName ?qrAmount ?qrUnit where { " . 
			" ?link 'http://opensustainability.info/vocab#lifeCycleInventory' ?lci_bnode . " . 			
			" ?lci_bnode 'http://opensustainability.info/vocab#process' ?process_bnode . " . 
			" ?process_bnode 'http://opensustainability.info/vocab#processDescription' ?processdescription_bnode . " . 				
			" ?processdescription_bnode 'http://opensustainability.info/vocab#processName' ?processName . " .
			" ?processdescription_bnode 'http://opensustainability.info/vocab#quantitativeReference' ?qr_bnode . " . 
			" ?qr_bnode 'http://opensustainability.info/vocab#quantitativeReferenceName' ?qrName . " . 
			" ?qr_bnode 'http://opensustainability.info/vocab#quantitativeReferenceAmount' ?qrAmount . " .				
			" ?qr_bnode 'http://opensustainability.info/vocab#quantitativeReferenceUnit' ?qrUnit . " . 
			"}";
		$records = $this->executeQuery($q);	
		return $records;
	}



	/**
	 * Retrieves and returns summary information for all existing records
	 * @return $records Array	
	 */
	public function backtrackToURI($bnode) {
		$previous_bnode = $this->getPreviousBnode($bnode);
		if (strpos($previous_bnode, "_:") !== false) {
			return $this->backtrackToURI($previous_bnode);
		} else {
			return $previous_bnode;
		}
	}



	/**
	 * Retrieves and returns summary information for all existing records
	 * @return $records Array	
	 */
	public function simpleSearch($variable, $value) {
		$URIs = array();
		$q = "select ?bnode where { " . 
			" ?bnode 'http://opensustainability.info/vocab#" . $variable . "' '".$value."' . " . 			
			"}";
		$records = $this->executeQuery($q);	
		foreach($records as $record) {
			$URIs[] = $this->backtrackToURI($record['bnode']);
		}
		return $URIs;
	}



	/**
	 * Retrieves and returns all the impacts of an existing uri
	 * @return $records Array	
	 * @param $uri string
	 */
	public function getImpacts($URI) {
		$q = "select ?impactCategory ?impactCategoryValue ?impactCategoryUnit where { " . 
			" <".$URI."> 'http://opensustainability.info/vocab#impactAssessment' ?impactAssessment . " .
			" ?impactAssessment 'http://opensustainability.info/vocab#classification' ?classification . " .				 	
			" ?classification 'http://opensustainability.info/vocab#impactCategory' ?impactCategory . " . 			
			" ?classification 'http://opensustainability.info/vocab#impactCategoryValue' ?impactCategoryValue . " . 
			" ?classification 'http://opensustainability.info/vocab#impactCategoryUnit' ?impactCategoryUnit . " .
			"}";
		$records = $this->executeQuery($q);	
		foreach ($records as &$record) {
			$record['URI'] = $URI;
		}
		return $records;
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
		$q = "select ?nextbnode where { " . 
			" <".$previous_bnode."> 'lca:".$next_type."' ?process_bnode . " . 
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
	
	
	
	/**
	 * Gets the Parent node of a bnode
	 * @return $records Array	
	 * @param $next_bnode string
	 */	
	public function latest($limit) {
		$q = "select ?uri ?created ?processName where { " . 
			"?uri dcterms:created ?created . " . 
			"?uri 'http://opensustainability.info/vocab#lifeCycleInventory' ?lci_bnode . " . 			
			"?lci_bnode 'http://opensustainability.info/vocab#process' ?process_bnode . " . 
			"?process_bnode 'http://opensustainability.info/vocab#processDescription' ?processdescription_bnode . " . 				
			"?processdescription_bnode 'http://opensustainability.info/vocab#processName' ?processName . " .
			"} ORDER BY DESC(?created)";	
		$records = $this->executeQuery($q);	
		return $records;
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
	
	
	public function getComments($uri) {
		$q = "select ?post ?title ?comment ?created ?author where { " . 
			"<" . $uri . "> sioc:post ?post . " . 
			"?post dcterms:title ?title . " . 
			"?post dcterms:created ?created . " .
			"?post sioc:content ?comment . " .
			"?post sioc:hasCreator ?account . " .			
			"?account sioc:userAccount ?author . " . 
			"}";
			//, ?comment, ?title, ?author, ?created	
		$records = $this->executeQuery($q);	
		$comments = $records;
			if(count($records) > 0) {
				$count = 0;
				foreach ($records as $record) {
					$replies = $this->getComments($record['post']);
					if(count($replies) > 0) {
						$comments[$count]['replies'] = $replies;
					}
					$count++;
				}
				return $comments;		
			}
	}
	
	
	
}
?>