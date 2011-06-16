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
        $vars = "";
        $q = "";
        if (isset($info['uri']) == true) {
            $uri = "<" . $info['uri'] . ">";      
        } else {
            $vars .= "?uri ";
            $uri = "?uri";
        }
        /*
        if (isset($info['email']) == true) {
            $q .= $uri . " '".$this->arc_config['ns']['foaf']."mbox_sha1sum' '" . $info['email'] . "' . ";       
        } else {
            $vars .= "?email ";
            $q .= $uri . " '".$this->arc_config['ns']['foaf']."mbox_sha1sum' ?email . "; 
        }
        */
         
        if (isset($info['firstName']) == true) {    
            $q .= "FILTER regex(?firstName, '" . $info['firstName'] . "', 'i')  ";  
        }
        if (isset($info['lastName']) == true) { 
            $q .= "FILTER regex(?lastName, '" . $info['lastName'] . "', 'i')  ";    
        } 
         
         
        $q = "select ".$vars."?firstName ?lastName where { " . 
            //$uri . " '".$this->arc_config['ns']['rdfs']."type' '".$this->arc_config['ns']['foaf']."Person' . " .    
            $uri . " '".$this->arc_config['ns']['foaf']."firstName' ?firstName . " . 
            $uri . " '".$this->arc_config['ns']['foaf']."lastName' ?lastName . " . 
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