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
 
    }
 
    public function everybody() {
         
        $q = "select ?firstName ?lastName where { " .   
            "?uri '".$this->arc_config['ns']['foaf']."firstName' ?firstName . " . 
            "?uri '".$this->arc_config['ns']['foaf']."lastName' ?lastName . " . 
            "}";
        var_dump($q);
        $records = $this->executeQuery($q);  
        var_dump($records);
        if (count($records) > 0) {
            return $records;
        } else {
            return false;
        }
    }
     
    public function searchPeople($info) {
        $filters = "";
        $q = "";
        if (isset($info['uri']) == true) {
            $uri = "<" . $info['uri'] . ">";      
        } else {
            $uri = "?uri";
        }
        if (isset($info['email']) == true) {
            $q .= $uri . " foaf:mbox_sha1sum '" . sha1($info['email']) . "' . ";       
        } else {
            $q .= $uri . " foaf:mbox_sha1sum ?email . "; 
        }        
        if (isset($info['firstName']) == true) {    
            $q .= "FILTER regex(?firstName, '" . $info['firstName'] . "', 'i')  ";  
        }
        if (isset($info['lastName']) == true) { 
            $q .= "FILTER regex(?lastName, '" . $info['lastName'] . "', 'i')  ";    
        } 
         
         
        $q = "select * where { " . 
            $uri . " rdfs:type foaf:Person . " .    
            $uri . " foaf:firstName ?firstName . " . 
            $uri . " foaf:lastName ?lastName . " . 
            $q . 
            "}";
        $records = $this->executeQuery($q);  
        if (count($records) > 0) {
            return $records;
        } else {
            return false;
        }
    }
}