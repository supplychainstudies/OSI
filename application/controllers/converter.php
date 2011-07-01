<?php
/**
 * Controller for environmental information structures
 * 
 * @version 0.8.0
 * @author info@footprinted.org
 * @package opensustainability
 * @subpackage controllers
 * @uses 
 */



class Converter extends FT_Controller {
	public function Converter() {
		parent::__construct();
		$this->load->library(Array('xml', 'Simplexml', 'form_extended'));
		$this->load->model(Array('lcamodel','peoplemodel','unitmodel','geographymodel'));		
	}	
	
	
	// This function automatically parses an Ecospold v1 xml file, converts it to ECO and dumps it into the footprinted database. if you want to see the ecospold v1 schema, go to []
	public function ecospold1auto($file = null) {
		$xmlfile = "http://osi/assets/examples/Ecospold_biomass-8.8_miscanthus.xml";
		$xmlRaw = file_get_contents($xmlfile);  
		$parsed = @$this->simplexml->xml_parse($xmlRaw);
		$uris = array();
		foreach ($parsed['dataset'] as $key=>$dataset) {
			$this->lca_datasets[$key] = array();
			$this->lca_datasets[$key]['person'] = array();
			$this->lca_datasets[$key]['organization'] = array();
			$this->lca_datasets[$key]['model'] = array();
			$this->lca_datasets[$key]['process'] = array();
			$this->lca_datasets[$key]['bibliography'] = array();
			$this->lca_datasets[$key]['exchange'] = array();
			$lang = array();
			if (isset($dataset['metaInformation']['processInformation']['dataSetInformation']) == true) {
				$lang[0] = $dataset['metaInformation']['processInformation']['dataSetInformation']['@attributes']['languageCode'];
				$lang[1] = $dataset['metaInformation']['processInformation']['dataSetInformation']['@attributes']['localLanguageCode'];				
			}
			
			foreach($dataset['metaInformation']['administrativeInformation']['person'] as $person) {
				if (count($this->ecospold1Person($person)) != 0) {
					$_person = $this->ecospold1Person($person);
					if (is_array($_person) == true) {
						$this->lca_datasets[$key]['person'][] = $_person;
					}
				}
				if (count($this->ecospold1Organization($person)) != 0) {				
					$this->lca_datasets[$key]['organization'][] = $this->ecospold1Organization($person);
				}
			}
			foreach($dataset['metaInformation']['modellingAndValidation'] as $mvkey=>$mv) {
				if ($mvkey == "source") { 
					foreach ($mv as $source) {
						if (count($this->ecospold1Source($source)) > 0) {
							$this->lca_datasets[$key]['bibliography'][] = $this->ecospold1Source($source);
							$last = end($this->lca_datasets[$key]['bibliography']);
							if (isset($last['pass_authors']) == true) {
								foreach ($last['pass_authors'] as $person) {
									$this->lca_datasets[$key]['person'][] = $person;
								}
							}
						}
					}
				}
			}
			if (count($this->ecospold1Model($dataset['metaInformation'])) != 0) {
				$this->lca_datasets[$key]['model'][] = $this->ecospold1Model($dataset['metaInformation']);
			}
			foreach($dataset['metaInformation'] as $_key=>$process) {
				if ($_key == "processInformation") {
					if (count($this->ecospold1Process($process)) != 0) {
						$this->lca_datasets[$key]['process'][] = $this->ecospold1Process($process);
						if (isset($process["referenceFunction"]) == true) {
							if ($process["referenceFunction"]['@attributes']['datasetRelatesToProduct'] = "yes") {
								$this->lca_datasets[$key]['exchange'][] = $this->ecospold1Exchange($process["referenceFunction"], "eco:hasReferenceExchange");
							}
						}
					}				
				}
			}
			$exchanges = array();
			foreach($dataset['flowData']['exchange'] as $exchange) {
				if (count($this->ecospold1Exchange($exchange)) != 0) {				
					$this->lca_datasets[$key]['exchange'][] = $this->ecospold1Exchange($exchange);
				}				
			}
			if (isset($this->lca_datasets[$key]['process'][0]['name'][0]) == true) {
				$uris[] = toURI("lca", $this->lca_datasets[$key]['process'][0]['name'][0]);
			} else {
				$uris[] = toURI("lca", "lca");
			}
			
		}
		// *********************************************************************************
		
		foreach ($this->lca_datasets as $key=>$lca_dataset) {
			foreach ($lca_dataset as $type=>$part) {
				foreach ($part as $_key=>$instance) {
					$fixed_instance = array();
					foreach ($instance as $field=>$value) {
						$fixed_instance[$field."_"] = $value;
					}
					$structure = $this->form_extended->load($type);
					if (isset($fixed_instance['local_uri_']) == true) {
						$uri = $fixed_instance['local_uri_'];
					} else {
						$uri = $uris[$key];
					}
					if (isset($fixed_instance['change_predicates_']) == true) {
						$triples = $this->form_extended->build_group_triples($uri,$fixed_instance,$structure, "", 0, $fixed_instance['change_predicates_']);
					} else {
						$triples = $this->form_extended->build_group_triples($uri,$fixed_instance,$structure);
					}
					$this->lcamodel->addTriples($triples);
				}
			}
		}
		
		
		// *********************************************************************************		
	}	
	
