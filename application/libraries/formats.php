<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/***
* RDF format library for CodeIgniter
*
*    author: Bianca Sayan
* copyright: (c) 2011
*   license: http://creativecommons.org/licenses/by-sa/2.5/
*      file: libraries/Formats.php
*	description: This file uses schemas (xsd, rdf, etc) to take a file in one format and return a file in another format
*/


class Formats {

    public function Formats () {
    /***
     * @constructor
     */
		$obj =& get_instance();    
 		$obj->load->library('simpleXML');
 		$this->ci =& $obj;
    } /*** END ***/

    private $ci;
	/*
	public $schema_folder = "http://osi/"."assets/schemas/";
    public $format_paths = array(
		'ISO14048' => $schema_folder.'ISO14048/ISO14048.rdf',
		'ILCD' => $schema_folder . '',
		'ECOSPOLD01' => $schema_folder . '',
		'ECOSPOLD02' => $schema_folder . '',
		'EARTHSTER' => $schema_folder . ''
	);
	*/


	
	private function openFormat($name) {
		$xmlfile= "http://osi/assets/schemas/ISO14048/ISO14048.rdf";
		$xmlRaw = file_get_contents($xmlfile);
		@$xdata = $this->ci->simplexml->xml_parse($xmlRaw);
		return $xdata;
	}
	
	public function find ($field, $xdata) {
		foreach ($xdata as $key => $_xdata) {
			if (is_array($_xdata) == false) {
				if ($field == $_xdata) {
					return "!";
				}
			} else if (is_array($_xdata) == true) {
				$follow = $this->find($field, $_xdata);
				if ($follow != "") {
					if (isset($_xdata["@attributes"]["rdf:ID"]) == true) {
						return $_xdata["@attributes"]["rdf:ID"] . "->" . $follow;
					} else {
						return $follow;
					}    	
				}
			}
		}
	}

    public function getPath ($field, $format) {
    /***
     * @public
     * looks for a field and returns the parent nodes
     */  
		$xdata = $this->openFormat($format);	
		return str_replace('->!', '', $this->find($field, $xdata));
    } /*** END get_fields ***/   



} // END Class
