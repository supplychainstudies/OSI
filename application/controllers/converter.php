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
		$xmlRaw = file_get_contents($file);  
		$parsed = @$this->simplexml->xml_parse($xmlRaw);
		$parsed = @$this->simplexml->array2object($parsed);
		$uris = array();
		$ias = array();
		if (isset($parsed->dataset->metaInformation) == true) {
			$temp = $parsed->dataset;
			unset($parsed->dataset);
			$parsed->dataset->{"0"} = $temp;
		} 
		foreach ($parsed->dataset as $key=>$dataset) {
			if ($dataset->metaInformation->processInformation->dataSetInformation->{"@attributes"}->type == "4") { 
				$ias[] = $this->ecospold1ImpactAssessment($dataset);				
			} else {
				$this->lca_datasets[$key] = array();
				$this->lca_datasets[$key]['person'] = array();
				$this->lca_datasets[$key]['organization'] = array();
				$this->lca_datasets[$key]['model'] = array();
				$this->lca_datasets[$key]['process'] = array();
				$this->lca_datasets[$key]['bibliography'] = array();
				$this->lca_datasets[$key]['exchange'] = array();
				$lang = array();
				$lang[0] = $dataset->metaInformation->processInformation->dataSetInformation->{"@attributes"}->languageCode;
				$lang[1] = $dataset->metaInformation->processInformation->dataSetInformation->{"@attributes"}->localLanguageCode;				
			
				foreach($dataset->metaInformation->administrativeInformation->person as $person) {
					if (isset($person->{"@attributes"}) == true) {
						$p = $person->{"@attributes"};
					} else {
						$p = $person;
					}
					$_org = $this->ecospold1Organization($p);
					if (count($_org) != 0) {				
						$this->lca_datasets[$key]['organization'][] = $_org;
					}
					$_person = $this->ecospold1Person($p);
					if (count($_person) != 0) {
						if (isset($_person['local_uri']) == true) {
							$this->lca_datasets[$key]['person'][] = $_person;
						}
					}
				}
				foreach($dataset->metaInformation->modellingAndValidation->source as $source) {
					if (count($this->ecospold1Source($source)) > 0) {
						$this->lca_datasets[$key]['bibliography'][] = $this->ecospold1Source($source);
						$last = end($this->lca_datasets[$key]['bibliography']);
						if (isset($last['pass_authors']) == true) {
							foreach ($last['pass_authors'] as $person) {
								if (isset($person['local_uri']) == true) {
									$this->lca_datasets[$key]['person'][] = $person;
								}
							}
						}
						if (isset($last['pass_org']) == true) {
							foreach ($last['pass_org'] as $person) {
								if (isset($person['local_uri']) == true) {
									$this->lca_datasets[$key]['organization'][] = $person;
								}
							}
						}
					}
				} 
				if (isset($dataset->metaInformation->modellingAndValidation->validation) == true) {
					foreach($dataset->metaInformation->modellingAndValidation->validation as $validation) {
						$_validation = $this->ecospold1Validation($validation);
						if (count($_validation) > 0) {
							$this->lca_datasets[$key]['validation'][] = $_validation;
						}
					}
				}
				if (count($this->ecospold1Model($dataset->metaInformation)) != 0) {
					$this->lca_datasets[$key]['model'][] = $this->ecospold1Model($dataset->metaInformation);
				}
				$_process = $this->ecospold1Process($dataset->metaInformation->processInformation);
				if (count($_process) != 0) {
					$this->lca_datasets[$key]['process'][] = $_process;
					if (isset($dataset->metaInformation->processInformation->referenceFunction) == true) {
						if ($dataset->metaInformation->processInformation->referenceFunction->{"@attributes"}->datasetRelatesToProduct = "yes") {
							$this->lca_datasets[$key]['exchange'][] = $this->ecospold1Exchange($dataset->metaInformation->processInformation->referenceFunction, "eco:hasReferenceExchange");
						}
					}
				}				
				$exchanges = array();
				foreach($dataset->flowData->exchange as $exchange) {
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
		}
		if (count($ias) > 0) {
			foreach ($ias as $key=>$ia) {
				foreach ($ia as $field=>$value) {
					$this->lca_datasets[0]['impactAssessment'][0][$field][$key] = $value;
				}
			}
			$this->lca_datasets[0]['impactAssessment'][0]['assessmentOf'] = $this->lca_datasets[0]['process'][0]['local_uri'];
			$this->lca_datasets[0]['impactAssessment'][0]['computedFrom'] = $uris[0];
		} 
		// *********************************************************************************
		$view_string = "";
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
					//var_dump($triples);
				}
			}
			$view_string .= '<a href="/'.str_replace('http://footprinted.org/rdfspace/lca/','',$uris[$key]).'">'.$this->lca_datasets[$key]['process'][0]['name'][0].'</a><br />'.$this->lca_datasets[$key]['process'][0]['description'].'<br /><br />';
			
		}
		$this->data("view_string",$view_string);
		$this->display("Your New LCAs", "view");
		
		// Non-auto
		//$this->session->set_userdata('convert_json', json_encode($this->lca_datasets));
		//$this->session->set_userdata('convert_uris', json_encode($uris));
		//$this->forms();
		// *********************************************************************************		
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
	
	public function objthis($data) {
		foreach ($data as &$_data) {
			if (is_array($_data) == true) {
				$_data = (object)$_data;
			} 
			if (is_object($_data) == true) {
				$_data = $this->objthis($_data);
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


	/*
	
	
	
	
	*/
	private function ecospold1ImpactAssessment ($ia) {
		if(isset($ia->processInformation->referenceFunction->{"@attributes"}->category) == true) {
			$info['impactAssessmentMethod'] = $ia->processInformation->referenceFunction->{"@attributes"}->category;
		}		
		if(isset($ia->processInformation->referenceFunction->{"@attributes"}->subCategory) == true) {
			$info['impactCategory'] = $ia->processInformation->referenceFunction->{"@attributes"}->subCategory;
		}
		if(isset($ia->processInformation->referenceFunction->{"@attributes"}->name) == true) {
			$info['impactCategoryIndicator'] = $ia->processInformation->referenceFunction->{"@attributes"}->name;
		}
		if(isset($ia->processInformation->referenceFunction->{"@attributes"}->amount) == true) {
			$info['quantity'] = $ia->processInformation->referenceFunction->{"@attributes"}->amount;
		}
		if(isset($ia->processInformation->referenceFunction->{"@attributes"}->unit) == true) {
			$info['unit'] = $ia->processInformation->referenceFunction->{"@attributes"}->unit;
		}
	}
	
	/*
	
	
	
	
	*/
	private function ecospold1Source ($source) {
		$info = array(); 
		$info['pass_authors'] = array();
		if (isset($source->{"@attributes"}->number) == true) {
			$info['esref'] = $source->{"@attributes"}->number;
		}
		if (isset($source->{"@attributes"}->title) == true) {
			$info['title'] = $source->{"@attributes"}->title;
		} 
		if (isset($source->{"@attributes"}->placeOfPublications) == true) {
			$info['location'] = $source->{"@attributes"}->placeOfPublications;
		}		
		if (isset($source->{"@attributes"}->publisher) == true) {
			$info['publisher'] = $source->{"@attributes"}->publisher;
		}
		if (isset($source->{"@attributes"}->volumeNo) == true) {
			$info['volume'] = $source->{"@attributes"}->volumeNo;
		}
		if (isset($source->{"@attributes"}->issueNo) == true) {
			$info['issue'] = $source->{"@attributes"}->issueNo;
		}
		if (isset($source->{"@attributes"}->nameOfEditors) == true) {
			$editors = explode(",",$source['@attributes->editors']);
			foreach ($editors as $editor) {
				$_editor = $this->ecospold1Person((object)array('name'=>trim($editor)));
				if (isset($_editor['local_uri']) == true) {
					$info['editor'][] = $_editor['local_uri'];
					$info['pass_authors'][] = $_editor;
				} elseif (isset($_editor['uri']) == true) {
					$info['editor'][] = $_editor['uri'];
				}
			}
		}
		if (isset($source->{"@attributes"}->pageNumbers) == true) {
			$info['pageNumbers'] = $source->{"@attributes"}->pageNumbers;
		}
		if (isset($source->{"@attributes"}->year) == true) {
			$info['date'] = $source->{"@attributes"}->year;
		}
		if (isset($source->{"@attributes"}->titleOfAnthology) == true) {
			$info['partOf'] = $source->{"@attributes"}->titleOfAnthology;
		}
		if (isset($source->{"@attributes"}->journal) == true) {
			$info['partOf'] = $source->{"@attributes"}->journal;
		}
		if (isset($source->{"@attributes"}->text) == true) {
			$info['comment'] = $source->{"@attributes"}->text;
		}
		if (isset($source->{"@attributes"}->firstAuthor) == true) {
			if (count(explode(" ",$source->{"@attributes"}->firstAuthor)) == 1 || count(explode(" ",$source->{"@attributes"}->firstAuthor)) > 3) {
				$_author = $this->ecospold1Organization((object)array('companyCode'=>$source->{"@attributes"}->firstAuthor));
				if (isset($_author['local_uri']) == true) {
					$info['author'][] = $_author['local_uri'];
					$info['pass_org'][] = $_author;
				} elseif (isset($_author['uri']) == true) {
					$info['author'][] = $_author['uri'];
				}
			} else {
				$_author = $this->ecospold1Person((object)array('name'=>$source->{"@attributes"}->firstAuthor));
				if (isset($_author['local_uri']) == true) {
					$info['author'][] = $_author['local_uri'];
					$info['pass_authors'][] = $_author;
				} elseif (isset($_author['uri']) == true) {
					$info['author'][] = $_author['uri'];
				}
				if (isset($source->{"@attributes"}->additionalAuthors) == true) {	
					if (trim($source->{"@attributes"}->additionalAuthors) != "") {
						$authors = explode(",",$source->{"@attributes"}->additionalAuthors);
						foreach ($authors as $author) {
							$_author = $this->ecospold1Person((object)array('name'=>trim($author)));
							if (isset($_author['local_uri']) == true) {
								$info['author'][] = $_author['local_uri'];
								$info['pass_authors'][] = $_author;
							} elseif (isset($_author['uri']) == true) {
								$info['author'][] = $_author['uri'];
							}
						}
					}
				}	
			}		
		}
		if (isset($source->{"@attributes"}->sourceType) == true) {
			// 0=Undefined (default) 1=Article 2=Chapters in anthology 3=Separate publication 4=Measurement on site 5=Oral communication 6=Personal written communication 7=Questionnaries
			// FIX: figure out what each type will correspond to.
			switch ($source->{"@attributes"}->sourceType) {
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
		if (isset($process->processInformation->referenceFunction->{"@attributes"}->name) == true) {
			$info['name'][] = $process->processInformation->referenceFunction->{"@attributes"}->name;
		}
		if (isset($process->processInformation->referenceFunction->{"@attributes"}->localName) == true) {
			$info['name'][] = $process->processInformation->referenceFunction->{"@attributes"}->localName;
		}
		if (isset($process->administrativeInformation->dataGeneratorAndPublication->{"@attributes"}->copyright) == true) {
			if ($process->administrativeInformation->dataGeneratorAndPublication->{"@attributes"}->copyright == 1) {
				$info['rights'] = "Copyrighted";
			} else {
				$info['rights'] = "Not Copyrighted";
			}
		}
		if (isset($process->administrativeInformation->dataGeneratorAndPublication->{"@attributes"}->referenceToPublishedSource) == true) {
			foreach ($this->lca_datasets as $set) {
				foreach ($set['bibliography'] as $ref) {
					if ($ref['esref'] == $process->administrativeInformation->dataGeneratorAndPublication->{"@attributes"}->referenceToPublishedSource) {
						$info['dataSource'] = $ref['local_uri'];
					}
				}
			}
		}
		
		// Big Description
		if (isset($process->processInformation->referenceFunction->{"@attributes"}->includedProcesses) == true) {
			$info['description'] .= $process->processInformation->referenceFunction->{"@attributes"}->includedProcesses;
		}
		if (isset($process->processInformation->referenceFunction->{"@attributes"}->generalComment) == true) {
			$info['description'] .= $process->processInformation->referenceFunction->{"@attributes"}->generalComment;
		}
		if (isset($process->processInformation->referenceFunction->{"@attributes"}->infrastructureProcess) == true) {
			if ($process->processInformation->referenceFunction->{"@attributes"}->infrastructureProcess == "Yes") {
				$info['description'] .= "This includes infrastructure processes.";
			} else {
				$info['description'] .= "This does not include infrastructure processes.";
			}
		}
		if (isset($process->processInformation->geography->{"@attributes"}->text) == true) {
				$info['description'] .= $process->processInformation->geography->{"@attributes"}->text;
		}
		if (isset($process->processInformation->technology->{"@attributes"}->text) == true) {
				$info['description'] .= $process->processInformation->technology->{"@attributes"}->text;
		}
		
		// Categories
		if (isset($process->processInformation->referenceFunction->{"@attributes"}->category) == true) {
			$info['category'][] = $process->processInformation->referenceFunction->{"@attributes"}->category;
		}
		if (isset($process->processInformation->referenceFunction->{"@attributes"}->subCategory) == true) {
			$info['category'][] = $process->processInformation->referenceFunction->{"@attributes"}->subCategory;
		}
		
		// Time Period
		if (isset($process->processInformation->timePeriod->startYear) == true) {
			$info['beginning'][] = $process->processInformation->timePeriod->startYear;
		}
		if (isset($process->processInformation->timePeriod->endYear) == true) {
			$info['end'][] = $process->processInformation->timePeriod->endYear;
		}
		if (isset($process->processInformation->timePeriod->startDate) == true) {
			$info['beginning'][] = $process->processInformation->timePeriod->startDate;
		}
		if (isset($process->processInformation->timePeriod->endDate) == true) {
			$info['end'][] = $process->processInformation->timePeriod->endDate;
		}
		return $info;
	}

	/*
	Ecospold 1 looks something like:
	<validation proofReadingDetails="automatic validation" proofReadingValidator="41" otherDetails="none"></validation>	
		
	so, we pass the attributes and return a set that will look like this:
	array (
		"responsibleAgent" => "http://footprinted.org/biancasayan4534523",
		"description" => "Everything looks good. good job."
	);
	
	when we pass this through the triples generator, the triples will look like this:
	http://footprinted.org/rdfspace/person/timgrant3445345
		eco:hasValidationResult _:validationResult345345345
			eco:responsibleAgent	"http://footprinted.org/biancasayan4534523"
			eco:hasReportText	"Everything looks good. good job."
	*/
		
	private function ecospold1Validation($validation) {
		$info = array();
		$info['description'] = "";
		if (isset($validation->proofReadingValidator) == true) {
			foreach ($this->lca_datasets as $set) {
				foreach ($set['person'] as $ref) {
					if (isset($ref['esref']) == true) {
						if ($ref['esref'] == $process->proofReadingValidator) {
							$info['responsibleAgent'] = $ref['local_uri'];
						}
					}
				}
			}
		}
		if (isset($validation->proofReadingDetails) == true) {
			$info['description'] .= $validation->proofReadingDetails;
		}
		if (isset($validation->otherDetails) == true) {
			$info['description'] .= $validation->otherDetails;
		}		
		return $info;
	}
	
	
	private function ecospold1Process($process) {
		$info = array();
		
		// Process Names
		if (isset($process->referenceFunction->{"@attributes"}->name) == true) {
			$info['name'][] = $process->referenceFunction->{"@attributes"}->name;
		}
		if (isset($process->referenceFunction->{"@attributes"}->synonym) == true) {
			foreach (explode("//",$process->referenceFunction->{"@attributes"}->synonym) as $name) {
				$info['name'][] = $name;
			}
		}
		// NACE Classification
		if (isset($process->referenceFunction->{"@attributes"}->statisticalClassification) == true) {
			$nace = $this->nacemodel->getURIbyCode($process->referenceFunction->{"@attributes"}->statisticalClassification);
			if ($nace != false) {
				$info['category'][] = $nace;				
			}
		}
		
		// Geography
		if (isset($process->geography) == true) {
			//Look up the location by country code
			if (strlen($process->geography->{"@attributes"}->location) == 3) {
				$cc = $this->geographymodel->getURIbyAlpha3($process->geography->{"@attributes"}->location);
			} elseif (strlen($process->geography->{"@attributes"}->location) == 2) {
				$cc = $this->geographymodel->getURIbyAlpha2($process->geography->{"@attributes"}->location);
			}
			if ($cc != false) {
				$info['geoLocation'] = $cc;
			} else {
				$info['geoLocation'] = $process->geography->{"@attributes"}->location;
			}
		}
		
		// Piece together a description
		$info['description'] = "";
		if (isset($process->referenceFunction->{"@attributes"}->includedProcesses) == true) {
			$info['description'] .= $process->referenceFunction->{"@attributes"}->includedProcesses;
		}
		if (isset($process->referenceFunction->{"@attributes"}->generalComment) == true) {
			$info['description'] .= $process->referenceFunction->{"@attributes"}->generalComment;
		}
		if (isset($process->referenceFunction->{"@attributes"}->infrastructureProcess) == true) {
			if ($process->referenceFunction->{"@attributes"}->infrastructureProcess == "Yes") {
				$info['description'] .= "This includes infrastructure processes.";
			} else {
				$info['description'] .= "This does not include infrastructure processes.";
			}
		}
		if (isset($process->geography->{"@attributes"}->text) == true) {
				$info['description'] .= $process->geography->{"@attributes"}->text;
		}	
		if (isset($process->technology->{"@attributes"}->text) == true) {
				$info['description'] .= $process->technology->{"@attributes"}->text;
		}	
		if ($info['description'] == "") {
			unset($info['description']);
		}
		
		// Time Span
		// Time Period
		if (isset($process->timePeriod->startYear) == true) {
			$info['beginning'][] = $process->timePeriod->startYear;
		}
		if (isset($process->timePeriod->endYear) == true) {
			$info['end'][] = $process->timePeriod->endYear;
		}
		if (isset($process->timePeriod->startDate) == true) {
			$info['beginning'][] = $process->timePeriod->startDate;
		}
		if (isset($process->timePeriod->endDate) == true) {
			$info['end'][] = $process->timePeriod->endDate;
		}
		
		// Done
		return $info;
	}
	
	
	/*
	Ecospold 1 looks something like:
	<exchange 
		number="2" 
		name="hard coal power plant, 500MW" 
		location="GLO" 
		unit="p" 
		meanValue="1.94E-9" 
		uncertaintyType="1" 
		standardDeviation95="3" 
		generalComment="Data is from ecoinvent unmodified." 
		infrastructureProcess="true">
		
		Exchanges can also have these attributes:
		localName
		category
		subCategory
		localCategory
		localSubCategory
		CASNumber	
		formula
		referenceToSource
		pageNumbers=
		infrastructureProcess
		minValue
		maxValue
		mostLikelyValue
		inputGroup
		outputGroup
		
		to incorporate:
			infrastructure process
			localName
			localCategory
			localSubCategory
			pageNumbers=
		
		
	so, we pass the attributes and return a set that will look like this:
	array (
		"name" => "hard coal power plant, 500MW",
		"meanValue" => "1.94E-9",
		"unit" => "p",
		"standardDeviation95" => "3",
		"description" => "Data is from ecoinvent unmodified.",
		"geolocation" => "http://",
	);
	
	- the location is searched for in the geonames database. if found, the uri for that location is used

	
	when we pass this through the triples generator, the triples will look like this:
	http://footprinted.org/rdfspace/person/timgrant3445345
		eco:hasUnallocatedExchange _:exchange345345345
			eco:hasCompartment
			eco:hasEffect 
			eco:hasQuantity
				eco:meanValue 
				eco:hasUnitOfMeasure "p"
	*/
	
	
	private function ecospold1Exchange($exchange, $ex = false) {
		$info['name'] = $exchange->{"@attributes"}->name;
		if ($ex != false) {
			$info['change_predicates']['Exchange'] = $ex;
		}
		// Values & uncertainty
		if (isset($exchange->{"@attributes"}->amount) == true) {
			$info['quantity'] = $exchange->{"@attributes"}->amount;
		}
		if (isset($exchange->{"@attributes"}->meanValue) == true) {
			$info['meanValue'] = $exchange->{"@attributes"}->meanValue;
		}
		if (isset($exchange->{"@attributes"}->minValue) == true) {
			$info['minValue'] = $exchange->{"@attributes"}->minValue;
		}
		if (isset($exchange->{"@attributes"}->maxValue) == true) {
			$info['maxValue'] = $exchange->{"@attributes"}->maxValue;
		}		
		if (isset($exchange->{"@attributes"}->mostLikelyValue) == true) {
			$info['mostLikelyValue'] = $exchange->{"@attributes"}->mostLikelyValue;
		}		
		if (isset($exchange->{"@attributes"}->standardDeviation95) == true) {
			$info['standardDeviation'] = $exchange->{"@attributes"}->standardDeviation95;
		}
		if (isset($exchange->{"@attributes"}->uncertaintyType) == true) {
			if ($exchange->{"@attributes"}->uncertaintyType == "1") {
				$info['uncertaintyDistribution'] = "ecoUD_LogNormalDistrubution";
			} elseif ($exchange->{"@attributes"}->uncertaintyType == "2") {
				$info['uncertaintyDistribution'] = "ecoUD_NormalDistrubution";
			} elseif ($exchange->{"@attributes"}->uncertaintyType == "3") {
				$info['uncertaintyDistribution'] = "ecoUD_TriangularDistrubution";
			} elseif ($exchange->{"@attributes"}->uncertaintyType == "4") {
				$info['uncertaintyDistribution'] = "ecoUD_UniformDistrubution";
			}
		}

		// Categories/Compartments
		if (isset($exchange->{"@attributes"}->category) == true) {
			$compartments = array("agriculturalSoil","air","biotic","forestrySoil","fossil","fossilWater","freshWater","groundWater","industrySoil","lakeWater","lowAir","nonAgriculturalSoil","resource","resourceBiotic","resourceInAir","resourceInGround","resourceInWater","resourceLand","riverWater","seaWater","soil","surfaceWater","tropoStratoSphere","water","highAir");
			if (in_array(str_replace("-","",$exchange->{"@attributes"}->category),$compartments) == true) {
				$info['compartment'] = "fasc_".str_replace("-","",$exchange->{"@attributes"}->category);
			} else {
				$info['category'][] = $exchange->{"@attributes"}->category;
			}
		}
		if (isset($exchange->{"@attributes"}->subCategory) == true) {
			$compartments = array("agriculturalSoil","air","biotic","forestrySoil","fossil","fossilWater","freshWater","groundWater","industrySoil","lakeWater","lowAir","nonAgriculturalSoil","resource","resourceBiotic","resourceInAir","resourceInGround","resourceInWater","resourceLand","riverWater","seaWater","soil","surfaceWater","tropoStratoSphere","water","highAir");
			if (in_array(str_replace("-","",$exchange->{"@attributes"}->subCategory).ucfirst($exchange->{"@attributes"}->category),$compartments) == true) {
				$info['compartment'] = "fasc_".str_replace("-","",$exchange->{"@attributes"}->subCategory).ucfirst($exchange->{"@attributes"}->category);
			} else {
				$info['category'][] = $exchange->{"@attributes"}->subCategory;
			}
		}	
			
		// Chemical-related fields
		if (isset($exchange->{"@attributes"}->CASNumber) == true) {
			$info['CASNumber'] = $exchange->{"@attributes"}->CASNumber;
			// Should create rdf CAS reference and just tack the CAS number onto the end of a url segment to create URI
		}
		if (isset($exchange->{"@attributes"}->formula) == true) {
			$info['formula'] = $exchange->{"@attributes"}->formula;
		}
		
		// Geography
		if (isset($exchange->{"@attributes"}->location) == true) {
			//Look up the location by country code
			if (strlen($exchange->{"@attributes"}->location) == 3) {
				$cc = $this->geographymodel->getURIbyAlpha3($exchange->{"@attributes"}->location);
			} elseif (strlen($exchange->{"@attributes"}->location) == 2) {
				$cc = $this->geographymodel->getURIbyAlpha2($exchange->{"@attributes"}->location);
			}
			if ($cc != false) {
				$info['geoLocation'] = $cc;
			} else {
				$info['geoLocation'] = $exchange->{"@attributes"}->location;
			}	
		}
		
		// Comments
		if (isset($exchange->{"@attributes"}->generalComment) == true) {
			$info['comment'] = $exchange->{"@attributes"}->generalComment;
		}
		
		// Unit		
		if (isset($exchange->{"@attributes"}->unit) == true) {
			$info['unit'] = $exchange->{"@attributes"}->unit;
			$unit_uri = $this->unitmodel->getURIbyAbbr($exchange->{"@attributes"}->unit);
			if ($unit_uri !== false && $unit_uri !== null) {
				$info['unit'] = $unit_uri;
			} else {
				$unit_uri = $this->unitmodel->getURIbyExactLabel($exchange->{"@attributes"}->unit);
				if ($unit_uri !== false && $unit_uri !== null) {
					$info['unit'] = $unit_uri[0]['uri'];
				}
			}
		}
		
		// Data Source
		if (isset($exchange->{"@attributes"}->referenceToSource) == true) {
			foreach ($this->lca_datasets[$key] as $set) {
				foreach ($set['bibliography'] as $ref) {
					if ($ref['esref'] == $exchange->{"@attributes"}->referenceToSource) {
						$info['source'] = $ref['uri'];
					}
				}
			}
		}
		$info['direction'] = "eco_Output";
		// Input/Output
		if (isset($exchange->inputGroup) == true) {
			$info['direction'] = "eco_Input";
		} elseif (isset($exchange->outputGroup) == true) {
			$info['direction'] = "eco_Output";
			// Stupid fix for stupid parsing
			if (is_object($exchange->outputGroup) == true) {
				foreach ($exchange->outputGroup as $key=>$x) {
					$exchange->outputGroup = $x;
				}
			}
			// End of stupid fix for stupid parsing
			if ($exchange->outputGroup == "0") {
				//$this->setReferenceProduct();
			}
		}
		if (isset($exchange->inputGroup) == true) {
			if ($exchange->inputGroup == "4") {
				$info['change_predicates']['Transfer or Flow'] = "eco:hasFlowable";
				$info['exchangeType'] = "eco_Flow";
			} else {
				$info['exchangeType'] = "eco_Transfer";
			}
			if ($exchange->inputGroup == "2") {
				$info['exchange'] = "eco_Energy";
			} else {
				$info['exchange'] = "eco_Substance";
			}
		} elseif (isset($exchange->outputGroup) == true) {
			if ($exchange->outputGroup == "4") {
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
		if (isset($organization->companyCode) == true) {
			$info['name'] = $organization->companyCode;
		}
		if (isset($organization->address) == true) {
			$info['address'] = $organization->address;
		}
		if (isset($organization->telephone) == true) {
			$info['phone'] = $organization->telephone;
		}
		if (isset($organization->telefax) == true) {
			$info['fax'] = $organization->telefax;
		}
		if (isset($organization->countryCode) == true) {
			$info['location'] = $organization->countryCode;
		}
		$info['local_uri'] = toURI('organization',$info['name']);
		return $info;
	}

	/*
	Ecospold 1 looks something like:
	<person number="2" name="Bianca Sayan" address="Unknown" telephone="617 345 1055" email="info@footprinted.org" companyCode="LCS" countryCode="AU"/>
	
	so, we pass the attributes and return a set that will look like this:
	array (
		"name" => "Bianca Sayan",
		"firstName" => "Bianca",
		"lastName" => "Sayan",
		"email" => "info@footprinted.org",
		"esref" => "2",
		"local_uri" => "http://footprinted.org/rdfspace/person/timgrant3445345"
	);
	
	- esref is strictly used to relate the entry to bibliographic sources, validation, etc within the ecospold file. it is not saved
	- local_uri is the 
	- the function may return an array like so:
	array (
		"uri" => "http://footprinted.org/rdfspace/person/timgrant3445345"
	);	
	in that case, there is already a person with that name in the database or in the ecospold file. instead of creating a new record, any references will refer to the existing entry
	
	when we pass this through the triples generator, the triples will look like this:
	http://footprinted.org/rdfspace/person/timgrant3445345
		rdfs:type foaf:Person
		foaf:name
		foaf:firstName "Bianca"
		foaf:LastName "Sayan"
		foaf:mbox_sha1sum "info@footprinted.org"
	*/	
	
	private function ecospold1Person($person) {
		//First, figure out this person's name & email
		// Email really makes a better key than name (or a combination of both) so we will look for email first
		$info = array();
		if (isset($person->{"@attributes"}->number) == true) {
			$info['esref'] = $person->{"@attributes"}->number;
		}
		$search_info = array();
		if (isset($person->email) == true) {
			$info['email'] = $person->email;
			$search_info = array('email'=>$person->email);
		}
		$search_info = array();
		$info['name'] = $person->name;
		$info['firstName'] = "";
		$info['lastName'] = "";
		if (strpos($person->name,",") !== false) {
			$name = explode(",", $person->name);
			$info['firstName'] = trim($name[1]);
			$info['lastName'] = trim($name[0]);			
		} elseif (((strpos($person->name," ",strlen($person->name)-2)-strlen($person->name)+2) === 0 || ((strpos($person->name," ",strlen($person->name)-3)-strlen($person->name)+3) === 0 && (strpos($person->name,".",strlen($person->name)-1)-strlen($person->name)+1) === 0))) {
			$person->name = str_replace(".","", $person->name);
			$info['firstName'] = trim(substr($person->name, -1));
			$info['lastName'] = trim(substr($person->name, 0,strlen($person->name)-1));			
	
		} elseif (strpos($person->name," ") !== false) {
			$name = explode(" ", $person->name);	
			$info['firstName'] = trim($name[0]);
			$info['lastName'] = trim($name[1]);
		}
		$search_info['firstName'] = substr($info['firstName'],0,1);
		$search_info['lastName'] = $info['lastName'];
		
		
		if (isset($person->address) == true) {
			$info['address'] = $person->address;
		}
		if (isset($person->telephone) == true) {
			$info['phone'] = $person->telephone;
		}
		if (isset($person->telefax) == true) {
			$info['fax'] = $pereson->telefax;
		}
		if (isset($person->companyCode) == true) {
			$info['organization'] = $person->companyCode;
		}
		
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
		if ($_POST) {
			if ($_POST['format'] == "eco1") {
				$this->ecospold1auto($_FILES['uploadedfile']['tmp_name']);
			}
		} else {
			$this->display("Convert","converter_view");
		}
	}
	
}