	public function ecospold1($file = null) {
		# LOAD XML FILE
		/*
		$file = "data/datasets/Ecospold1/Ecospold_biomass-8.8_miscanthus";
		$this->xml->load($file);	
		$parsed = $this->xml->parse();
		*/
		$xmlfile = "http://osi/assets/examples/Ecospold_biomass-8.8_miscanthus.xml";
		$xmlRaw = file_get_contents($xmlfile);  
		$parsed = @$this->simplexml->xml_parse($xmlRaw);
		$uris = array();
		foreach ($parsed['dataset'] as $key=>$dataset) {
			$this->lca_datasets[$key] = array();
			$this->lca_datasets[$key]['person'] = array();
			$this->lca_datasets[$key]['organization'] = array();
			$this->lca_datasets[$key]['model'] = array();
			$this->lca_datasets[$key]['process'] = array();
			$this->lca_datasets[$key]['bibliography'] = array();
			$this->lca_datasets[$key]['exchange'] = array();
			foreach($dataset['metaInformation'] as $_key=>$process) {
				if ($_key == "processInformation") {
					if (count($this->ecospold1Process($process)) != 0) {
						$this->lca_datasets[$key]['process'][] = $this->ecospold1Process($process);
					}
					if (count($this->ecospold1Model($process)) != 0) {
						$this->lca_datasets[$key]['model'][] = $this->ecospold1Model($process);
					}					
				}
			}
			foreach($dataset['metaInformation']['administrativeInformation']['person'] as $person) {
				if (count($this->ecospold1Person($person)) != 0) {
					$this->lca_datasets[$key]['person'][] = $this->ecospold1Person($person);
				}
				if (count($this->ecospold1Organization($person)) != 0) {				
					$this->lca_datasets[$key]['organization'][] = $this->ecospold1Organization($person);
				}
			}
			$exchanges = array();
			foreach($dataset['flowData']['exchange'] as $exchange) {
				if (count($this->ecospold1Exchange($exchange)) != 0) {				
					$this->lca_datasets[$key]['exchange'][] = $this->ecospold1Exchange($exchange);
				}				
			}
			foreach($dataset['metaInformation']['modellingAndValidation'] as $source) {
				if (count($this->ecospold1Source($source)) > 0) {
					$this->lca_datasets[$key]['bibliography'][] = $this->ecospold1Source($source);
				}
			}
			if (isset($this->lca_datasets[$key]['process'][0]['name']) == true) {
				$uris[] = toURI("lca", $this->lca_datasets[$key]['process'][0]['name']);
			} else {
				$uris[] = toURI("lca", "lca");
			}
			
		}
		// *********************************************************************************
		
		
		
		
		// *********************************************************************************		
		//$this->session->set_userdata('convert_json', json_encode($this->lca_datasets));
		//$this->session->set_userdata('convert_uris', json_encode($uris));
		//$this->forms();
	}
	
	public function arraythis($data) {
		foreach ($data as &$_data) {
			if (is_object($_data) == true) {
				$_data = (array)$_data;
			} 
			if (is_array($_data) == true) {
				$_data = $this->arraythis($_data);
			}
		}
		return $data;
	}
	
