<?php
class Lcamodel extends FT_Model{
    public function Lcamodel(){
        parent::__construct();
		$this->load->model(Array('unitmodel','geographymodel','ecomodel','opencycmodel','dbpediamodel'));
		$this->arc_config['store_name'] = "footprinted";
    }

	/**
	 * Takes an array that represents nested triples of only the geography of an lca, retrieves some info (coordinates and a name) from the geonames dataset, and turns it into a cleaner, simpler array for the purpose of viewing
	 * @return $converted_dataset Array	
	 */
	public function convertGeography($dataset){
		$converted_dataset = array();
		if ($dataset != false) {
			foreach($dataset as $geo) {
				$converted_dataset[] = $this->geographymodel->getPointGeonames($geo['geo_uri']);
			}
			return $converted_dataset;
		} else {
			return false;
		}
	}

	/**
	 * Takes an array of impact assessment information that represents nested triples and turns it into a cleaner, simpler array for the purpose of viewing
	 * @return $converted_dataset Array	
	 */
	public function convertImpactAssessments($dataset) {
		$converted_dataset = array();
		$dataset = $dataset[0];
		foreach ($dataset[$this->arc_config['ns']['eco'].'hasImpactCategoryIndicatorResult'] as $key=>$_record) {
			foreach ($_record[$this->arc_config['ns']['eco']."hasImpactAssessmentMethodCategoryDescription"] as $__record) {
				foreach($__record[$this->arc_config['ns']['eco']."hasImpactCategory"] as $___record) {
					$converted_dataset[$key]['impactCategory'] = $this->ecomodel->makeToolTip($___record);
				} 
				foreach($__record[$this->arc_config['ns']['eco']."hasImpactCategoryIndicator"] as $___record) {
					$converted_dataset[$key]['impactCategoryIndicator'] =  $this->ecomodel->makeToolTip($___record);
				}					
			} 	
			foreach ($_record[$this->arc_config['ns']['eco']."hasQuantity"] as $__record) {
				foreach($__record[$this->arc_config['ns']['eco']."hasMagnitude"] as $___record) {
					$converted_dataset[$key]['amount'] = $___record;
				}
				foreach($__record[$this->arc_config['ns']['eco']."hasUnitOfMeasure"] as $___record) {
					$converted_dataset[$key]['unit'] = $this->unitmodel->makeToolTip($___record);
				}		
			}	
			if (isset($converted_dataset[$key]['unit']) == false) {
				$converted_dataset[$key]['unit'] = "?";
			}									
			if (isset($converted_dataset[$key]['amount']) == false) {
				$converted_dataset[$key]['amount'] = "?";
			}
			if (isset($converted_dataset[$key]['impactCategory']) == false) {
				$converted_dataset[$key]['impactCategory'] = "?";
			}
			if (isset($converted_dataset[$key]['impactCategoryIndicator']) == false) {
				$converted_dataset[$key]['impactCategoryIndicator'] = "?";
			}
		}
		return $converted_dataset; 
	}	

	/**
	 * Takes an array of exchange (inputs and outputs) information that represents nested triples and turns it into a cleaner, simpler array for the purpose of viewing
	 * @return $converted_dataset Array	
	 */	
	public function convertExchanges($dataset){
			$converted_dataset = array();
			foreach($dataset as $key=>$record) {		
				foreach($record[$this->arc_config['ns']['eco']."hasEffect"] as $_record) {
					foreach ($_record[$this->arc_config['ns']['rdfs']."type"] as $__record) {
						if ($__record == $this->arc_config['ns']['eco']."Output" || $__record == $this->arc_config['ns']['eco']."Input") {
							$converted_dataset[$key]["direction"] = str_replace($this->arc_config['ns']['eco'], "", $__record);
						} 
					}
					if (isset($_record[$this->arc_config['ns']['eco']."hasTransferable"]) == true) {
						foreach($_record[$this->arc_config['ns']['eco']."hasTransferable"] as $transferable) {
							if (is_array($transferable) == true) {
								foreach($transferable[$this->arc_config['ns']['rdfs']."label"] as $label) {
									$converted_dataset[$key]['name'] = $label;
								}
							} else {
								$converted_dataset[$key]['name'] = $transferable;
							}
						} 
					}
					if (isset($_record[$this->arc_config['ns']['eco']."hasFlowable"]) == true) {
						foreach($_record[$this->arc_config['ns']['eco']."hasFlowable"] as $flowable) {
							$converted_dataset[$key]['name'] = str_replace("eco", "", $flowable);
						} 	
					}							
				}
				foreach ($record[$this->arc_config['ns']['eco']."hasQuantity"] as $_record) {
					foreach($_record[$this->arc_config['ns']['eco']."hasMagnitude"] as $magnitude) {
						$converted_dataset[$key]['amount'] = $magnitude;
					} 
					foreach($_record[$this->arc_config['ns']['eco']."hasUnitOfMeasure"] as $unitOfMeasure) {
						$converted_dataset[$key]['unit'] = $this->unitmodel->makeToolTip($unitOfMeasure);
					} 
				}
			}
					
			return $converted_dataset; 

		}

