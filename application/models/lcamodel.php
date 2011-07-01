<?php
class Lcamodel extends FT_Model{
    public function Lcamodel(){
        parent::__construct();
		$this->load->model(Array('unitmodel','geographymodel','ecomodel','opencycmodel','dbpediamodel'));
		$this->arc_config['store_name'] = "ECOSPOLDTESTfootprinted";
    }


	public function convertGeography($dataset){
		if ($dataset != false) {
			$converted_dataset = array();
			foreach($dataset as $geo) {
				$converted_dataset[] = $this->geographymodel->getPointGeonames($geo['geo_uri']);
			}
			return $converted_dataset;
		} else {
			return false;
		}
	}

	public function convertImpactAssessments($dataset) {
		if ($dataset != false) {
		$converted_dataset = array();		
		foreach($dataset as $key=>$_record) {	
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
		} else {
			return false;
		}
	}	
	
	public function convertExchanges($dataset){
			if ($dataset != false) {
				$converted_dataset = array();
				var_dump($dataset);
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
								if (is_array($flowable) == true) {
									foreach($flowable[$this->arc_config['ns']['rdfs']."label"] as $label) {
										$converted_dataset[$key]['name'] = $label;
									}
								} else {
									$converted_dataset[$key]['name'] = $flowable;
								}
							} 	
						}							
					}
					foreach ($record[$this->arc_config['ns']['eco']."hasQuantity"] as $_record) {
						if (isset($_record[$this->arc_config['ns']['eco']."hasMagnitude"]) == true) {
							foreach($_record[$this->arc_config['ns']['eco']."hasMagnitude"] as $magnitude) {
								$converted_dataset[$key]['amount'] = $magnitude;
							} 
						}
						if (isset($_record[$this->arc_config['ns']['ecoUD']."meanValue"]) == true) {
							foreach($_record[$this->arc_config['ns']['ecoUD']."meanValue"] as $magnitude) {
								$converted_dataset[$key]['amount'] = $magnitude;
							} 
						}
						if (isset($_record[$this->arc_config['ns']['eco']."hasUnitOfMeasure"]) == true) {
							foreach($_record[$this->arc_config['ns']['eco']."hasUnitOfMeasure"] as $unitOfMeasure) {
								$converted_dataset[$key]['unit'] = $this->unitmodel->makeToolTip($unitOfMeasure);
							} 
						}
					}
				}
				return $converted_dataset; 	
			} else {
				return false;
			}
		}
		
	public function convertQR($dataset){
		$converted_dataset = array();		
		foreach($dataset as $key=>$record) {		
			$converted_dataset['name'] = $record['name'];
			$converted_dataset['amount'] = $record['magnitude'];
			$converted_dataset['unit'] = $this->unitmodel->makeToolTip($record['unit']);
		}		
		return $converted_dataset; 
	}	
	
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
	 * Retrieves and returns summary information for all existing records
	 * @return $records Array	
	 */
	 public function getRecords() {
		$q = "select ?label ?uri where { " . 
			"?uri rdfs:label ?label . " .
			"{ ?uri rdfs:type eco:FootprintModel . } UNION { ?uri rdfs:type eco:Model . } UNION { ?uri rdfs:type eco:LCAModel . }" . 
			"}";
		$records = $this->executeQuery($q);	
		return $records;
	}