	public function forms() {
		// get the translated file from the session and decode it
		$data = json_decode($this->session->userdata('convert_json'));
		// Seems to return weird objects, so turn it into a nice array
		$data = $this->arraythis($data);
		// get a list of all the lcas in there
		$keys = array_keys($data);
		// get the first lca on the list (we'll pop lcas off of the session var to iterate through)
		// list of forms in the order that they should appear 
		$types = array('bibliography','person','model','process','exchange','impactAssessment');
		// find the first form type that should appear
		foreach ($types as $type) {
			if (isset($data[$keys[0]][$type]) == true){
				if (count($data[$keys[0]][$type]) > 0) {
					$_type = $type;
					break;
				}
			}
		}
		$_keys = array_keys($data[$keys[0]][$_type]);
		$view_string = '';
		foreach (current($data[$keys[0]][$_type]) as $field=>$value) {
			if ($field != "" && $value != "") {
				$view_string .= '<input type="hidden" value="'.$value.'" name="pre_'.$field.'_" />';
			}
		}
		unset($data[$keys[0]][$_type][$_keys[0]]);
		if (count($data[$keys[0]][$_type]) == 0) {
			unset($data[$keys[0]][$_type]);
			if (count($data[$keys[0]]) == 0) {
				unset($data[$keys[0]]);
				if (count($data) == 0) {
					unset($data);
				}
			}
		} 		
		if (isset($data) == true) {
			$this->session->set_userdata('convert_json', json_encode($data));
			var_dump(mb_strlen($this->session->userdata('convert_json'),'latin1'));
			$view_string .= $this->form_extended->build($_type);		
			$this->script(Array('form.js','register.js'));
			$this->style(Array('style.css','form.css'));
			$this->data("form_string", $view_string);
			$this->display("Form", "form_view");
		}
	}
	
	
	//
	private function ecospold1Source ($source) {
		$info = array(); 
		$info['pass_authors'] = array();
		if (isset($source['@attributes']['number']) == true) {
			$info['esref'] = $source['@attributes']['number'];
		}
		if (isset($source['@attributes']['title']) == true) {
			$info['title'] = $source['@attributes']['title'];
		} 
		if (isset($source['@attributes']['placeOfPublications']) == true) {
			$info['location'] = $source['@attributes']['placeOfPublications'];
		}		
		if (isset($source['@attributes']['publisher']) == true) {
			$info['publisher'] = $source['@attributes']['publisher'];
		}
		if (isset($source['@attributes']['volumeNo']) == true) {
			$info['volume'] = $source['@attributes']['volumeNo'];
		}
		if (isset($source['@attributes']['issueNo']) == true) {
			$info['issue'] = $source['@attributes']['issueNo'];
		}
		if (isset($source['@attributes']['nameOfEditors']) == true) {
			$editors = explode(",",$source['@attributes']['editors']);
			foreach ($editors as $editor) {
				$_editor = $this->ecospold1Person(array('name'=>trim($editor)));
				if (isset($_editor['local_uri']) == true) {
					$info['editor'][] = $_editor['local_uri'];
					$info['pass_authors'][] = $_editor;
				} elseif (isset($_editor['uri']) == true) {
					$info['editor'][] = $_editor['uri'];
				}
			}
		}
		if (isset($source['@attributes']['pageNumbers']) == true) {
			$info['pageNumbers'] = $source['@attributes']['pageNumbers'];
		}
		if (isset($source['@attributes']['year']) == true) {
			$info['date'] = $source['@attributes']['year'];
		}
		if (isset($source['@attributes']['titleOfAnthology']) == true) {
			$info['partOf'] = $source['@attributes']['titleOfAnthology'];
		}
		if (isset($source['@attributes']['journal']) == true) {
			$info['partOf'] = $source['@attributes']['journal'];
		}
		if (isset($source['@attributes']['text']) == true) {
			$info['comment'] = $source['@attributes']['text'];
		}
		if (isset($source['@attributes']['firstAuthor']) == true) {
			$_author = $this->ecospold1Person(array('name'=>$source['@attributes']['firstAuthor']));
			if (isset($_author['local_uri']) == true) {
				$info['author'][] = $_author['local_uri'];
				$info['pass_authors'][] = $_author;
			} elseif (isset($_author['uri']) == true) {
				$info['author'][] = $_author['uri'];
			}
			if (isset($source['@attributes']['additionalAuthors']) == true) {	
				$authors = explode(",",$source['@attributes']['additionalAuthors']);
				foreach ($authors as $author) {
					$_author = $this->ecospold1Person(array('name'=>trim($author)));
					if (isset($_author['local_uri']) == true) {
						$info['author'][] = $_author['local_uri'];
						$info['pass_authors'][] = $_author;
					} elseif (isset($_author['uri']) == true) {
						$info['author'][] = $_author['uri'];
					}
				}
			}			
		}
		if (isset($source['@attributes']['sourceType']) == true) {
			// 0=Undefined (default) 1=Article 2=Chapters in anthology 3=Separate publication 4=Measurement on site 5=Oral communication 6=Personal written communication 7=Questionnaries
			// FIX: figure out what each type will correspond to.
			switch ($source['@attributes']['sourceType']) {
			    case 0:
			        $info['bibotype'] = "bibo_Document";
			        break;
			    case 1:
			        $info['bibotype'] = "bibo_Article";			        
			        break;
			    case 2:
			        $info['bibotype'] = "bibo_Book";			        
			        break;
			    case 3:
			        $info['bibotype'] = "bibo_Document";			        
			     	break;
			    case 4:
			        //$info['type'] = "Document";			        
			     	break;
			    case 5:
			        //$info['type'] = "Document";			        
			     	break;
			    case 6:
			        //$info['type'] = "Document";			        
			     	break;
			    case 7:
			        //$info['type'] = "Document";			        
			     	break;
			}
		}
		$info['local_uri'] = toURI("bibliography",$info['title']);
		return $info;		
	}
	
