<?php
class Bibliographymodel extends FT_Model{
    function Bibliographymodel(){
        parent::__construct();
    }

	public function convertBibliography($dataset){
		$converted_dataset = array();
		foreach ($dataset as $key=>$record) {
			if (isset($record[$this->arc_config['ns']['dc']."title"]) == true) {
				foreach($record[$this->arc_config['ns']['dc']."title"] as $title) {
					$converted_dataset[$key]['title'] = $title;
				}
			} else {
				$converted_dataset[$key]['title'] = "";
			}
			if (isset($record[$this->arc_config['ns']['bibo']."authorList"]) == true) {
				$person_array = array();
				foreach($record[$this->arc_config['ns']['bibo']."authorList"] as $author_uri) {
					$person = $this->getTriples($author_uri);
					foreach ($person[$this->arc_config['ns']['foaf'].'firstName'] as $firstName) {
						$person_array['firstName'] = $firstName;
					} 
					foreach ($person[$this->arc_config['ns']['foaf'].'lastName'] as $lastName) {
						$person_array['lastName'] = $lastName;
					}
					$converted_dataset[$key]['authors'][] = $person_array;						
				}
				
			} elseif (isset($record[$this->arc_config['ns']['dcterms']."creator"]) == true)  {
				foreach($record[$this->arc_config['ns']['dcterms']."creator"] as $author_uri) {
					$person = $this->getTriples($author_uri);
					foreach ($person[$this->arc_config['ns']['foaf'].'firstName'] as $firstName) {
						$person_array['firstName'] = $firstName;
					} 
					foreach ($person[$this->arc_config['ns']['foaf'].'lastName'] as $lastName) {
						$person_array['lastName'] = $lastName;
					}
					$converted_dataset[$key]['authors'][] = $person_array;						
				}
			}
			if (isset($record[$this->arc_config['ns']['bibo']."uri"]) == true) {
				foreach($record[$this->arc_config['ns']['bibo']."uri"] as $uri) {
					$converted_dataset[$key]['uri'] = $uri;
				}
			} else {
				$converted_dataset[$key]['uri'] = "";
			} 
			if (isset($record[$this->arc_config['ns']['dc']."date"]) == true) {
				foreach($record[$this->arc_config['ns']['dc']."date"] as $date) {
					$converted_dataset[$key]['date'] = $date;
				}
			} else {
				$converted_dataset[$key]['date'] = "";
			}
			/*
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
			" <".$URI."> eco:hasDataSource ?bibouri . " .			
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