	/**
	 * Takes an array of quantitative reference information that represents nested triples and turns it into a cleaner, simpler array for the purpose of viewing
	 * @return $converted_dataset Array	
	 */		
	public function convertQR($dataset){
		$converted_dataset = array();		
		foreach($dataset as $key=>$record) {		
			$converted_dataset['name'] = $record['name'];
			$converted_dataset['amount'] = $record['magnitude'];
			$converted_dataset['unit'] = $this->unitmodel->makeToolTip($record['unit']);
		}		
		return $converted_dataset; 
	}	

	/**
	 * Takes an array of model information that represents nested triples and turns it into a cleaner, simpler array for the purpose of viewing
	 * @return $converted_dataset Array	
	 */	
	public function convertModeled($dataset){
		$converted_dataset = array();
		foreach($dataset as $key=>$record) {		
			if(isset($record[$this->arc_config['ns']['rdfs']."type"]) == true) {
				foreach($record[$this->arc_config['ns']['rdfs']."type"] as $type) {
					foreach($record[$this->arc_config['ns']['rdfs']."label"] as $label) {
							$converted_dataset['type'] =$this->ecomodel->makeToolTip($type);
					}				
				}				
			}
		}		
		return $converted_dataset; 
	}

	/**
	 * Gets all LCAs and returns the uri and name
	 * @return $records Array	
	 */
	public function getRecords() {
		$qfm = "SELECT DISTINCT ?uri ?label WHERE {GRAPH ?uri {?s rdfs:type eco:FootprintModel . ?s eco:models ?bnode . ?bnode rdfs:label ?label . }}";
		$qm = "SELECT DISTINCT ?uri ?label WHERE {GRAPH ?uri {?s rdfs:type eco:Model . ?s eco:models ?bnode . ?bnode rdfs:label ?label . }}";
		$records = array_merge($this->executeQuery($qm),$this->executeQuery($qfm));
		return $records;
	}
	/**
	 * Gets all LCAs and returns the uri and name
	 * @return $records Array	
	 */
	public function oldgetRecords() {
				$this->arc_config['store_name'] = "slow_footprinted";
		$qfm = "SELECT DISTINCT ?uri ?label WHERE { ?uri rdfs:type eco:FootprintModel . ?uri eco:models ?bnode . ?bnode rdfs:label ?label . }";
		$qm = "SELECT DISTINCT ?uri ?label WHERE { ?uri rdfs:type eco:Model . ?uri eco:models ?bnode . ?bnode rdfs:label ?label . }";
		$records = array_merge($this->executeQuery($qm),$this->executeQuery($qfm));
		return $records;
	}
	/**
	 * Searches arrays. Can be based on a value that is matched to the product or process label.
	 * @return $records Array	
	 */
	 public function simpleSearch($value = null, $limit = 20, $offset = 0) {
		$URIs = array();
		$q = "select DISTINCT ?uri ?label where { GRAPH ?uri { " . 
			" ?s eco:models ?bnode . "  .
			" ?bnode rdfs:label ?label . " ;
		if ($value != null) {
			$q .= "FILTER regex(?label, '" . $value . "', 'i')";
		} 
		$q .= "} }" . 
				"LIMIT " . $limit . " " . 
				"OFFSET " . $offset . " ";
		$records = $this->executeQuery($q);	
		return $records;
	}	

