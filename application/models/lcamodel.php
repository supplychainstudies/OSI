<?php
/**
 * This model uses the Arc2 library to insert, edit, and retrieve rdf data from the arc store 
 * 
 * @package opensustainability
 * @subpackage models
 */
 class Lcamodel extends FT_Model{
     
    /**
     * @ignore
     */
    public function Lcamodel(){
        parent::__construct();
		$this->load->model(Array('unitmodel','geographymodel','ecomodel'));
    }


	public function convertGeography($dataset){
		$rdfs_prefix = "http://www.w3.org/2000/01/rdf-schema#";
		$eco_prefix = "http://ontology.earthster.org/eco/core#";
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

	public function convertImpactAssessments($dataset, $tooltips) {
		$rdfs_prefix = "http://www.w3.org/2000/01/rdf-schema#";
		$eco_prefix = "http://ontology.earthster.org/eco/core#";
		$converted_dataset = array();		
		foreach($dataset as $key=>$_record) {	
			foreach ($_record[$eco_prefix."hasImpactAssessmentMethodCategoryDescription"] as $__record) {
				foreach($__record[$eco_prefix."hasImpactCategory"] as $___record) {
					$converted_dataset[$key]['impactCategory'] = $___record;
					$this->ecomodel->makeToolTip($___record, $tooltips);
				} 
				foreach($__record[$eco_prefix."hasImpactCategoryIndicator"] as $___record) {
					$converted_dataset[$key]['impactCategoryIndicator'] =  $___record;
				}					
			} 	
			foreach ($_record[$eco_prefix."hasQuantity"] as $__record) {
				foreach($__record[$eco_prefix."hasMagnitude"] as $___record) {
					$converted_dataset[$key]['amount'] = $___record;
				}
				foreach($__record[$eco_prefix."hasUnitOfMeasure"] as $___record) {
					$converted_dataset[$key]['unit'] = $___record;
					$this->unitmodel->makeToolTip($___record, $tooltips);
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
	

	public function convertExchanges($dataset, $tooltips){
			$rdfs_prefix = "http://www.w3.org/2000/01/rdf-schema#";
			$eco_prefix = "http://ontology.earthster.org/eco/core#";
			$converted_dataset = array();
			foreach($dataset as $key=>$record) {		
				foreach($record[$eco_prefix."hasEffect"] as $_record) {
					foreach ($_record[$rdfs_prefix."type"] as $__record) {
						if ($__record == "eco:Output" || $__record == "eco:Input") {
							$converted_dataset[$key]["direction"] = str_replace("eco:", "", $__record);
						} 
					}
					foreach($_record[$eco_prefix."hasTransferable"] as $transferable) {
						if (is_array($transferable) == true) {
							foreach($transferable[$rdfs_prefix."label"] as $label) {
								$converted_dataset[$key]['name'] = $label;
							}
						} else {
							$converted_dataset[$key]['name'] = $transferable;
						}
					}
					foreach($_record[$eco_prefix."hasFlowable"] as $flowable) {
						$converted_dataset[$key]['name'] = str_replace("eco", "", $flowable);
					} 								
				}
				foreach ($record[$eco_prefix."hasQuantity"] as $_record) {
					foreach($_record[$eco_prefix."hasMagnitude"] as $magnitude) {
						$converted_dataset[$key]['amount'] = $magnitude;
					} 
					foreach($_record[$eco_prefix."hasUnitOfMeasure"] as $unitOfMeasure) {
						$converted_dataset[$key]['unit'] = $unitOfMeasure;
						$this->unitmodel->makeToolTip($unitOfMeasure, $tooltips);
					} 
				}
			}
			return $converted_dataset; 
		}
	
	public function convertQR($dataset, $tooltips){
		$rdfs_prefix = "http://www.w3.org/2000/01/rdf-schema#";
		$eco_prefix = "http://ontology.earthster.org/eco/core#";
		$converted_dataset = array();		
		foreach($dataset as $key=>$record) {		
			$converted_dataset['name'] = $record['name'];
			$converted_dataset['amount'] = $record['magnitude'];
			$converted_dataset['unit'] = $record['unit'];
			$this->unitmodel->makeToolTip($record['unit'], $tooltips);
		}		
		return $converted_dataset; 
	}	
	
	public function convertModeled($dataset, $tooltips){
		$rdfs_prefix = "http://www.w3.org/2000/01/rdf-schema#";
		$eco_prefix = "http://ontology.earthster.org/eco/core#";
		$converted_dataset = array();
		foreach($dataset as $key=>$record) {		
			if(isset($record[$rdfs_prefix."type"]) == true) {
				foreach($record[$rdfs_prefix."type"] as $type) {
					foreach($record[$rdfs_prefix."label"] as $label) {
							$this->ecomodel->makeToolTip($type, $tooltips);
							$converted_dataset['type'] = $type;
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
			"}" . 
			"LIMIT 10 ";

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


} //
