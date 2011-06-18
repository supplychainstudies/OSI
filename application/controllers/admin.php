<?php
/**
 * Controller for administration things
 * 
 * @version 0.8.0
 * @author info@footprinted.org
 * @package opensustainability
 * @subpackage controllers
 * @uses 
 */

class Admin extends FT_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model(Array('lcamodel', 'geographymodel', 'bibliographymodel','peoplemodel','commentsmodel','ecomodel','opencycmodel'));	
		$this->load->library(Array('form_extended', 'xml'));
		$this->load->helper(Array('nameformat_helper','linkeddata_helper'));
		$obj =& get_instance();    
        $obj->load->library(array('xml'));
        $this->ci =& $obj;
		$this->check_if_admin();
	}
	
	public function testGraph() {
		$uris = $this->lcamodel->getRecords();
		$q = "SELECT * { GRAPH <http://footprinted.org> { ?x ?y ?z } }";
		var_dump($q);
		$results= $this->lcamodel->executeQuery($q);
		var_dump($results);
	}
	
	// Working function to normalize
	/* public function normalize() {
		$uris = $this->lcamodel->getRecords();
		$parts = array();
		$factors = array (
				'http://data.nasa.gov/qudt/owl/unit#Gram' => '0.001',
				'http://data.nasa.gov/qudt/owl/unit#Kilogram' => '1',
				'http://data.nasa.gov/qudt/owl/unit#TableSpoon' => '0.0147867648',
				'http://data.nasa.gov/qudt/owl/unit#Liter' => '1',
				'http://data.nasa.gov/qudt/owl/unit#Ounce'=>'0.0625',
				'http://data.nasa.gov/qudt/owl/unit#Pound'=>'1',
			);
		//foreach ($uris as $uri) {
			
			$parts['impactAssessments'] = $this->lcamodel->getImpactAssessments($uris[200]['uri']);
			$parts['exchanges'] = $this->lcamodel->getExchanges($uris[200]['uri']);	
			$parts['quantitativeReference'] = $this->lcamodel->getQR($uris[200]['uri']);
			// Create the divisor
			var_dump($parts);
			$divisor = $parts['quantitativeReference'][0]['magnitude']/1;	
			if ($parts['quantitativeReference'][0]['unit'] == "http://data.nasa.gov/qudt/owl/unit#Gram") {
				$new_qr_unit = "http://data.nasa.gov/qudt/owl/unit#Kilogram";
			}
			if ($parts['quantitativeReference'][0]['unit'] == "http://data.nasa.gov/qudt/owl/unit#TableSpoon") {
				$new_qr_unit = "http://data.nasa.gov/qudt/owl/unit#Liter";
			}
			if ($parts['quantitativeReference'][0]['unit'] == "http://data.nasa.gov/qudt/owl/unit#Ounce") {
				$new_qr_unit = "http://data.nasa.gov/qudt/owl/unit#Pound";
			}			
			
			foreach ($parts['exchanges'] as $key=>$exchange) {
				if ($exchange["http://www.w3.org/2000/01/rdf-schema#type"][$exchange['link']] == "http://ontology.earthster.org/eco/core#Exchange") {
					// Delete the triple
					$q = "DELETE DATA FROM <http://footprinted.org>" . 
					"{ <".$exchange['link']."> rdfs:type eco:Exchange }";
					var_dump($q);
					$query = "insert into <http://footprinted.org> { " . 
						"<".$exchange['link']."> rdfs:type eco:UnallocatedExchange . }";					
				}				
				if ($exchange["http://www.w3.org/2000/01/rdf-schema#type"][$exchange['link']] == "http://ontology.earthster.org/eco/core#Exchange") {
					// Delete the triple
					$q = "DELETE DATA FROM <http://footprinted.org>" . 
					"{ <".$exchange['link']."> rdfs:type eco:Exchange }";
					var_dump($q);
					$query = "insert into <http://footprinted.org> { " . 
						"<".$exchange['link']."> rdfs:type eco:UnallocatedExchange . }";					
				}
				foreach ($exchange["http://ontology.earthster.org/eco/core#hasQuantity"] as $_key=>$q) {
					$new_magnitude = $q["http://ontology.earthster.org/eco/core#hasMagnitude"][$_key]*$divisor;
					$new_unit = $q["http://ontology.earthster.org/eco/core#hasUnitOfMeasure"][$_key];
					if ($q["http://ontology.earthster.org/eco/core#hasUnitOfMeasure"][$_key] == "http://data.nasa.gov/qudt/owl/unit#Gram") {
						$new_unit = "http://data.nasa.gov/qudt/owl/unit#Kilogram";
						$new_magnitude = $new_magnitude*$factors[$q["http://ontology.earthster.org/eco/core#hasUnitOfMeasure"][$_key]];
					}
					if ($q["http://ontology.earthster.org/eco/core#hasUnitOfMeasure"][$_key] == "http://data.nasa.gov/qudt/owl/unit#TableSpoon") {
						$new_unit = "http://data.nasa.gov/qudt/owl/unit#Liter";
						$new_magnitude = $new_magnitude*$factors[$q["http://ontology.earthster.org/eco/core#hasUnitOfMeasure"][$_key]];
					}
					if ($q["http://ontology.earthster.org/eco/core#hasUnitOfMeasure"][$_key] == "http://data.nasa.gov/qudt/owl/unit#Ounce") {
						$new_unit = "http://data.nasa.gov/qudt/owl/unit#Pound";
						$new_magnitude = $new_magnitude*$factors[$q["http://ontology.earthster.org/eco/core#hasUnitOfMeasure"][$_key]];
					}
					$query = "insert into <http://footprinted.org> { " . 
						"<".$_key."> eco:hasUnitOfMeasure <" . $new_unit . "> . " . 
						"<".$_key."> eco:hasMagnitude '" . $new_magnitude . "' . ";
					var_dump($query);
					
				}
			} 
			foreach ($parts['impactAssessments'] as $key=>$a) {
				foreach ($a["http://ontology.earthster.org/eco/core#hasQuantity"] as $_key=>$q) {
					$new_magnitude = $q["http://ontology.earthster.org/eco/core#hasMagnitude"][$_key]*$divisor;
					$new_unit = $q["http://ontology.earthster.org/eco/core#hasUnitOfMeasure"][$_key];
					if ($q["http://ontology.earthster.org/eco/core#hasUnitOfMeasure"][$_key] == "http://data.nasa.gov/qudt/owl/unit#Gram") {
						$new_unit = "http://data.nasa.gov/qudt/owl/unit#Kilogram";
						$new_magnitude = $new_magnitude*$factors[$q["http://ontology.earthster.org/eco/core#hasUnitOfMeasure"][$_key]];
					}
					if ($q["http://ontology.earthster.org/eco/core#hasUnitOfMeasure"][$_key] == "http://data.nasa.gov/qudt/owl/unit#TableSpoon") {
						$new_unit = "http://data.nasa.gov/qudt/owl/unit#Liter";
						$new_magnitude = $new_magnitude*$factors[$q["http://ontology.earthster.org/eco/core#hasUnitOfMeasure"][$_key]];
					}
					if ($q["http://ontology.earthster.org/eco/core#hasUnitOfMeasure"][$_key] == "http://data.nasa.gov/qudt/owl/unit#Ounce") {
						$new_unit = "http://data.nasa.gov/qudt/owl/unit#Pound";
						$new_magnitude = $new_magnitude*$factors[$q["http://ontology.earthster.org/eco/core#hasUnitOfMeasure"][$_key]];
					}
					$query = "insert into <http://footprinted.org> { " . 
						"<".$_key."> eco:hasUnitOfMeasure <" . $new_unit . "> . " . 
						"<".$_key."> eco:hasMagnitude '" . $new_magnitude . "' . ";
					var_dump($query);
					
				}
			}
			
		//}
	}*/
	
	public function convertToNamedGraphs() {
		$uris = $this->lcamodel->getRecords();
		//foreach ($uris as $uri) {
			$triples = $this->lcamodel->getArcTriples($uris[0]['uri']);
		//}
		foreach ($triples as &$triple) {
			foreach ($triple as &$t) {
				if ($t == $uris[0]['uri']) {
					$t = "_:".str_replace("http://footprinted.org/rdfspace/lca/","", $t);
				} 
			}
			
		}
		$graph_name = str_replace("http://footprinted.org/rdfspace/lca/","", $uris[0]['uri']).".rdf";
		var_dump($graph_name);
		var_dump($triples);
		//$this->testmodel->addT($graph_name, $triples);
	}


	public function assignCategory($index = 1) {
		// find URI of something that doesnt have a category or sameas
		$uris = $this->lcamodel->getRecords();
		$record = array(
			'uri'=> $uris[$index]['uri'],
			'label'=>$uris[$index]['label']
		);
		$sameAs = $this->lcamodel->getSameAs($uris[$index]['uri']);
		$categories = $this->lcamodel->getCategories($uris[$index]['uri']);
		$sameAsSuggestions = $this->lcamodel->getOpenCycSuggestions($uris[$index]['uri']);
		$categorySuggestions = array(
			array(
				"uri" => "Mx4rvUCoPtoTQdaZVdw2OtjsAg",
				"label" => "chemical compound"
			),
			array(
				"uri"=>"Mx4rv-6HepwpEbGdrcN5Y29ycA",
				"label"=> "transportation"
				),
			array(
				"uri"=>"Mx4rvVibU5wpEbGdrcN5Y29ycA",
				"label"=> "textile"
				),
			array(
				"uri"=>"Mx4rwQr0i5wpEbGdrcN5Y29ycA",
				"label"=> "building material"
			),
			array(
				"uri"=>"Mx4rvVi9A5wpEbGdrcN5Y29ycA",	
				"label"=> "food"
			),
			array(
				"uri"=>"Mx4rvViSe5wpEbGdrcN5Y29ycA",
				"label"=> "commodity"
			)		
		);
		// Print out the selector
		$this->data("record", $record);
		//$this->data("sameAs", $sameAs);
		$this->data("categories", $categories);		
		//$this->data("sameAsSuggestions", $sameAsSuggestions);
		$this->data("categorySuggestions", $categorySuggestions);
		$this->data("next", $index+1);		
		$this->display("Admin - Fix Categories and Same Concept Links", "adminCategories");
			
	}
	
	/* 
	Get all the records and one can select if add to featured or to remove
	*/
	public function adminFeatured(){
		// Querying the database for all records		
		$records = $this->lcamodel->getRecords();
		// Initializing array
		$set = array();
		// Add tooltips
		$this->tooltips = array();

		// Filling the arry with the records
		foreach ($records as $key => $record) {	
			// Go through each field
			foreach ($record as $_key => $field) {
				// if its a uri, get the label and store that instead 
				// rewrite this into a better function later
					$set[$key][$_key] = $field;
			}
		}
		// Send data to the view
		$this->data("set", $set);
		$this->display("Featured","adminFeatured_view");
	}
	
	public function adminCategoriesBatch(){
		// Querying the database for all records		
		$records = $this->lcamodel->getRecords();
		// Initializing array
		$set = array();
		// Add tooltips
		$this->tooltips = array();

		// Filling the arry with the records
		foreach ($records as $key => $record) {	
			// Go through each field
			foreach ($record as $_key => $field) {
				// if its a uri, get the label and store that instead 
				// rewrite this into a better function later
					$set[$key][$_key] = $field;
			}
		}
		// Send data to the view
		$this->data("set", $set);
		$this->display("Featured","adminCategories_view");
	}
	
	public function addAsFeatured(){
		parse_str($_SERVER['QUERY_STRING'],$_GET); 
		$URI = $_GET["URI"];
		$data = array(
		   'uri' => $URI,
		);

		$this->db->insert('featured', $data);
	}
	public function removeAsFeatured ($uri){
		
	}
}