	private function ecospold1Model($process) {
		$info = array();
		$info['modelType'] = "eco_LCAModel";
		$info['description'] = "";
		$info['category'] = array();
		if (isset($process["processInformation"]["referenceFunction"]["@attributes"]['name']) == true) {
			$info['name'] = $process["processInformation"]["referenceFunction"]["@attributes"]["name"];
		}
		if (isset($process["administrativeInformation"]["dataGeneratorAndPublication"]["@attributes"]['referenceToPublishedSource']) == true) {
			foreach ($this->lca_datasets as $set) {
				foreach ($set['bibliography'] as $ref) {
					if ($ref['esref'] == $process["administrativeInformation"]["dataGeneratorAndPublication"]["@attributes"]['referenceToPublishedSource']) {
						$info['dataSource'] = $ref['local_uri'];
					}
				}
			}
		}
		
		// Big Description
		if (isset($process["processInformation"]["referenceFunction"]["@attributes"]['includedProcesses']) == true) {
			$info['description'] .= $process["processInformation"]["referenceFunction"]["@attributes"]["includedProcesses"];
		}
		if (isset($process["processInformation"]["referenceFunction"]["@attributes"]['generalComment']) == true) {
			$info['description'] .= $process["processInformation"]["referenceFunction"]["@attributes"]["generalComment"];
		}
		if (isset($process["processInformation"]["referenceFunction"]["@attributes"]['infrastructureProcess']) == true) {
			if ($process["processInformation"]["referenceFunction"]["@attributes"]['infrastructureProcess'] == "Yes") {
				$info['description'] .= "This includes infrastructure processes.";
			} else {
				$info['description'] .= "This does not include infrastructure processes.";
			}
		}
		if (isset($process["processInformation"]['geography']['@attributes']['text']) == true) {
				$info['description'] .= $process["processInformation"]['geography']['@attributes']['text'];
		}
		if (isset($process["processInformation"]['technology']['@attributes']['text']) == true) {
				$info['description'] .= $process["processInformation"]['technology']['@attributes']['text'];
		}
		
		// Categories
		if (isset($process["processInformation"]["referenceFunction"]["@attributes"]['category']) == true) {
			$info['category'][] = $process["processInformation"]["referenceFunction"]["@attributes"]["category"];
		}
		if (isset($process["processInformation"]["referenceFunction"]["@attributes"]['subCategory']) == true) {
			$info['category'][] = $process["processInformation"]["referenceFunction"]["@attributes"]["subCategory"];
		}
		
		// Time Period
		if (isset($process["processInformation"]["timePeriod"]['startYear']) == true) {
			$info['beginning'][] = $process["processInformation"]["timePeriod"]['startYear'];
		}
		if (isset($process["processInformation"]["timePeriod"]['endYear']) == true) {
			$info['end'][] = $process["processInformation"]["timePeriod"]['endYear'];
		}
		return $info;
	}
	