	public function getImpactAssessments($URI) {
		$this->arc_config['store_name'] = "footprinted";
		$q = "select * from <".$URI."> WHERE { ?bnode eco:computedFrom ?p . }";
		$records = $this->executeQuery($q);	
		$full_record = array();
		foreach($records as $record) {
			$full_record[] = $this->getTriples($record['bnode'],$URI);
		}
		return $full_record;
	}

	public function getModeled($URI) {
		$q = "select ?bnode from <".$URI."> where { " . 
			" ?s eco:models ?bnode . " .			
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
		$q = "select * from <".$URI."> WHERE { ?s eco:models ?bnode . ?bnode eco:hasGeoLocation ?geo_uri . }";
		$records = $this->executeQuery($q);	
		return $records;
	}
	
	public function getLCAsByPublisher($foaf_uri) {
		$q = "select ?uri ?title where { GRAPH ?uri {" . 
			" ?s dcterms:publisher '" . $foaf_uri . "' . " . 	
			" ?s eco:models ?bnode . " . 
			" ?bnode rdfs:label ?title . " . 		
			"} }" . 
			"LIMIT 10 ";

		$records = $this->executeQuery($q);	

		if (count($records) != 0) {
			return $records;
		} else {
			return false;
		}
	}
	

	// Get external links for a resource (DBPedia...)
	public function getSameAs($URI){
		$q = "select ?uri from <".$URI."> where { " . 
			" ?s eco:models ?bnode . " .			
			" ?bnode rdfs:type  eco:Product . " .
			" ?bnode owl:sameAs  ?uri . " .
			"}";
		$records = $this->executeQuery($q);
		return $records;
	}
	
	// Convert 
	public function convertLinks($dataset){
		$converted_dataset = array();		
		foreach($dataset as $key=>$record) {		
			$converted_dataset[$record['uri']]['uri'] = $record['uri'];
		//	if (strpos($converted_dataset['uri'],"opencyc") !== false ){
				$results = $this->opencycmodel->getAll($record['uri']);
				foreach($results as $r){
					switch ($r['c']) {
					    case 'http://www.w3.org/2000/01/rdf-schema#label':
							$converted_dataset[$record['uri']]['title'] =  $r['object'];
							break;	
					    case 'http://www.w3.org/2002/07/owl#sameAs': 			
							if (strpos( $r['object'],"dbpedia")==true){
								$converted_dataset[$record['uri']]['dbpedia'] =  $r['object'];
								// Change the address to get the triples instead
								$address = str_replace("resource","data",$r['object']);
								$this->dbpediamodel->loadDBpediaEntry($address);	
								$converted_dataset[$record['uri']]['description'] = $this->dbpediamodel->getDBpediaDescription($r['object']);
								$converted_dataset[$record['uri']]['img'] = $this->dbpediamodel->getImageURL($r['object']);
							}
							break;
					    case 'http://www.w3.org/2000/01/rdf-schema#comment': 
							$converted_dataset[$record['uri']]['info'] =  $r['object']; 
							break;
					}
				}
		//	}
		}		
		return $converted_dataset;
		
	}
	
	/**
	 * Get the qr for a LCA
	 * @return $records Array	
	 */	
	public function getQR($URI) {
		$q = "select * from <".$URI."> WHERE { " . 
		" ?s eco:models ?bnode . " .			
		" ?bnode rdfs:type  eco:Product . " .
		" ?bnode rdfs:label  ?name . " .
		" ?exchange_bnode eco:hasEffect ?effect_bnode . " .
		" ?exchange_bnode eco:hasQuantity ?quantity_bnode . " .
		" ?quantity_bnode eco:hasMagnitude ?magnitude . " .
		" ?quantity_bnode eco:hasUnitOfMeasure ?unit . " .
		" ?effect_bnode eco:hasTransferable ?bnode . " . 
		" } ";
		$records = $this->executeQuery($q);	
		return $records;
	}
		