/*	
	 public function fixLabels() {
		$q = "select ?uri where { " . 
			"{ ?uri rdfs:type eco:FootprintModel . } UNION { ?uri rdfs:type eco:Model . } UNION { ?uri rdfs:type eco:LCAModel . }" . 
			"}";
		$records = $this->executeQuery($q);	
		foreach ($records as $record) {
			$q = "select ?label where { " . 
				"<".$record['uri']."> rdfs:label ?label . " .
				"}";
			$get_label = $this->executeQuery($q);
			if (count($get_label) == 0) {
				$q = "select ?label where { " . 
					"<".$record['uri']."> eco:models ?node . " .
					"?node rdfs:label ?label . " .
					//"?node rdfs:type eco:Process . " .
					"}";
				$get_process_label = $this->executeQuery($q);
				var_dump($get_process_label);
				$triples = array(
					array(
						's' => $record['uri'],
						'p' => "rdfs:label",
						'o' => $get_process_label[0]['label']
					)
				);
				var_dump($triples);	
				$this->addTriples($triples);			
			}
		}
		return $records;
	}	
*/	
	/**
	 * Retrieves and returns summary information for all existing records
	 * @return $records Array	
	 */
	 public function simpleSearch($value = null, $limit = 20, $offset = 0) {
		$URIs = array();
		$q = "select DISTINCT ?uri ?label ?created where { " . 
			" ?uri eco:models ?bnode . "  .
			" ?bnode rdfs:label ?label . " .
			" ?bnode dcterms:created ?created . ";		
		if ($value != null) {
			$q .= "FILTER regex(?label, '" . $value . "', 'i')";
		} 
		$q .= "}" . 
				"LIMIT " . $limit . " " . 
				"OFFSET " . $offset . " ".
				"ORDER BY DESC(?created) ";

		$records = $this->executeQuery($q);	
		return $records;
	}	
	
	public function getImpactAssessments($URI) {
		$q = "select ?bnoder where { " . 
			" ?bnode eco:computedFrom <".$URI."> . " .
			" ?bnode rdfs:type eco:ImpactAssessment . " .
			" ?bnode eco:hasImpactCategoryIndicatorResult ?bnoder . " .			
			"}";				
		$records = $this->executeQuery($q);	
		$full_record = array();
		foreach($records as $record) {
			$full_record[] = $this->getTriples($record['bnoder']);
		}
		return $full_record;
	}



	public function getModeled($URI) {
		$q = "select ?bnode where { " . 
			" <".$URI."> eco:models ?bnode . " .			
			"}";				
		$records = $this->executeQuery($q);
		$full_record = array();		
		foreach ($records as $record) {
			$link = array('link' => $record['bnode']);
			$full_record[$record['bnode']] = array_merge($link, $this->getTriples($record['bnode']));			
		}
		return $full_record;
	}	
	
	public function getTitle($URI) {
		$q = "select ?title where { " . 
			" <".$URI."> rdfs:label ?title . " .			
			"}";				
		$records = $this->executeQuery($q);
		if (count($records) > 0) {
			return $records[0]['title'];
		} else {
			return false;
		}			
	}
	
	public function getGeography($URI) {
		$q = "select ?geo_uri where { " . 
			" <".$URI."> eco:models ?bnode . " .
			"?bnode eco:hasGeoLocation ?geo_uri . " . 			
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
			" ?uri dcterms:publisher '" . $foaf_uri . "' . " . 	
			" ?uri eco:models ?bnode . " . 
			" ?bnode rdfs:label ?title . " . 		
			"}" . 
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
		$q = "select ?uri where { " . 
			" <".$URI."> eco:models ?bnode . " .			
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
	
	public function getQR($URI) {
		$q = "select ?bnode ?name where { " . 
			" <".$URI."> eco:models ?bnode . " .			
			" ?bnode rdfs:label ?name . " .
			"{ ?bnode rdfs:type eco:Product . } UNION { ?bnode rdfs:type eco:Substance . } UNION { ?bnode rdfs:type eco:Energy . } " . 
			"}";				
		$records = $this->executeQuery($q);
		if (count($records) > 0) {
			$q = "select ?magnitude ?unit ?exchange_bnode where { " . 
				" ?exchange_bnode eco:hasEffect ?effect_bnode . " .
				" ?exchange_bnode eco:hasQuantity ?quantity_bnode . " .
				" ?quantity_bnode eco:hasMagnitude ?magnitude . " .
				" ?quantity_bnode eco:hasUnitOfMeasure ?unit . " .
				" ?effect_bnode eco:hasTransferable <" . $records[0]['bnode'] . "> . " .			
				"}";
			$full_records = $this->executeQuery($q);
			$full_records[0]['name'] = $records[0]['name'];
			return $full_records;
		} else {
				$q = "select ?unit ?name where { " . 
					" <".$URI."> eco:hasFunctionalUnitofMeasure ?bnode . " .
					" ?bnode eco:hasFunction ?name . " .
					" ?bnode eco:hasUnitQuantity ?unit . " .			
					"}";
				$records = $this->executeQuery($q);
				if (count($records) > 0) {				
					$records[0]['magnitude'] = "1";				
					return $records;
				} else {
					
					$q = "select ?name ?unit ?magnitude where { " . 
						" <".$URI."> eco:hasReferenceExchange ?exchange_bnode . " .
						" ?exchange_bnode eco:hasEffect ?effect_bnode . " .
						" ?exchange_bnode eco:hasQuantity ?quantity_bnode . " .
						" { ?quantity_bnode eco:hasMagnitude ?magnitude . } UNION { ?quantity_bnode ecoUD:meanValue ?magnitude . } " .
						" ?quantity_bnode eco:hasUnitOfMeasure ?unit . " .
						" ?effect_bnode eco:hasTransferable ?transferable . " .	
						" ?transferable rdfs:label ?name . " .			
						"}";
					//var_dump($q);
					$records = $this->executeQuery($q);
					if (count($records) > 0) {							
						return $records;
					} else {
						return false;
					}
				}
		}
	}
	
	public function getExchanges($URI) {		
		$q = "select ?bnode where { " . 
			" <".$URI."> eco:hasUnallocatedExchange ?bnode . " .				
			"}";				
		$records = $this->executeQuery($q);
		$q2 = "select ?bnode where { " . 	
			" <".$URI."> eco:hasAllocatedExchange ?bnode . " .				
			"}";				
		$records2 = $this->executeQuery($q2);
		$q3 = "select ?bnode where { " . 	
			" <".$URI."> eco:hasReferenceExchange ?bnode . " .				
			"}";				
		$records3 = $this->executeQuery($q3);		
		$records = array_merge($records, $records2);
		$records = array_merge($records, $records3);
		$full_record = array();		
		foreach ($records as $record) {
			$link = array('link' => $record['bnode']);
			$full_record[$record['bnode']] = array_merge($link, $this->getTriples($record['bnode']));			
		}
		if (count($full_record) > 0) {
			return $full_record;
		} else {
			return false;
		}	
	}


	
	/**
	 * Gets the Parent node of a bnode
	 * @return $records Array	
	 * @param $next_bnode string
	 */	
	public function latest($limit) {
		$q = "select ?uri ?created ?name where { " . 
			"?uri dcterms:created ?created . " . 
			"?uri eco:models ?bnode . " .			
			" ?bnode rdfs:#type  eco:Product . " .
			" ?bnode rdfs:label  ?name . " .
			"} ORDER BY DESC(?created)";	
		$records = $this->executeQuery($q);	
		return $records;
	}

   public function addSameAs($ft,$oc) {
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
		$q = "insert into <http://footprinted.org/> { " . 
		"<".$records[0]['bnode']."> owl:sameAs <"."http://sw.opencyc.org/concept/". $oc.">" . 
		"}";
		$this->executeQuery($q);
	   //$this->addTriples($triples);
   }


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
		$q = "insert into <http://footprinted.org/> { " . 
		"<".$records[0]['bnode']."> owl:sameAs <". $oc.">" . 
		"}";
		$this->executeQuery($q);
	   //$this->addTriples($triples);
   }


   public function addCategory($ft,$oc) {
		$triples = array();
       $q = "select ?bnode where { " . 
           " <http://footprinted.org/rdfspace/lca/".$ft."> eco:models ?bnode . " .            
           " ?bnode rdfs:type  eco:Product . " .
           "}";              
       $records = $this->executeQuery($q);
		$q = "insert into <http://footprinted.org/> { " . 
		"<".$records[0]['bnode']."> eco:hasCategory <"."http://sw.opencyc.org/concept/". $oc.">" . 
		"}";
		$this->executeQuery($q);
   }
	
	public function getOpenCycSuggestions($uri) {
		$q = "select ?label where { " . 
            " <".$uri."> eco:models ?bnode . " .            
            " ?bnode rdfs:type  eco:Product . " .
			" ?bnode rdfs:label  ?label . " .
            "}";              
        $records = $this->executeQuery($q);
		$q = "select ?label where { " . 
            " <".$uri."> eco:models ?bnode . " .            
            " ?bnode rdfs:type  eco:Process . " .
			" ?bnode rdfs:label  ?label . " .
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
/*	
	public function getCategories($URI) {
		$uris = $this->getLinks($URI);
		$path = array();
		foreach ($uris as $auri) {
			$path[] = $this->opencycmodel->getOpenCycCategories($auri['uri']);
		}	
	}
*/	
	public function getCategories($URI){		
		$q = "select ?uri where { " . 
			" <".$URI."> eco:models ?bnode . " .
			" ?bnode eco:hasCategory  ?uri . " .			
			" { ?bnode rdfs:type  eco:Product . } UNION { ?bnode rdfs:type  eco:Substance . } UNION { ?bnode rdfs:type  eco:Energy . } " .
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
	
	public function getAllUsedCategories() {
		$q = "select DISTINCT ?uri where { " . 
			" ?bnode eco:hasCategory  ?uri . " .
			"}";
		$records = $this->executeQuery($q);
		if (count($records) > 0) {
			$categories = array();
			foreach ($records as $record) {
				$categories[] = array(
					'uri'=>$record['uri'],
					'label'=> $this->opencycmodel->getOpenCycLabel($record['uri'])
				);						
			}
			return $categories;
		} else {
			return false;
		}	
	}

	public function getLCAsByCategory($URI) {		
		$q = "select ?uri where { " . 
			" ?uri eco:models ?bnode . " .			
			" ?bnode rdfs:type  eco:Product . " .
			" ?bnode eco:hasCategory  <" . $URI . "> . " .
			"}";
			
		$records = $this->executeQuery($q);
		foreach ($records as &$record) {
			$q = "select ?label where { " . 
				"<". $record['uri'] ."> eco:models ?bnode . " .
				"?bnode rdfs:label ?label . " .
				"?bnode rdfs:type eco:Product . " .  
				"}";
			$get_product_name = $this->executeQuery($q);
			if (count($get_product_name) > 0) {
				$record['label'] = $get_product_name[0]['label'];			
			// If it doesnt appear to model a product, get the name of a process	
			} else {
				$q = "select ?label where { " . 
					"<". $record['uri'] ."> eco:models ?bnode . " .
					"?bnode rdfs:label ?label . " .
					"?bnode rdfs:type eco:Process . " .  
					"}";
				$get_process_name = $this->executeQuery($q);	
				if (count($get_product_name) > 0) {
				$record['label'] = $get_process_name[0]['label'];					
				} else {
					$q = "select ?label where { " . 
						"<". $record['uri'] ."> rdfs:label ?label . " .
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