	private function ecospold1Process($process) {
		$info = array();
		
		// Process Names
		if (isset($process["referenceFunction"]["@attributes"]['name']) == true) {
			$info['name'][] = $process["referenceFunction"]["@attributes"]["name"];
		}
		if (isset($process["referenceFunction"]["@attributes"]['synonym']) == true) {
			foreach (explode("//",$process["referenceFunction"]["@attributes"]['synonym']) as $name) {
				$info['name'][] = $name;
			}
		}
		// NACE Classification
		if (isset($process["referenceFunction"]["@attributes"]['statisticalClassification']) == true) {
			$nace = $this->nacemodel->getURIbyCode($process["referenceFunction"]["@attributes"]['statisticalClassification']);
			if ($nace != false) {
				$info['category'][] = $nace;				
			}
		}
		
		// Geography
		if (isset($process['geography']) == true) {
			//Look up the location by country code
			$cc = $this->geographymodel->getURIbyAlpha3($process['geography']['@attributes']['location']);
			if ($cc != false) {
				$info['geoLocation'] = $cc;
			} else {
				$info['geoLocation'] = $process['geography']['@attributes']['location'];
			}
		}
		
		// Piece together a description
		$info['description'] = "";
		if (isset($process["referenceFunction"]["@attributes"]['includedProcesses']) == true) {
			$info['description'] .= $process["referenceFunction"]["@attributes"]["includedProcesses"];
		}
		if (isset($process["referenceFunction"]["@attributes"]['generalComment']) == true) {
			$info['description'] .= $process["referenceFunction"]["@attributes"]["generalComment"];
		}
		if (isset($process["referenceFunction"]["@attributes"]['infrastructureProcess']) == true) {
			if ($process["referenceFunction"]["@attributes"]['infrastructureProcess'] == "Yes") {
				$info['description'] .= "This includes infrastructure processes.";
			} else {
				$info['description'] .= "This does not include infrastructure processes.";
			}
		}
		if (isset($process['geography']['@attributes']['text']) == true) {
				$info['description'] .= $process['geography']['@attributes']['text'];
		}	
		if (isset($process['technology']['@attributes']['text']) == true) {
				$info['description'] .= $process['technology']['@attributes']['text'];
		}	
		if ($info['description'] == "") {
			unset($info['description']);
		}
		
		// Time Span
		if (isset($process['timePeriod']) == true) {
			$info['timePeriod']['description'] = $process['timePeriod']['@attributes']['text'];
			$info['beginning'] = $process['timePeriod']['startYear'];
			$info['end'] = $process['timePeriod']['endYear'];
		}
		
		// Done
		return $info;
	}
	
