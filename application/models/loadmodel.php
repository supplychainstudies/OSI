<?php
include_once('arcmodel.php');
/**
 * This model uses the Arc2 library to insert, edit, and retrieve rdf data from the arc store 
 * 
 * @package opensustainability
 * @subpackage models
 */

class Loadmodel extends ArcModel{
	
	/**
	 * @ignore
	 */
	function Loadmodel(){
		parent::arcmodel();
		

	}
	
	public function fixgeo() {
		$this->arc_config['store_name'] = 'remote_os';
		$this->arc_config['db_name'] = 'geonames';
		
		
		/*

		*/
		$cached = array(
			'http://www.qudt.org/qudt/owl/1.0.0/nist-constants.owl',				
			'http://www.qudt.org/qudt/owl/1.0.0/qudt-spin.owl',
			'http://www.qudt.org/qudt/owl/1.0.0/qudt-dbpedia.owl',
			'http://www.qudt.org/qudt/owl/1.0.0/qudt.owl',
			'http://www.qudt.org/qudt/owl/1.0.0/unit.owl',
			'http://www.qudt.org/qudt/owl/1.0.0/quantity.owl',
			'http://www.qudt.org/qudt/owl/1.0.0/dimension.owl',
			'http://www.qudt.org/qudt/owl/1.0.0/nist-constants.owl',
			'ecoalloc' => 'http://ontology.earthster.org/eco/alloc.n3',
			'ecoattr' => 'http://ontology.earthster.org/eco/attribute.n3',
			'ecob' => 'http://ontology.earthster.org/eco/bridges.n3',
			'cml2001' => 'http://ontology.earthster.org/eco/cml2001.ttl',
			'eco' => 'http://ontology.earthster.org/eco/core.rdf',
			'ecodl' =>'http://ontology.earthster.org/eco/ecodl.n3',
			'ecofull' =>'http://ontology.earthster.org/eco/ecofull.n3',
			'ecoinvent' =>'http://ontology.earthster.org/eco/ecoinvent.ttl',
			'ecosp' =>'http://ontology.earthster.org/eco/ecospold.n3',
			'fasc' =>'http://ontology.earthster.org/eco/fasc.n3',
			'ecofa' =>'http://ontology.earthster.org/eco/fullAxioms.n3',
			'ecoilcd' =>'http://ontology.earthster.org/eco/ilcd.ttl',
			'impact' =>'http://ontology.earthster.org/eco/impact.n3',
			'impact2002' =>'http://ontology.earthster.org/eco/impact2002Plus.n3',
			'ecoud' =>'http://ontology.earthster.org/eco/uncertaintyDistribution.n3',
			'qudtu' => 'http://data.nasa.gov/qudt/owl/unit#',
			'qudtq' => 'http://data.nasa.gov/qudt/owl/quantity#',
			'qudtd' => 'http://data.nasa.gov/qudt/owl/dimension#',
			'nist' => 'http://physics.nist.gov/cuu/',
			'm' => 'http://www.qudt.org/qudt/owl/1.0.0/nist-constants.owl',			
			'n' => 'http://www.qudt.org/qudt/owl/1.0.0/qudt-spin.owl',
			'o' => 'http://www.qudt.org/qudt/owl/1.0.0/qudt-dbpedia.owl',
			'ecoalloc' => 'http://ontology.earthster.org/eco/alloc#',
			'ecoattr' => 'http://ontology.earthster.org/eco/attribute#',
			'ecob' => 'http://ontology.earthster.org/eco/bridges#',
			'cml2001' => 'http://ontology.earthster.org/eco/cml2001#',
			'eco' => 'http://ontology.earthster.org/eco/core#',
			'ecodl' =>'http://ontology.earthster.org/eco/ecodl#',
			'ecofull' =>'http://ontology.earthster.org/eco/ecofull#',
			'ecoinvent' =>'http://ontology.earthster.org/eco/ecoinvent#',
			'ecosp' =>'http://ontology.earthster.org/eco/ecospold#',
			'fasc' =>'http://ontology.earthster.org/eco/fasc#',
			'ecofa' =>'http://ontology.earthster.org/eco/fullAxioms#',
			'ecoilcd' =>'http://ontology.earthster.org/eco/ilcd#',
			'impact' =>'http://ontology.earthster.org/eco/impact#',
			'impact2002' =>'http://ontology.earthster.org/eco/impact2002Plus#',
			'ecoud' =>'http://ontology.earthster.org/eco/uncertaintyDistribution#',
			'ecounit' =>'http://ontology.earthster.org/eco/unit#',			
		);
		//foreach($cached as $c) {
			//$q = "DELETE FROM <" . $c . ">";
		//	$results = $this->executeQuery($q);
		//}
		
		$q = "DELETE { " . 
			"?s ?p ?o . " . 
			"FILTER regex(?o, 'nasa', 'i') " . 
			"}";
		$results = $this->executeQuery($q);
		
	}
	
