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



class Converter extends SM_Controller {
	public function Converter() {
		parent::SM_Controller();
		$this->check_if_logged_in();
		$this->load->library(Array('xml', 'Simplexml', 'form_extended'));
		$this->load->model(Array('arcmodel', 'arcremotemodel'));		
	}	
	
	public $lcas = array();	
		
	/***
    * @public
    * Allows you to edit an entry
	* This is not functional yet
    */	
	public function index($file = null, $format = null) {
		# LOAD XML FILE
		$file = "http://osi/assets/examples/Ecospold_biomass-8.8_miscanthus";
		
		/*
		$format = "http://osi/assets/transforms/EcoSpold01toEcospold02.xsl";
		var_dump($file);
		$xml_doc = new DOMDocument();
		$xml_doc->load( $file );
		var_dump($xml_doc);
		# START XSLT
		$xslt_doc = new XSLTProcessor();

		# IMPORT STYLESHEET 1
		$xsl_output = new DOMDocument();
		$xsl_output->load( $format );
		$xslt_doc->importStylesheet( $xsl_output );
/*
		#IMPORT STYLESHEET 2
		$this->XSL = new DOMDocument();
		$this->XSL->load( 'template2.xsl' );
		$this->xslt->importStylesheet( $XSL );
*/
		#PRINT
		print $this->xslt->transformToXML( $xml_doc );
		

	}
	
	
	
	public function ecospold1($file = null, $format = null) {
		# LOAD XML FILE
		/*
		$file = "data/datasets/Ecospold1/Ecospold_biomass-8.8_miscanthus";
		$this->xml->load($file);	
		$parsed = $this->xml->parse();
		*/
		$xmlfile = "http://osi/assets/examples/Ecospold_biomass-8.8_miscanthus.xml";
		$xmlRaw = file_get_contents($xmlfile);  
		$parsed = @$this->simplexml->xml_parse($xmlRaw);
		$view_string = "<div id=\"tabulator_menu\"></div>";
		$data = $this->form_extended->load('converter');	
		$show = " show";
		//var_dump($parsed);
		foreach ($parsed['dataset'] as $key=>$dataset) {
			$this->lca_datasets[$key] = array();
			$this->lca_datasets[$key]['people'] = array();
			$this->lca_datasets[$key]['organization'] = array();
			$this->lca_datasets[$key]['processes'] = array();
			$this->lca_datasets[$key]['sources'] = array();
			$this->lca_datasets[$key]['exchanges'] = array();
			foreach($dataset['metaInformation'] as $_key=>$process) {
				if ($key == "processInformation") {
					//$processes[] = $this->ecospold1Process($process);
					$this->lca_datasets[$key]['processes'] = $this->ecospold1Process($process);
				}
			}
			foreach($dataset['metaInformation']['administrativeInformation']['person'] as $person) {
				//$info['people'][$person['number']] = $this->ecospold1Person($person);
				//$info['organization'][] = $this->ecospold1Organization($person);
				$this->lca_datasets[$key]['people'] = $this->ecospold1Person($person);
				$this->lca_datasets[$key]['organization'] = $this->ecospold1Organization($person);
			}
			$exchanges = array();
			foreach($dataset['flowData']['exchange'] as $exchange) {
				//$info['exchanges'][] = $this->ecospold1Exchange($exchange);
				$this->lca_datasets[$key]['exchanges'][] = $this->ecospold1Exchange($exchange);
			}
			foreach($dataset['metaInformation']['modellingAndValidation'] as $source) {
				//$info['sources'][] = $this->ecospold1Source($source);
				$this->lca_datasets[$key]['sources'][] = $this->ecospold1Source($source);
			}
			$view_string .= "<div id=\"dataset_" . $key . "\" class=\"tabulate" . $show . "\">" . $this->form_extended->build_flat_edit($this->lca_datasets[$key], $data) . "</div>";
			$show = "";
		}
		$links = "";
		$this->data("links", $links);
		
		//$this->data("info", $this->lca_datasets);
		$this->script(Array('janrain.js','form.js', 'toggle.js', 'tabulator.js'));
		$this->data("view_string", $view_string);
		$this->display("Conversion - Step 1 ", "converter_view");
	}
	
