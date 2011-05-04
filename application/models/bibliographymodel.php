<?php
/**
 * This model uses the Arc2 library to insert, edit, and retrieve rdf data from the arc store 
 * 
 * @package opensustainability
 * @subpackage models
 */
 
class Bibliographymodel extends FT_Model{
     
    /**
     * @ignore
     */
    function Bibliographymodel(){
        parent::__construct();
 
    }

	public function convertBibliography($dataset){
		$bibo_prefix = "http://purl.org/ontology/bibo/";
		$foaf_prefix = "http://xmlns.com/foaf/0.1/";
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
					$person = $this->getTriples($author_uri);
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

} // End Class
