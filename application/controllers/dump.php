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

class Dump extends FT_Controller {
	public function __construct() {
		parent::__construct();
		$this->check_if_logged_in();
		$this->load->model(Array('lcamodel', 'geographymodel', 'bibliographymodel','peoplemodel','commentsmodel','ecomodel','opencycmodel'));	
		$this->load->library(Array('form_extended', 'xml'));
		$this->load->helper(Array('nameformat_helper','linkeddata_helper'));
		$obj =& get_instance();    
        $obj->load->library(array('xml'));
        $this->ci =& $obj;
	}
	
	public function okala() {	
		$handle = fopen('application/data/datasets/okala.csv', 'r');
		$bib_uri = toURI('bibliography',"Okala");
		$triples = array(
			array(
				's' => $bib_uri,
				'p' => "rdfs:type",
				'o' => "bibo:Document"
			),
			array(
				's' => $bib_uri,
				'p' => "dc:title",
				'o' => "Okala Design Guide 2010"
			),			
			array(
				's' => $bib_uri,
				'p' => "dc:date",
				'o' => "2010"
			)
		);
		$this->lcamodel->addTriples($triples);
		if ($handle) {
			while (!feof($handle)) {
		        $line = fgets($handle);
		        $stuff = explode(",",$line);
					$main_url = toUri('lca',$stuff[0]);
					$ia_node = toBNode("impactAssessment");
					$iamcd_node = toBNode("iamcd");
					$icir_node = toBNode("icir");
					$process_node = toBNode("process");
					$exchange_node = toBNode("exchange");
					$effect_node = toBNode("effect");
					$quantity_node = toBNode("quantity");
					$triples = array(
						array(
							's' => $main_url,
							'p' => "rdfs:type",
							'o' => "eco:FootprintModel"
						),
						array(
							's' => $main_url,
							'p' => "rdfs:label",
							'o' => trim($stuff[0])
						),	
						array(
							's' => $main_url,
							'p' => "eco:hasDataSource",
							'o' => $bib_uri
						),
						array(
							's' => $main_url,
							'p' => 'dcterms:creator',
							'o' => 'http://db.opensustainability.info/rdfspace/person/bianca-sayan5191827'
						),
						array(
							's' => $main_url,
							'p' => 'dcterms:created',
							'o' => date('h:i:s-m:d:Y')
						),
						array(
							's' => $main_url,
							'p' => "eco:models",
							'o' => $process_node
						),
						array(
							's' => $process_node,
							'p' => "rdfs:type",
							'o' => "eco:Process"
						),
						array(
							's' => $process_node,
							'p' => "rdfs:label",
							'o' => trim($stuff[0])
						),			
						array(
							's' => $ia_node,
							'p' => "eco:computedFrom",
							'o' => $main_url
						),
						array(
							's' => $ia_node,
							'p' => "eco:assessmentOf",
							'o' => $process_node
						),
						array(
							's' => $ia_node,
							'p' => "rdfs:type",
							'o' => "eco:ImpactAssessment"
						),	
						array(
							's' => $ia_node,
							'p' => "eco:hasImpactCategoryIndicatorResult",
							'o' => $icir_node
						),
						array(
							's' => $icir_node,
							'p' => "eco:hasImpactAssessmentMethodCategoryDescription",
							'o' => $iamcd_node
						),					
						array(
							's' => $iamcd_node,
							'p' => "eco:hasImpactCategory",
							'o' => "impact:climateChange"
						),
						array(
							's' => $iamcd_node,
							'p' => "eco:hasImpactCategoryIndicator",
							'o' => "Carbon Dioxide Equivalent"
						),
						array(
							's' => $icir_node,
							'p' => "eco:hasQuantity",
							'o' => $iamcd_node
						),					
						array(
							's' => $iamcd_node,
							'p' => "eco:hasUnitOfMeasure",
							'o' => "http://data.nasa.gov/qudt/owl/unit#PoundMass"
						),
						array(
							's' => $iamcd_node,
							'p' => "eco:hasMagnitude",
							'o' => trim($stuff[1])
						)
					);
					if ($stuff[4] != 'Transportation Process') {
						$triples[] = array(
							's' => $main_url,
							'p' => "eco:hasReferenceExchange",
							'o' => $exchange_node
						);
						$triples[] = array(
							's' => $exchange_node,
							'p' => "rdfs:type",
							'o' => "eco:Exchange"
						);
						$triples[] = array(
							's' => $exchange_node,
							'p' => "eco:hasEffect",
							'o' => $effect_node
						);
						$triples[] = array(
							's' => $effect_node,
							'p' => "rdfs:type",
							'o' => "eco:".$stuff[7]
						);
						$triples[] = array(
							's' => $exchange_node,
							'p' => "eco:hasQuantity",
							'o' => $quantity_node
						);					
						$triples[] = array(
							's' => $quantity_node,
							'p' => "eco:hasUnitOfMeasure",
							'o' => trim($stuff[2])
						);
						$triples[] = array(
							's' => $quantity_node,
							'p' => "eco:hasMagnitude",
							'o' => "1"
						);			
					}
					if (trim($stuff[3]) != "") {
						$triples[] = array(
							's' => $main_url,
							'p' => "rdfs:description",
							'o' => trim($stuff[3])
						);
						$triples[] = array(
							's' => $process_node,
							'p' => "rdfs:description",
							'o' => trim($stuff[3])
						);
					}
					if (isset($stuff[5]) == true && $stuff[4] != 'Product') {
						foreach (explode(";", $stuff[5]) as $category) {
							$triples[] = array(
								's' => $process_node,
								'p' => "eco:hasCategory",
								'o' => trim($category)
							);				
						}
					}
					if ($stuff[4] != 'Transportation Process') {
						$t_node = toBNode("transferable");
						$triples[] = array(
							's' => $effect_node,
							'p' => "eco:hasTransferable",
							'o' => $t_node
						);
						$triples[] = array(
							's' => $main_url,
							'p' => "eco:models",
							'o' => $t_node
						);
						$triples[] = array(
							's' => $t_node,
							'p' => "rdfs:type",
							'o' => "eco:".$stuff[4]
						);
						$triples[] = array(
							's' => $t_node,
							'p' => "rdfs:label",
							'o' => trim($stuff[8])
						);	
						if (trim($stuff[3]) != "") {
							$triples[] = array(
								's' => $t_node,
								'p' => "rdfs:description",
								'o' => trim($stuff[3])
							);
						}
						if (isset($stuff[5]) == true) {
							foreach (explode(";", $stuff[5]) as $category) {
								$triples[] = array(
									's' => $t_node,
									'p' => "eco:hasCategory",
									'o' => trim($category)
								);				
							}
						}
					}
					   if ($stuff[4] == 'Transportation Process') {
							$fum_node = toBNode("functionalUnitOfMeasure");
							$triples[] = array(
								's' => $main_url,
								'p' => "eco:hasFunctionalUnitofMeasure",
								'o' => $fum_node
							);
							$triples[] = array(
								's' => $fum_node,
								'p' => "eco:hasFunction",
								'o' => trim($stuff[8])
							);
							$triples[] = array(
								's' => $fum_node,
								'p' => "eco:hasUnitQuantity",
								'o' => trim($stuff[2])
							);
						}
					var_dump($triples);
					$this->lcamodel->addTriples($triples);
				}
			}
			fclose($handle);
	}
}