	public function dumpqudt() {
		var_dump($this->arc_config);
		$this->arc_config['store_name'] = 'qudt';
		$cached = array(
			array(
				'http://www.qudt.org/qudt/owl/1.0.0/nist-constants.owl'				
			),
			array(
				'http://www.qudt.org/qudt/owl/1.0.0/qudt-spin.owl'
			),
			array(
				'http://www.qudt.org/qudt/owl/1.0.0/qudt-dbpedia.owl'
			),
			array(
				'http://www.qudt.org/qudt/owl/1.0.0/qudt.owl',
				'http://data.nasa.gov/qudt/owl/qudt#'
			),
			array(
				'http://www.qudt.org/qudt/owl/1.0.0/unit.owl',
				'http://data.nasa.gov/qudt/owl/unit#'
			),
			array(
				'http://www.qudt.org/qudt/owl/1.0.0/quantity.owl',
				'http://data.nasa.gov/qudt/owl/quantity#'
			),
			array(
				'http://www.qudt.org/qudt/owl/1.0.0/dimension.owl',
				'http://data.nasa.gov/qudt/owl/dimension#'
			),
			array(
				'http://www.qudt.org/qudt/owl/1.0.0/nist-constants.owl',
				'http://physics.nist.gov/cuu/'
			),
		);
		
		foreach($cached as $onto) {
			if (count($onto) == 1) {
				$q = "LOAD <" . $onto[0] . "> INTO <" . $onto[0] . ">";
			} elseif (count($onto) == 2) {
				$q = "LOAD <" . $onto[0] . "> INTO <" . $onto[1] . ">";
			}
			$results = $this->executeQuery($q);	
		}
	}
	
	
	
	
	public function dumpEco() {
		$this->arc_config['store_name'] = 'eco';
		$cached = array(
			array(
				'http://osi/assets/schemas/Earthster/alloc.n3',
				'http://ontology.earthster.org/eco/alloc#'
			),				
			array(
				'http://osi/assets/schemas/Earthster/attribute.n3',
				'http://ontology.earthster.org/eco/attribute#'
			),
			array(
				'http://osi/assets/schemas/Earthster/biboBridge.n3',
				'http://ontology.earthster.org/eco/biboBridge#'
			),
			array(
				'http://osi/assets/schemas/Earthster/bridges.n3',
				'http://ontology.earthster.org/eco/bridges#'
			),
			array(
				'http://osi/assets/schemas/Earthster/cml2001.ttl',
				'http://ontology.earthster.org/eco/cml2001#'
			),
			array(
				'http://osi/assets/schemas/Earthster/core.n3',
				'http://ontology.earthster.org/eco/core#'
			),
			array(
				'http://osi/assets/schemas/Earthster/ecodl.n3',
				'http://ontology.earthster.org/eco/ecodl#'
			),
			array(
				'http://osi/assets/schemas/Earthster/ecofull.n3',
				'http://ontology.earthster.org/eco/ecofull#'
			),				
			array(
				'http://osi/assets/schemas/Earthster/ecoinvent.ttl',
				'http://ontology.earthster.org/eco/ecoinvent#'
			),
			array(
				'http://osi/assets/schemas/Earthster/ecospold.n3',
				'http://ontology.earthster.org/eco/ecospold#'
			),
			array(
				'http://osi/assets/schemas/Earthster/fasc.n3',
				'http://ontology.earthster.org/eco/fasc#'
			),
			array(
				'http://osi/assets/schemas/Earthster/foafBridge.n3',
				'http://ontology.earthster.org/eco/foafBridge#'
			),
			array(
				'http://osi/assets/schemas/Earthster/fullAxioms.n3',
				'http://ontology.earthster.org/eco/fullAxioms#'
			),
			array(
				'http://osi/assets/schemas/Earthster/goodRelationsBridge.n3',
				'http://ontology.earthster.org/eco/goodRelationsBridge#'
			),
			array(
				'http://osi/assets/schemas/Earthster/ilcd.ttl',
				'http://ontology.earthster.org/eco/ilcd#'
			),
			array(
				'http://osi/assets/schemas/Earthster/impact.n3',
				'http://ontology.earthster.org/eco/impact#'
			),
			array(
				'http://osi/assets/schemas/Earthster/impact2002Plus.n3',
				'http://ontology.earthster.org/eco/impact2002Plus#'
			),
			array(
				'http://osi/assets/schemas/Earthster/sumoBridge.n3',
				'http://ontology.earthster.org/eco/sumoBridge#'
			),
			array(
				'http://osi/assets/schemas/Earthster/timeBridge.n3',
				'http://ontology.earthster.org/eco/timeBridge#'
			),
			array(
				'http://osi/assets/schemas/Earthster/uncertaintyDistribution.n3',
				'http://ontology.earthster.org/eco/uncertaintyDistribution#'
			),
			array(
				'http://osi/assets/schemas/Earthster/unit.ttl',
				'http://ontology.earthster.org/eco/unit#'
			),
		);
		foreach($cached as $onto) {
			if (count($onto) == 1) {
				$q = "LOAD <" . $onto[0] . "> INTO <" . $onto[0] . ">";
			} elseif (count($onto) == 2) {
				$q = "LOAD <" . $onto[0] . "> INTO <" . $onto[1] . ">";
			}
			$results = $this->executeQuery($q);	
		}
	}
	
	
}