	private function ecospold1Source ($source) {
		$info = array(); 
		if (isset($source['@attributes']['title']) == true) {
			$info['title'] = $source['@attributes']['title'];
		} 
		if (isset($source['@attributes']['titleOfAnthology']) == true) {
			// how does this work depending on the type? dont know yet
			//$info['title'] = $source['@attributes']['titleOfAnthology'];
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
		if (isset($source['@attributes']['text']) == true) {
			$info['comment'] = $source['@attributes']['text'];
		}
		if (isset($source['@attributes']['firstAuthor']) == true) {
			if (strpos($source['@attributes']['firstAuthor'], ",") !== false) {
				$name = explode($source['@attributes']['firstAuthor'], ",");
				$info['authorList'][] = array ('firstName'=> $name[1], 'lastName' => $name[0]);
			} elseif (substr($source['@attributes']['firstAuthor'], -1) == ".") {
				$name = explode($source['@attributes']['firstAuthor'], " ");
				$info['authorList'][] = array ('firstName'=> $name[1], 'lastName' => $name[0]);
			} elseif (strpos($source['@attributes']['firstAuthor'], " ") !== false) {
				$name = explode($source['@attributes']['firstAuthor'], " ");
				$info['authorList'][] = array ('firstName'=> $name[0], 'lastName' => $name[1]);
			}
			if (isset($source['@attributes']['additionalAuthors']) == true) {
				$authors = explode();
				foreach ($authors as $author) {
					if (strpos($author, ",") !== false) {
						$name = explode($author, ",");
						$info['authorList'][] = array ('firstName'=> $name[1], 'lastName' => $name[0]);
					} elseif (substr($author, -1) == ".") {
						$name = explode($author, " ");
						$info['authorList'][] = array ('firstName'=> $name[1], 'lastName' => $name[0]);
					} elseif (strpos($author, " ") !== false) {
						$name = explode($author, " ");
						$info['authorList'][] = array ('firstName'=> $name[0], 'lastName' => $name[1]);
					}
				}
			}			
		}
		if (isset($source['@attributes']['sourceType']) == true) {
			// 0=Undefined (default) 1=Article 2=Chapters in anthology 3=Separate publication 4=Measurement on site 5=Oral communication 6=Personal written communication 7=Questionnaries
			// FIX: figure out what each type will correspond to.
			switch ($source['@attributes']['sourceType']) {
			    case 0:
			        $info['type'] = "Document";
			        break;
			    case 1:
			        $info['type'] = "article";			        
			        break;
			    case 2:
			        $info['type'] = "Book";			        
			        break;
			    case 3:
			        $info['type'] = "Document";			        
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
		return $info;		
	}
	
	private function ecospold1Process($process) {
		$info = array();
		//var_dump($process["referenceFunction"]["@attributes"]["name"]);
		$info['name'] = $process["referenceFunction"]["@attributes"]["name"];
		if (isset($process['geography']) == true) {
			$info['geography']['uri'] = $process['geography']['@attributes']['location'];
			// In order to extract geography, we should create a doc that associates the ecospold 1 region codes with geonames region uris, either with rdfs:label or sameAs
			$info['geography']['description'] = $process['geography']['@attributes']['text'];
		}
		if (isset($process['timePeriod']) == true) {
			$info['timePeriod']['description'] = $process['timePeriod']['@attributes']['text'];
			$info['timePeriod']['startYear'] = $process['timePeriod']['startYear'];
			$info['timePeriod']['endYear'] = $process['timePeriod']['endYear'];
		}
		return $info;
	}
	
	private function ecospold1Exchange($exchange) {
		$info['name'] = $exchange['@attributes']['name'];
		
		
		// Values & uncertainty
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
		
		// Other fields
		if (isset($exchange['@attributes']['CASNumber']) == true) {
			$info['CASNumber'] = $exchange['@attributes']['CASNumber'];
			// Should create rdf CAS reference and just tack the CAS number onto the end of a url segment to create URI
		}
		if (isset($exchange['@attributes']['location']) == true) {
			//$info['location'] = 
			// where does the location code come from? we can use geonames for country codes, but RER is a mystery. Solutions: either mostly rely on geonames, or figure out if ecospold 2 has a regional doc that works
		}
		if (isset($exchange['@attributes']['generalComment']) == true) {
			$info['comment'] = $exchange['@attributes']['generalComment'];
		}		
		if (isset($exchange['@attributes']['unit']) == true) {
			$info['unit'] = $exchange['@attributes']['unit'];
			if (count($this->arcremotemodel->getURIFromLabel($exchange['@attributes']['unit'])) != 0) {
				$info['unit'] = $this->arcremotemodel->getURIFromLabel($exchange['@attributes']['unit']);
			}
		}
		if (isset($exchange['inputGroup']) == true) {
			$info['direction'] = "input";
		} elseif (isset($exchange['outputGroup']) == true) {
			$info['direction'] = "output";
			if ($exchange['outputGroup'] == 0) {
				//$this->setReferenceProduct();
			}
		}
		return $info;
	}

	private function ecospold1Organization($organization) {
		$info = array();
		$search_info = array();
		if (isset($organization['companyCode']) == true) {
			//$search_info['companyCode'] = $organization['companyCode'];
			$info['companyCode'] = $organization['companyCode'];
			//$results = $this->arcmodel->searchOrganization($search_info);
		}
		/*
		if ($results != false) {
			$info['possibleIdentities'] = $results;
		}
		*/
		if (isset($organization['companyCode']) == true) {
			$info['address'] = $organization['address'];
		}
		return $info;
	}

	private function ecospold1Person($person) {
		//First, figure out this person's name & email
		// Email really makes a better key than name (or a combination of both) so we will look for email first
		
		$info = array();
		$search_info = array();
		if (isset($person['email']) == true) {
			$info['email'] = $person['email'];
			$search_info = array('email'=>$person['email']);
			$results = $this->arcmodel->searchFoaf($search_info);
		}
		if ($results != false) {
			$info['possibleIdentities'] = $results;
		}
		$search_info = array();
		if (strpos(",", $person['name']) === true) {
			$name = explode(",", $person['name']);
			$search_info['firstName'] = trim($name[1]);
			$search_info['lastName'] = trim($name[0]);
			$info['firstName'] = trim($name[1]);
			$info['lastName'] = trim($name[0]);			
		} elseif (strpos(" ", $person['name']) === true) {
			$name = explode(" ", $person['name']);
			$search_info['firstName'] = trim($name[0]);
			$search_info['lastName'] = trim($name[1]);	
			$info['firstName'] = trim($name[1]);
			$info['lastName'] = trim($name[0]);		
		}
		foreach ($this->lca_datasets as $key=>$dataset) {
			foreach ($dataset['people'] as $_key=>$person) {
				if ($info['firstName'] == $person['firstName'] && $info['lastName'] == $person['lastName'] && $info['email'] == $person['email']) {
					$info['reference'] = $key. "-".$_key;
				}
			}
		}
		if (isset($info['reference']) === false) {
			$results = $this->arcmodel->searchFoaf($search_info);
			if ($results != false) {
				$info['possibleIdentities'] = array_merge($info['possibleIdentities'], $results);
			}
		return $info;
		} else {
			return array('reference' => $info['reference']);
		}
	}
}