	/**
	 * Gets all the exchanges (inputs and outputs) for an LCA
	 * @return $full_record Array	
	 */
	public function getExchanges($URI) {
		$q1 = "select * from <".$URI."> WHERE { " . 
		" ?s eco:hasUnallocatedExchange ?bnode . }";
		$q2 = "select * from <".$URI."> WHERE { " . 
		" ?s eco:hasAllocatedExchange ?bnode . }";
		$q3 = "select * from <".$URI."> WHERE { " . 
		" ?s eco:hasReferenceExchange ?bnode . }";
		$records = array_merge($this->executeQuery($q3),array_merge($this->executeQuery($q1),$this->executeQuery($q2)));	
		$full_record = array();		
		foreach ($records as $record) {
			$link = array('link' => $record['bnode']);
			$full_record[$record['bnode']] = array_merge($link, $this->getTriples($record['bnode'], $URI));			
		}
		return $full_record;
	}
	
	/**
	 * Gets all LCAs in the reverse order of submission to the db
	 * @return $full_record Array	
	 */
	public function latest($limit) {
		$q = "select ?uri ?created ?name where { GRAPH ?uri {" . 
			" ?s dcterms:created ?created . " . 
			" ?s eco:models ?bnode . " .			
			" ?bnode rdfs:#type  eco:Product . " .
			" ?bnode rdfs:label  ?name . " .
			"} } ORDER BY DESC(?created)";	
		$records = $this->executeQuery($q);	
		return $records;
	}

	/**
	 * Adds a triple to an LCA record that specifies the the product describes the same concept as another linked data record 
	 * @param $ft string
	 * @param $oc string	
	 */
   public function addSameAs($ft,$oc) {
		$triples = array();
       $q = "select ?bnode from <http://footprinted.org/".$ft.".rdf> where { " . 
           " ?s eco:models ?bnode . " .            
           " ?bnode rdfs:type  eco:Product . " .
           "}";              
       $records = $this->executeQuery($q);
/*
       $triples[] = array(
           's' => $records[0]['bnode'],
           'p' => 'owl:sameAs',
           'o' => 'http://sw.opencyc.org/concept/' . $oc
       ); 
*/
		$q = "insert into <http://footprinted.org/".$ft.".rdf> { " . 
		"<".$records[0]['bnode']."> owl:sameAs <"."http://sw.opencyc.org/concept/". $oc.">" . 
		"}";
		$this->executeQuery($q);
	   //$this->addTriples($triples);
   }

	/**
	 * Adds a triple to an LCA record that specifies the the product describes a concept that is a category of a linked data record 
	 * @param $ft string
	 * @param $oc string		
	 */

   public function addDbpedia($ft,$oc) {
		$triples = array();
       $q = "select ?bnode where { " . 
           " <http://footprinted.org/rdfspace/lca/".$ft."> eco:models ?bnode . " .            
           " ?bnode rdfs:type  eco:Product . " .
           "}";              
       $records = $this->executeQuery($q);
/*
       $triples[] = array(
           's' => $records[0]['bnode'],
           'p' => 'owl:sameAs',
           'o' => 'http://sw.opencyc.org/concept/' . $oc
       ); 
*/
		//$q = "insert into <http://footprinted.org/> { " . 
		//"<".$records[0]['bnode']."> owl:sameAs <". $oc.">" . 
		//"}";
		$this->executeQuery($q);
	   //$this->addTriples($triples);
   }

   public function addCategory($ft,$oc) {
		$triples = array();
       $q = "select ?bnode from <".$ft."> where { " . 
           " ?s eco:models ?bnode . " .            
           " ?bnode rdfs:type  eco:Product . " .
           "}";  
       $records = $this->executeQuery($q);
		$q = "insert into <".$ft."> { " . 
		"<".$records[0]['bnode']."> eco:hasCategory <"."http://sw.opencyc.org/concept/". $oc.">" . 
		"}";
		$this->executeQuery($q);
   }

