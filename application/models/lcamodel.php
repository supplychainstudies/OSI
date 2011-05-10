<?php
class Lcamodel extends FT_Model{
    public function Lcamodel(){
        parent::__construct();
		$this->load->model(Array('unitmodel','geographymodel','ecomodel'));
    }


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

	public function convertImpactAssessments($dataset) {
		$converted_dataset = array();		
		foreach($dataset as $key=>$_record) {	
			foreach ($_record[$this->arc_config['ns']['eco']."hasImpactAssessmentMethodCategoryDescription"] as $__record) {
				foreach($__record[$this->arc_config['ns']['eco']."hasImpactCategory"] as $___record) {
					$converted_dataset[$key]['impactCategory'] = $this->ecomodel->makeToolTip($___record);
					
				} 
				foreach($__record[$this->arc_config['ns']['eco']."hasImpactCategoryIndicator"] as $___record) {
					$converted_dataset[$key]['impactCategoryIndicator'] =  $___record;
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
	

	public function convertExchanges($dataset){
			$converted_dataset = array();
			foreach($dataset as $key=>$record) {		
				foreach($record[$this->arc_config['ns']['eco']."hasEffect"] as $_record) {
					foreach ($_record[$this->arc_config['ns']['rdfs']."type"] as $__record) {
						if ($__record == $this->arc_config['ns']['eco']."Output" || $__record == $this->arc_config['ns']['eco']."Input") {
							$converted_dataset[$key]["direction"] = str_replace($this->arc_config['ns']['eco'], "", $__record);
						} 
					}
					foreach($_record[$this->arc_config['ns']['eco']."hasTransferable"] as $transferable) {
						if (is_array($transferable) == true) {
							foreach($transferable[$this->arc_config['ns']['rdfs']."label"] as $label) {
								$converted_dataset[$key]['name'] = $label;
							}
						} else {
							$converted_dataset[$key]['name'] = $transferable;
						}
					}
					foreach($_record[$this->arc_config['ns']['eco']."hasFlowable"] as $flowable) {
						$converted_dataset[$key]['name'] = str_replace("eco", "", $flowable);
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
	
		$q = "select ?link where { " . 
			" ?link rdfs:type eco:FootprintModel . " . 
			"}";
		$footprint_records = $this->executeQuery($q);	
		$q = "select ?link where { " . 
			" ?link rdfs:type eco:Model . " . 
			"}";
		$model_records = $this->executeQuery($q);	
	
		$records = array_merge($footprint_records, $model_records);
		foreach ($records as &$record) {
			$q = "select ?name where { " . 
				"<". $record['link'] ."> eco:models ?bnode . " .
				"?bnode rdfs:label ?name . " .
				"?bnode rdfs:type eco:Product . " .  
				"}";
			$get_product_name = $this->executeQuery($q);
			if (count($get_product_name) > 0) {
				$record['name'] = $get_product_name[0]['name'];			
			// If it doesnt appear to model a product, get the name of a process	
			} else {
				$q = "select ?name where { " . 
					"<". $record['link'] ."> eco:models ?bnode . " .
					"?bnode rdfs:label ?name . " .
					"?bnode rdfs:type eco:Process . " .  
					"}";
				$get_process_name = $this->executeQuery($q);	
				if (count($get_product_name) > 0) {
				$record['name'] = $get_process_name[0]['name'];					
				} else {
					$q = "select ?name where { " . 
						"<". $record['link'] ."> rdfs:label ?name . " .
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
			" ?uri eco:models ?bnode . " ;
		if ($value != null) {
			$q .= " ?bnode rdfs:label ?label . " . 
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
			" ?bnode eco:computedFrom <".$URI."> . " .
			" ?bnode rdfs:type eco:ImpactAssessment . " .	
			" ?bnode eco:hasImpactCategoryIndicatorResult ?bnode2 . " .
			" ?bnode2 eco:hasImpactAssessmentMethodCategoryDescription ?bnode3 . " .			 	
			" ?bnode3 eco:hasImpactCategory ?impactCategory . " .			
			" ?bnode3 eco:hasImpactCategoryIndicator ?impactCategoryIndicator . " .
			" ?bnode2 eco:hasQuantity ?bnode4 . " . 			
			" ?bnode4 eco:hasUnitOfMeasure ?impactCategoryUnit . " . 
			" ?bnode4 eco:hasMagnitude ?impactCategoryValue . " .				
			"}";				
		$records = $this->executeQuery($q);	
		foreach ($records as &$record) {
			$record['impactCategory'] = $this->getLabel($record['impactCategory']);
			$record['impactCategoryIndicator'] = $this->getLabel($record['impactCategoryIndicator']);
			$record['impactCategoryUnit'] = $this->getLabel($record['impactCategoryUnit']);
		}
		return $records;
	}
	
	
	
	public function getImpactAssessments($URI) {
		$q = "select ?bnoder where { " . 
			" ?bnode eco:computedFrom <".$URI."> . " .
			" ?bnode rdfs:type eco:ImpactAssessment . " .
			" ?bnode eco:hasImpactCategoryIndicatorResult ?bnoder . " .			
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
	
	
	
	public function getQR($URI) {
		$q = "select ?bnode ?name where { " . 
			" <".$URI."> eco:models ?bnode . " .			
			" ?bnode rdfs:type  eco:Product . " .
			" ?bnode rdfs:label  ?name . " .
			"}";				
		$records = $this->executeQuery($q);
		if (count($records) > 0) {
			$q = "select ?magnitude ?unit where { " . 
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
			return false;
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
			"?uri eco:models ?bnode . " .			
			" ?bnode rdfs:#type  eco:Product . " .
			" ?bnode rdfs:label  ?name . " .
			"} ORDER BY DESC(?created)";	
		$records = $this->executeQuery($q);	
		return $records;
	}


}
