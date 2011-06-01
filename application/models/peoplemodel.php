<?php
/**
 * This model uses the Arc2 library to insert, edit, and retrieve rdf data from the arc store 
 * 
 * @package opensustainability
 * @subpackage models
 */
 
class Peoplemodel extends FT_Model{
     
    /**
     * @ignore
     */
    function Peoplemodel(){
        parent::__construct();
 $this->arc_config['store_name'] = "lca";
    }
 
    public function everybody() {
         
        $q = "select ?firstName ?lastName where { " .   
            "?uri foaf:firstName ?firstName . " . 
            "?uri foaf:lastName ?lastName . " . 
            "}";
        $records = $this->executeQuery($q);  
        if (count($records) > 0) {
            return $records;
        } else {
            return false;
        }
    }
   
  
    public function searchPeople($info) {
        $q = "";
        if (isset($info['uri']) == true) {
 	        $q = "select ?email ?firstName ?lastName from <" . $info['uri'] . "> where { " .   
	            " ?s foaf:firstName ?firstName . " . 
	            " ?s foaf:lastName ?lastName . " . 
				"OPTIONAL { ?s  foaf:mbox_sha1sum  ?email } " . 
	            "}";               
        } else {

	        if (isset($info['email']) == true) {
	            $q .= " ?s foaf:mbox_sha1sum ?email . ";  
				$q .= "FILTER regex(?email, '" . $info['email'] . "', 'i')  ";     
	        } else {
	            $q .= "OPTIONAL { ?s  foaf:mbox_sha1sum  ?email } ";
	        }

	        if (isset($info['firstName']) == true) {    
	            $q .= "FILTER regex(?firstName, '" . $info['firstName'] . "', 'i')  ";  
	        }
	        if (isset($info['lastName']) == true) { 
	            $q .= "FILTER regex(?lastName, '" . $info['lastName'] . "', 'i')  ";    
	        } 


	        $q = "select * where { GRAPH ?uri {" . 
	            "?s rdfs:type foaf:Person . " .    
	            " ?s foaf:firstName ?firstName . " . 
	            " ?s foaf:lastName ?lastName . " . 
	            $q . 
	            "} }";
        }

        $records = $this->executeQuery($q);  
        if (count($records) > 0) {
            return $records;
        } else {
            return false;
        }
    }

    public function getAllPeople() {
         
        $q = "select ?uri where { " .   
            "?uri rdfs:type foaf:Person . " . 
            "}";
        $records = $this->executeQuery($q);  
        if (count($records) > 0) {
            return $records;
        } else {
            return false;
        }
    }
}