	/**
	 * Searches the opencyc db for string matches that may be same concepts
	 * @return $suggestions Array	
	 */	
	public function getOpenCycSuggestions($uri) {
		$q = "select ?label from <".$uri."> where { " . 
            " ?s eco:models ?bnode . " .            
            " ?bnode rdfs:type eco:Product . " .
			" ?bnode rdfs:label ?label . " .
            "}";              
        $records = $this->executeQuery($q);
		$q = "select ?label from <".$uri."> where { " . 
            " ?s eco:models ?bnode . " .            
            " ?bnode rdfs:type eco:Process . " .
			" ?bnode rdfs:label ?label . " .
            "}";              
        $records = array_merge($records,$this->executeQuery($q));
		$suggestions = array();
		foreach ($records as $record) {
			$send_array = array($record['label']);
			$_suggestions = $this->opencycmodel->getSuggestedPages($send_array);
			if (count($_suggestions) > 0) {
				$suggestions = array_merge($suggestions, $_suggestions);
			}
		}
		return $suggestions;
	}	

	/**
	 * Gets all the categories for an LCA
	 * @return $categories Array	
	 */	
	public function getCategories($URI){		
		$q = "select ?uri from <".$URI."> where { " . 
			" ?s eco:models ?bnode . " .			
			" ?bnode rdfs:type  eco:Product . " .
			" ?bnode eco:hasCategory  ?uri . " .
			"}";
		$records = $this->executeQuery($q);
		if (count($records) > 0) {
			$categories = array();
			foreach ($records as $record) {
				$categories[] = array(
					'uri'=>$record['uri'],
					'label'=>$this->opencycmodel->getOpenCycLabel($record['uri'])
				);
			}
			return $categories;
		} else {
			return false;
		}
	}

	/**
	 * Gets all the distinct categories that are associated with LCAs
	 * @return $categories Array	
	 */	
	public function getAllUsedCategories() {
		$q = "select DISTINCT ?uri where { GRAPH ?u {" . 
			" ?bnode eco:hasCategory  ?uri . " .
			"} }";
		$records = $this->executeQuery($q);
		if (count($records) > 0) {
			$categories = array();
			foreach ($records as $record) {
				$categories[] = array(
					'uri'=>$record['uri'],
					'label'=>$this->opencycmodel->getOpenCycLabel($record['uri'])
				);
			}
			return $categories;
		} else {
			return false;
		}		
	}

	/**
	 * Gets all LCAs that are associated with a particular category
	 * @return $records Array	
	 */
	public function getLCAsByCategory($URI) {		
		$q = "select ?uri where { GRAPH ?uri {" . 
			" ?s eco:models ?bnode . " .			
			" ?bnode rdfs:type  eco:Product . " .
			" ?bnode eco:hasCategory  <" . $URI . "> . " .
			"} }";
			
		$records = $this->executeQuery($q);
		foreach ($records as &$record) {
			$q = "select ?label from <".$record['uri']."> where { " . 
				" ?s eco:models ?bnode . " .
				" ?bnode rdfs:label ?label . " .
				" ?bnode rdfs:type eco:Product . " .  
				"}";
			$get_product_name = $this->executeQuery($q);
			if (count($get_product_name) > 0) {
				$record['label'] = $get_product_name[0]['label'];			
			// If it doesnt appear to model a product, get the name of a process	
			} else {
				$q = "select ?label from <".$record['uri']."> where { " . 
					"?s eco:models ?bnode . " .
					"?bnode rdfs:label ?label . " .
					"?bnode rdfs:type eco:Process . " .  
					"}";
				$get_process_name = $this->executeQuery($q);	
				if (count($get_product_name) > 0) {
				$record['label'] = $get_process_name[0]['label'];					
				} else {
					$q = "select ?label from <".$record['uri']."> where { " . 
						"?s rdfs:label ?label . " .
						"}";
					$get_model_name = $this->executeQuery($q);
					if (count($get_product_name) > 0) {
						$record['label'] = $get_model_name[0]['label'];					
					} else {
						$record['label'] = "";
					}
				}
			}
		}
		return $records;
	}

}
