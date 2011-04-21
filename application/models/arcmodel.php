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
		//$this->config->load('arc');	  
	    $this->config->load('arc');	
		$this->config->load('arcdb');	
		$this->arc_config = array_merge($this->config->item("arc_info"), $this->config->item("db_arc_info"));
	}	
	
	// Configuration information for accessing the arc store
	
	

	/**
	 * This function is a generic call to the arc store.
	 * @return Array of triples.
	 * @param $q string - query string.
	 */
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
				var_dump($error);
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
		
		$q = "select ?link where { " . 
			" ?link 'http://www.w3.org/2000/01/rdf-schema#type' 'eco:FootprintModel' . " . 
			"}";
		$footprint_records = $this->executeQuery($q);	
		$q = "select ?link where { " . 
			" ?link 'rdfs:type' 'eco:Model' . " . 
			"}";
		$model_records = $this->executeQuery($q);	
		
		$records = array_merge($footprint_records, $model_records);
		foreach ($records as &$record) {
			$q = "select ?name where { " . 
				"<". $record['link'] ."> eco:models ?bnode . " .
				"?bnode 'http://www.w3.org/2000/01/rdf-schema#label' ?name . " .
				"?bnode 'http://www.w3.org/2000/01/rdf-schema#type' 'eco:Product' . " .  
				"}";
			$get_product_name = $this->executeQuery($q);
			if (count($get_product_name) > 0) {
				$record['name'] = $get_product_name[0]['name'];			
			// If it doesnt appear to model a product, get the name of a process	
			} else {
				$q = "select ?name where { " . 
					"<". $record['link'] ."> eco:models ?bnode . " .
					"?bnode 'http://www.w3.org/2000/01/rdf-schema#label' ?name . " .
					"?bnode 'http://www.w3.org/2000/01/rdf-schema#type' 'eco:Process' . " .  
					"}";
				$get_process_name = $this->executeQuery($q);	
				if (count($get_product_name) > 0) {
				$record['name'] = $get_process_name[0]['name'];					
				} else {
					$q = "select ?name where { " . 
						"<". $record['link'] ."> 'http://www.w3.org/2000/01/rdf-schema#label' ?name . " .
						"}";
					$get_model_name = $this->executeQuery($q);
					if (count($get_product_name) > 0) {
						$record['name'] = $get_model_name[0]['name'];					
					} else {
						$record['name'] = "";
					}
				}
			}
		}
		return $records;
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

	public function getFieldValue($URI, $path) {
		$bnode = $this->followToBNode($URI, $path);
		$type = end(explode("->", $path));
		var_dump($bnode);
		echo "----";
		var_dump($type);
		$q = "select ?value where { " . 
			"'" .$bnode . "' 'http://opensustainability.info/vocab#" . $type . "' ?value . " . 			
			"}";
		$records = $this->executeQuery($q);	
		return $records;	
	}

	/**
	 * Retrieves and returns summary information for all existing records
	 * @return $records Array	
	 */
	public function simpleSearch($value = null, $limit = 20, $offset = 0) {
		$URIs = array();
		$q = "select ?uri where { " . 
			" ?uri '" . $this->arc_config['ns']['eco'] . "models' ?bnode . " ;
		if ($value != null) {
			$q .= " ?bnode '" . $this->arc_config['ns']['rdfs'] . "label' ?label . " . 
					"FILTER regex(?label, '" . $value . "', 'i')";
		} 
		$q .= "}" . 
				"LIMIT " . $limit . " " . 
				"OFFSET " . $offset . " ";

		$records = $this->executeQuery($q);	
		foreach ($records as $record) {
			$URIs[] = $record['uri'];
		}
		return $URIs;
	}


	/**
	 * Retrieves and returns summary information for all existing records
	 * @return $records Array	
	 */
	public function oldsimpleSearch($variable, $value) {
		$URIs = array();
		$q = "select ?bnode where { " . 
			" ?bnode '" . $this->arc_config['ns']['rdfs'] . "label' '".$value."' . " . 			
			"}";
		$records = $this->executeQuery($q);	
		var_dump($records);
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
	 * @return $records Array	
	 * @param $uri string
	 */
	public function getConvertedImpactAssessments($URI) {
		$q = "select ?impactCategory ?impactCategoryIndicator ?impactCategoryValue ?impactCategoryUnit where { " . 
			" ?bnode 'http://ontology.earthster.org/eco/core#computedFrom' <".$URI."> . " .
			" ?bnode 'http://www.w3.org/2000/01/rdf-schema#type' 'eco:ImpactAssessment' . " .	
			" ?bnode 'http://ontology.earthster.org/eco/core#hasImpactCategoryIndicatorResult' ?bnode2 . " .
			" ?bnode2 'http://ontology.earthster.org/eco/core#hasImpactAssessmentMethodCategoryDescription' ?bnode3 . " .			 	
			" ?bnode3 'http://ontology.earthster.org/eco/core#hasImpactCategory' ?impactCategory . " .			
			" ?bnode3 'http://ontology.earthster.org/eco/core#hasImpactCategoryIndicator' ?impactCategoryIndicator . " .
			" ?bnode2 'http://ontology.earthster.org/eco/core#hasQuantity' ?bnode4 . " . 			
			" ?bnode4 'http://ontology.earthster.org/eco/core#hasUnitOfMeasure' ?impactCategoryUnit . " . 
			" ?bnode4 'http://ontology.earthster.org/eco/core#hasMagnitude' ?impactCategoryValue . " .				
			"}";				
		$records = $this->executeQuery($q);	
		//var_dump($records);
		foreach ($records as &$record) {
			$record['impactCategory'] = $this->getLabel($record['impactCategory']);
			$record['impactCategoryIndicator'] = $this->getLabel($record['impactCategoryIndicator']);
			$record['impactCategoryUnit'] = $this->getLabel($record['impactCategoryUnit']);
		}
		return $records;
	}
	
	
	
	public function getImpactAssessments($URI) {
		$q = "select ?bnoder where { " . 
			" ?bnode 'http://ontology.earthster.org/eco/core#computedFrom' <".$URI."> . " .
			" ?bnode 'http://www.w3.org/2000/01/rdf-schema#type' 'eco:ImpactAssessment' . " .
			" ?bnode 'http://ontology.earthster.org/eco/core#hasImpactCategoryIndicatorResult' ?bnoder . " .			
			"}";				
		$records = $this->executeQuery($q);	
		$full_records = array();
		foreach($records as $record) {
			$full_record[] = $this->getTriples($record['bnoder']);
		}
		return $full_record;
	}
		
	public function getBibliography($URI) {
		$q = "select ?bibouri where { " . 
			" <".$URI."> 'http://ontology.earthster.org/eco/core#hasDataSource' ?bibouri . " .			
			"}";				
		$records = $this->executeQuery($q);
		$full_record = array();		
		foreach ($records as $record) {
			$link = array('link' => $record['bibouri']);
			$full_record[$record['bibouri']] = array_merge($link, $this->getTriples($record['bibouri']));			
		}
		return $full_record;
	}

	public function getModeled($URI) {
		$q = "select ?bnode where { " . 
			" <".$URI."> 'http://ontology.earthster.org/eco/core#models' ?bnode . " .			
			"}";				
		$records = $this->executeQuery($q);
		$full_record = array();		
		foreach ($records as $record) {
			$link = array('link' => $record['bnode']);
			$full_record[$record['bnode']] = array_merge($link, $this->getTriples($record['bnode']));			
		}
		return $full_record;
	}	
	
	public function getGeography($URI) {
		$q = "select ?geo_uri where { " . 
			" <".$URI."> '" . $this->arc_config['ns']['eco'] . "models' ?bnode . " .
			"?bnode '" . $this->arc_config['ns']['eco'] . "hasGeoLocation' ?geo_uri . " . 			
			"}";				
		$records = $this->executeQuery($q);	
		if (count($records) != 0) {
			return $records;
		} else {
			return false;
		}
	}
	
	public function getLCAsByPublisher($foaf_uri) {
		$q = "select ?uri ?title where { " . 
			" ?uri '" . $this->arc_config['ns']['dcterms'] . "publisher' '" . $foaf_uri . "' . " . 	
			" ?uri '" . $this->arc_config['ns']['eco'] . "models' ?bnode . " . 
			" ?bnode '" . $this->arc_config['ns']['rdfs'] . "label' ?title . " . 		
			"}";				
		$records = $this->executeQuery($q);	
		if (count($records) != 0) {
			return $records;
		} else {
			return false;
		}
	}
	
	
	
	public function getQR($URI) {
		$q = "select ?bnode ?name where { " . 
			" <".$URI."> 'http://ontology.earthster.org/eco/core#models' ?bnode . " .			
			" ?bnode 'http://www.w3.org/2000/01/rdf-schema#type'  'eco:Product' . " .
			" ?bnode 'http://www.w3.org/2000/01/rdf-schema#label'  ?name . " .
			"}";				
		$records = $this->executeQuery($q);
		if (count($records) > 0) {
			$q = "select ?magnitude ?unit where { " . 
				" ?exchange_bnode 'http://ontology.earthster.org/eco/core#hasEffect' ?effect_bnode . " .
				" ?exchange_bnode 'http://ontology.earthster.org/eco/core#hasQuantity' ?quantity_bnode . " .
				" ?quantity_bnode 'http://ontology.earthster.org/eco/core#hasMagnitude' ?magnitude . " .
				" ?quantity_bnode 'http://ontology.earthster.org/eco/core#hasUnitOfMeasure' ?unit . " .
				" ?effect_bnode 'http://ontology.earthster.org/eco/core#hasTransferable' <" . $records[0]['bnode'] . "> . " .			
				"}";
			$full_records = $this->executeQuery($q);
			$full_records[0]['name'] = $records[0]['name'];
			return $full_records;
		} else {
			return false;
		}
	}
	
	
	
	public function getExchanges($URI) {
		$q = "select ?bnode where { " . 
			" <".$URI."> 'http://ontology.earthster.org/eco/core#hasUnallocatedExchange' ?bnode . " .				
			"}";				
		$records = $this->executeQuery($q);
		$q2 = "select ?bnode where { " . 	
			" <".$URI."> 'http://ontology.earthster.org/eco/core#hasAllocatedExchange' ?bnode . " .				
			"}";				
		$records2 = $this->executeQuery($q2);
		$records = array_merge($records, $records2);
		$full_record = array();		
		foreach ($records as $record) {
			$link = array('link' => $record['bnode']);
			$full_record[$record['bnode']] = array_merge($link, $this->getTriples($record['bnode']));			
		}
		return $full_record;
	}
	
	
	public function getLabel($URI) {
		$q = "select ?name where { " . 
			" <".$URI."> 'http://www.w3.org/2000/01/rdf-schema#label'  ?name . " .
			"}";				
		$records = $this->executeQuery($q);
		if (count($records) > 0) {
			return $records[0]['name'];
		} else {
			return $URI;
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
			" <".$previous_bnode."> 'http://opensustainability.info/vocab#".$next_type."' ?next_bnode . " . 
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
		$q = "select ?uri ?created ?name where { " . 
			"?uri dcterms:created ?created . " . 
			"?uri 'http://ontology.earthster.org/eco/core#models' ?bnode . " .			
			" ?bnode 'http://www.w3.org/2000/01/rdf-schema#type'  'eco:Product' . " .
			" ?bnode 'http://www.w3.org/2000/01/rdf-schema#label'  ?name . " .
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
	
	
	public function searchFoaf($info) {
		$vars = "";
		$q = "";
		if (isset($info['uri']) == true) {
			$q .= "<" . $info['uri'] . "> 'http://xmls.com/foaf/0.1/Person' ?person . ";		
		} else {
			$vars .= "?uri ";
			$q .= "?uri 'http://xmls.com/foaf/0.1/Person' ?person . ";
		}
		if (isset($info['firstName']) == true) {
			$q .= "?person 'http://xmls.com/foaf/0.1/firstName' '" . $info['firstName'] . "' . ";		
		} else {
			$vars .= "?firstName ";
			$q .= "?person 'http://xmls.com/foaf/0.1/firstName' ?firstName . ";	
		}
		if (isset($info['lastName']) == true) {
			$q .= "?person 'http://xmls.com/foaf/0.1/lastName' '" . $info['lastName'] . "' . ";		
		} else {
			$vars .= "?lastName ";
			$q .= "?person 'http://xmls.com/foaf/0.1/lastName' ?lastName . ";	
		}
		if (isset($info['email']) == true) {
			$q .= "?person 'http://xmls.com/foaf/0.1/mbox_sha1sum' '" . $info['email'] . "' . ";		
		} else {
			$vars .= "?email ";
			$q .= "?person 'http://xmls.com/foaf/0.1/mbox_sha1sum' ?email . ";	
		}

		$q = "select " . $vars . "where { " . $q . "}";
		$records = $this->executeQuery($q);	
		foreach ($records as &$record) {
			foreach($info as $name=>$field)
			$record[$name] = $field;
		}
		if (count($records) > 0) {
			return $records;
		} else {
			return false;
		}
	}
	
}
?>