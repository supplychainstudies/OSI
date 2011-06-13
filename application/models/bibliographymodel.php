<?php
class Bibliographymodel extends FT_Model{
    function Bibliographymodel(){
        parent::__construct();
		$this->load->model(Array('peoplemodel'));
$this->arc_config['store_name'] = "fast_footprinted";
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
					$person = $this->peoplemodel->searchPeople(array('uri'=>$author_uri));
					if (count($person) > 0) {
						$person_array['firstName'] = $person[0]['firstName'];
						$person_array['lastName'] = $person[0]['lastName'];
					}
					$converted_dataset[$key]['authors'][] = $person_array;						
				}
				
			} elseif (isset($record[$this->arc_config['ns']['dcterms']."creator"]) == true)  {
				foreach($record[$this->arc_config['ns']['dcterms']."creator"] as $author_uri) {
					$person = $this->getTriples(null,$author_uri);
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
		$q = "select ?bibouri from <".$URI."> where { " . 
			" ?s eco:hasDataSource ?bibouri . " .			
			"}";				
		$records = $this->executeQuery($q);
		$full_record = array();		
		foreach ($records as $record) {	
			$link = array('link' => $record['bibouri']);
			$full_record[$record['bibouri']] = array_merge($link, $this->getTriples(null,$record['bibouri']));			
		}
		return $full_record;
	}

	public function getAll() {
		$q = "SELECT DISTINCT ?uri WHERE {GRAPH ?uri {?s rdfs:type bibo:Document . }}";				
		$records = $this->executeQuery($q);
		foreach ($records as $record) {
			$q = "select * from <".$record['uri']."> where { " . 
				" ?s ?p ?o . " .			
				"}";			
			$r = $this->executeQuery($q);
			var_dump($r);
		}
		var_dump($records);
	}
	public function getAllBibliographies() {
		$q = "select ?uri where { " . 
			" ?uri rdfs:type bibo:Document . " .			
			"}";				
		$records = $this->executeQuery($q);
		$q = "select ?uri where { " . 
			" ?uri rdfs:type bibo:Journal . " .			
			"}";				
		$records = array_merge($records,$this->executeQuery($q));		
		$q = "select ?uri where { " . 
			" ?uri rdfs:type bibo:Conference . " .			
			"}";				
		$records = array_merge($records,$this->executeQuery($q));
		$q = "select ?uri where { " . 
			" ?uri rdfs:type bibo:Book . " .			
			"}";				
		$records = array_merge($records,$this->executeQuery($q));
		$q = "select ?uri where { " . 
			" ?uri rdfs:type bibo:Website . " .			
			"}";				
		$records = array_merge($records,$this->executeQuery($q));
		$q = "select ?uri where { " . 
			" ?uri rdfs:type bibo:Organization . " .			
			"}";				
		$records = array_merge($records,$this->executeQuery($q));
		$q = "select ?uri where { " . 
			" ?uri rdfs:type bibo:Webpage . " .			
			"}";				
		$records = array_merge($records,$this->executeQuery($q));
		return $records;
	}

} // End Class