	private function ecospold1Exchange($exchange, $ex = false) {
		$info['name'] = $exchange['@attributes']['name'];
		if ($ex != false) {
			$info['change_predicates']['Exchange'] = $ex;
		}
		// Values & uncertainty
		if (isset($exchange['@attributes']['amount']) == true) {
			$info['quantity'] = $exchange['@attributes']['amount'];
		}
		if (isset($exchange['@attributes']['meanValue']) == true) {
			$info['meanValue'] = $exchange['@attributes']['meanValue'];
		}
		if (isset($exchange['@attributes']['minValue']) == true) {
			$info['minValue'] = $exchange['@attributes']['minValue'];
		}
		if (isset($exchange['@attributes']['maxValue']) == true) {
			$info['maxValue'] = $exchange['@attributes']['maxValue'];
		}		
		if (isset($exchange['@attributes']['mostLikelyValue']) == true) {
			$info['mostLikelyValue'] = $exchange['@attributes']['mostLikelyValue'];
		}		
		if (isset($exchange['@attributes']['standardDeviation95']) == true) {
			$info['standardDeviation'] = $exchange['@attributes']['standardDeviation95'];
		}
		if (isset($exchange['@attributes']['standardDeviation95']) == true) {
			$info['standardDeviation'] = $exchange['@attributes']['standardDeviation95'];
		}

		// Categories/Compartments
		if (isset($exchange['@attributes']['category']) == true) {
			$compartments = array("agriculturalSoil","air","biotic","forestrySoil","fossil","fossilWater","freshWater","groundWater","industrySoil","lakeWater","lowAir","nonAgriculturalSoil","resource","resourceBiotic","resourceInAir","resourceInGround","resourceInWater","resourceLand","riverWater","seaWater","soil","surfaceWater","tropoStratoSphere","water","highAir");
			if (in_array(str_replace("-","",$exchange['@attributes']['category']),$compartments) == true) {
				$info['compartment'] = "fasc_".str_replace("-","",$exchange['@attributes']['category']);
			}
		}
		if (isset($exchange['@attributes']['subCategory']) == true) {
			$compartments = array("agriculturalSoil","air","biotic","forestrySoil","fossil","fossilWater","freshWater","groundWater","industrySoil","lakeWater","lowAir","nonAgriculturalSoil","resource","resourceBiotic","resourceInAir","resourceInGround","resourceInWater","resourceLand","riverWater","seaWater","soil","surfaceWater","tropoStratoSphere","water","highAir");
			if (in_array(str_replace("-","",$exchange['@attributes']['subCategory']).ucfirst($exchange['@attributes']['category']),$compartments) == true) {
				$info['compartment'] = "fasc_".str_replace("-","",$exchange['@attributes']['subCategory']).ucfirst($exchange['@attributes']['category']);
			}
		}	
			
		// Chemical-related fields
		if (isset($exchange['@attributes']['CASNumber']) == true) {
			$info['CASNumber'] = $exchange['@attributes']['CASNumber'];
			// Should create rdf CAS reference and just tack the CAS number onto the end of a url segment to create URI
		}
		if (isset($exchange['@attributes']['formula']) == true) {
			$info['formula'] = $exchange['@attributes']['formula'];
		}
		
		// Geography
		if (isset($exchange['@attributes']['location']) == true) {
			$cc = $this->geographymodel->getURIbyAlpha3($exchange['@attributes']['location']);
			if ($cc != false) {
				$info['geoLocation'] = $cc;
			} else {
				$info['geoLocation'] = $exchange['@attributes']['location'];
			}
		}
		
		// Comments
		if (isset($exchange['@attributes']['generalComment']) == true) {
			$info['comment'] = $exchange['@attributes']['generalComment'];
		}
		
		// Unit		
		if (isset($exchange['@attributes']['unit']) == true) {
			$info['unit'] = $exchange['@attributes']['unit'];
			$unit_uri = $this->unitmodel->getURIbyAbbr($exchange['@attributes']['unit']);
			if ($unit_uri !== false && $unit_uri !== null) {
				$info['unit'] = $unit_uri;
			} else {
				$unit_uri = $this->unitmodel->getURIbyExactLabel($exchange['@attributes']['unit']);
				if ($unit_uri !== false && $unit_uri !== null) {
					$info['unit'] = $unit_uri;
				}
			}
		}
		
		// Data Source
		if (isset($exchange['@attributes']['referenceToSource']) == true) {
			foreach ($this->lca_datasets[$key] as $set) {
				foreach ($set['bibliography'] as $ref) {
					if ($ref['esref'] == $exchange['@attributes']['referenceToSource']) {
						$info['source'] = $ref['uri'];
					}
				}
			}
		}
		$info['direction'] = "eco_Output";
		// Input/Output
		if (isset($exchange['inputGroup']) == true) {
			$info['direction'] = "eco_Input";
		} elseif (isset($exchange['outputGroup']) == true) {
			$info['direction'] = "eco_Output";
			if ($exchange['outputGroup'] == 0) {
				//$this->setReferenceProduct();
			}
		}
		if (isset($exchange['inputGroup']) == true) {
			if ($exchange['inputGroup'] == 4) {
				$info['change_predicates']['Transfer or Flow'] = "eco:hasFlowable";
				$info['exchangeType'] = "eco_Flow";
			} else {
				$info['exchangeType'] = "eco_Transfer";
			}
			if ($exchange['inputGroup'] == 2) {
				$info['exchange'] = "eco_Energy";
			} else {
				$info['exchange'] = "eco_Substance";
			}
		} elseif (isset($exchange['outputGroup']) == true) {
			if ($exchange['outputGroup'] == 4) {
				$info['change_predicates']['Transfer or Flow'] = "eco:hasFlowable";
				$info['exchangeType'] = "eco_Flow";
			} else {
				$info['exchangeType'] = "eco_Transfer";
			}
			$info['exchange'] = "eco_Product";
		}
		
		return $info;
	}

