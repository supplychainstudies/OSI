<?php if(!defined('BASEPATH')) { exit('No direct script access allowed'); }

//$this->tooltips = array();

function toolTipBuilder($uri) {
	/*
	if (isset($this->tooltips[$uri]) != true) {
		if (strpos($uri,":") !== false) {
			$this->tooltips[$uri] = array();
			$this->tooltips[$uri]['label'] = $this->arcremotemodel->getLabel($uri);	
			$this->tooltips[$uri]['l'] = $this->tooltips[$uri]['label'];
			if (strpos($uri, "qudt") !== false) {
				$this->tooltips[$uri]['abbr'] = $this->arcremotemodel->getAbbr($uri);
				$this->tooltips[$uri]['l'] = $this->tooltips[$uri]['abbr'];
			} 
			if (strpos($uri, "qudt") !== false) {
				$this->tooltips[$uri]['quantityKind'] = $this->arcremotemodel->getQuantityKind($uri);
			}				
			if ($this->tooltips[$uri]['l'] == false) { 
				$uri_parts = explode(":", $uri);
				return $uri_parts[1];
			} 
		} 
	}
	*/
}



function convertBibliography($dataset){
	$bibo_prefix = "http://purl.org/ontology/bibo/";
	$foaf_prefix = "http://xmls.com/foaf/0.1/";
	$dc_prefix = "http://purl.org/dc/";
	$converted_dataset = array();
	foreach ($dataset as $key=>$record) {
		if (isset($record[$dc_prefix."title"]) == true) {
			foreach($record[$dc_prefix."title"] as $title) {
				$converted_dataset[$key]['title'] = $title;
			}
		} else {
			$converted_dataset[$key]['title'] = "";
		}
		if (isset($record[$bibo_prefix."authorList"]) == true) {
			$person_array = array();
			foreach($record[$bibo_prefix."authorList"] as $author_uri) {
				$person = $this->arcmodel->getTriples($author_uri);
				foreach ($person[$foaf_prefix.'firstName'] as $firstName) {
					$person_array['firstName'] = $firstName;
				} 
				foreach ($person[$foaf_prefix.'lastName'] as $lastName) {
					$person_array['lastName'] = $lastName;
				}						
			}
			$converted_dataset[$key]['authors'][] = $person_array;
		} else {
			
		}
		if (isset($record[$bibo_prefix."uri"]) == true) {
			foreach($record[$bibo_prefix."uri"] as $uri) {
				$converted_dataset[$key]['uri'] = $uri;
			}
		} else {
			$converted_dataset[$key]['uri'] = "";
		} 
		if (isset($record[$dc_prefix."date"]) == true) {
			foreach($record[$dc_prefix."date"] as $date) {
				$converted_dataset[$key]['date'] = $date;
			}
		} else {
			$converted_dataset[$key]['date'] = "";
		}
		/*
		if (isset($record[$bibo_prefix."isbn"]) == true) {
			foreach($record[$bibo_prefix."date"] as $date) {
				$converted_dataset[$key]['date'] = $date;
			}
		} else {
			$converted_dataset[$key]['date'] = "";
		}
		"dc:creator" => $organization_uris,
		"bibo:isbn" => trim($line_array[5]),
		"bibo:volume" => trim($line_array[6]),
		"bibo:issue" => trim($line_array[7]),
		"bibo:doi" => trim($line_array[10]),
		"bibo:chapter" => trim($line_array[13]),
		"bibo:locator" => trim($line_array[14]),	
		*/					
	}
	return $converted_dataset;
}

function convertExchanges($dataset){
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
				toolTipBuilder($unitOfMeasure);
			} 
		}
	}
	return $converted_dataset; 
}

function convertQR($dataset){
	$rdfs_prefix = "http://www.w3.org/2000/01/rdf-schema#";
	$eco_prefix = "http://ontology.earthster.org/eco/core#";
	$converted_dataset = array();		
	foreach($dataset as $key=>$record) {		
		$converted_dataset['name'] = $record['name'];
		$converted_dataset['amount'] = $record['magnitude'];
		$converted_dataset['unit'] = $record['unit'];
		toolTipBuilder($record['unit']);
	}		
	return $converted_dataset; 
}	

function convertModeled($dataset){
	$rdfs_prefix = "http://www.w3.org/2000/01/rdf-schema#";
	$eco_prefix = "http://ontology.earthster.org/eco/core#";
	$converted_dataset = array();
	foreach($dataset as $key=>$record) {		
		if(isset($record[$rdfs_prefix."type"]) == true) {
			foreach($record[$rdfs_prefix."type"] as $type) {
				foreach($record[$rdfs_prefix."label"] as $label) {
						toolTipBuilder($type);
						$converted_dataset['type'] = $type;
				}				
			}				
		}
	}		
	return $converted_dataset; 
}

function convertGeography($dataset){
	$rdfs_prefix = "http://www.w3.org/2000/01/rdf-schema#";
	$eco_prefix = "http://ontology.earthster.org/eco/core#";
	$converted_dataset = array();
	if ($dataset != false) {
		foreach($dataset as $geo) {
			$converted_dataset[] = $this->arcremotemodel->getPointGeonames($geo['geo_uri']);
		}
		return $converted_dataset;
	} else {
		return false;
	}
}

function convertImpactAssessments($dataset){
	$rdfs_prefix = "http://www.w3.org/2000/01/rdf-schema#";
	$eco_prefix = "http://ontology.earthster.org/eco/core#";
	$converted_dataset = array();		
	foreach($dataset as $key=>$_record) {	
		foreach ($_record[$eco_prefix."hasImpactAssessmentMethodCategoryDescription"] as $__record) {
			foreach($__record[$eco_prefix."hasImpactCategory"] as $___record) {
				$converted_dataset[$key]['impactCategory'] = $___record;
				toolTipBuilder($___record);
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
				toolTipBuilder($___record);
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