	private function ecospold1Organization($organization) {
		$info = array();
		$search_info = array();
		if (isset($organization['companyCode']) == true) {
			//$search_info['companyCode'] = $organization['companyCode'];
			$info['name'] = $organization['companyCode'];
			//$results = $this->arcmodel->searchOrganization($search_info);
		}
		/*
		if ($results != false) {
			$info['possibleIdentities'] = $results;
		}
		*/
		if (isset($organization['address']) == true) {
			$info['address'] = $organization['address'];
		}
		$info['local_uri'] = toURI('organization',$info['name']);
		return $info;
	}

	private function ecospold1Person($person) {
		//First, figure out this person's name & email
		// Email really makes a better key than name (or a combination of both) so we will look for email first
		
		$info = array();
		if (isset($person['@attributes']['number']) == true) {
			$info['esref'] = $person['@attributes']['number'];
		}
		$search_info = array();
		if (isset($person['email']) == true) {
			$info['email'] = $person['email'];
			$search_info = array('email'=>$person['email']);
		}
		$search_info = array();
		$info['name'] = $person['name'];
		if (strpos($person['name'],",") !== false) {
			$name = explode(",", $person['name']);
			$info['firstName'] = trim($name[1]);
			$info['lastName'] = trim($name[0]);			
		} elseif (((strpos($person['name']," ",strlen($person['name'])-2)-strlen($person['name'])+2) === 0 || ((strpos($person['name']," ",strlen($person['name'])-3)-strlen($person['name'])+3) === 0 && (strpos($person['name'],".",strlen($person['name'])-1)-strlen($person['name'])+1) === 0))) {
			$person['name'] = str_replace(".","", $person['name']);
			$info['firstName'] = trim(substr($person['name'], -1));
			$info['lastName'] = trim(substr($person['name'], 0,strlen($person['name'])-1));			
	
		} elseif (strpos($person['name']," ") !== false) {
			$name = explode(" ", $person['name']);	
			$info['firstName'] = trim($name[0]);
			$info['lastName'] = trim($name[1]);
		}
		$search_info['firstName'] = substr($info['firstName'],0,1);
		$search_info['lastName'] = $info['lastName'];
		// Now we have to check whether this person already has been accounted for, both earlier in the ecospold file or in our own database. We can do this by comparing first name and last. While the email is much more precise (it is essentially a unique key) there can be multiple emails per person, so we will just use first and last name. In the case that the first intial is given, we will try to match that. 
		
		foreach($this->lca_datasets as $people) {
			foreach($people['person'] as $p) {
				if (isset($p['local_uri']) == true) {
					if (substr($p['firstName'],0,1) == substr($info['firstName'],0,1) && $p['lastName'] == $info['lastName']) {
						unset($info);
						return array('uri'=>$p['local_uri']);
					}
				}
			}
		}
		$results = $this->peoplemodel->searchPeople($search_info);
		if ($results != false) {
			foreach ($results as $result) {
				unset($info);
				return array('uri'=>$result['uri']);
			}
		}
		if (isset($info['firstName']) == true && isset($info['lastName']) == true) {
			$info['local_uri'] = toURI('people',$info['firstName'].$info['lastName']);
		} else {
			$info['local_uri'] = toURI('person',$info['name']);
		}
		return $info;
	}
	
	public function index(){
		$this->check_if_logged_in();
		//$this->data("allusers", $allusers->result());
		// Send data to the view
		$this->display("Convert","converter_view");
	}
	
}