<?php
/**
 * Controller for dumping data straight into the db
 * THIS IS A JUNK CONTROLLER! DONT USE IT OR DO ANYTHING WITH IT!
 * 
 * @version 0.8.0
 * @author data_dump@opensustainability.info
 * @package opensustainability
 * @subpackage controllers
 * @uses 
 */


class Triplesdump extends SM_Controller {
	public function triplesdump() {
		parent::SM_Controller();
		$this->load->model(Array('arcmodel'));	
		$this->load->library(Array('form_extended','name_conversion'));
	}
	public $URI;
	public $data;
	public $post_data;
	/**
	 * Default controller, redirects appropriately.
	 */
	public function index() {
}

public function foodprint() {
	$handle = fopen('application/data/datasets/food_carbon_catalogue.csv', "r");
	// Columns: Process_name,class_name,reference_to_nomenclature,quantitative_reference_type,quantitative_reference_amount,quantitative_reference_name,quantitative_reference_unit,area_name,reference_to_nomenclature_,parameter_value,symbol,direction,group,receiving_environment,name_text,geographical_location
	$bibo_bnode = "http://db.opensustainability.info/rdfspace/bibliography/Life-Cycle-GHG-Emissions-of-Foods" . rand(9999999, 100000000) ."";  
	$person_bnode = "http://db.opensustainability.info/rdfspace/people/AlexLoijos" . rand(9999999, 100000000) .""; 
	$organization_bnode = "http://db.opensustainability.info/rdfspace/organizations/Foodprint" . rand(9999999, 100000000) .""; 
	
	$triples = array(
		array(
			"subject"=> $person_bnode,
			"predicate"=> "rdfs:type",
			"object"=> "foaf:Person"			
		),
		array(
			"subject"=> $person_bnode,
			"predicate"=> "foaf:firstName",
			"object"=> "Alex"			
		),
		array(
			"subject"=> $person_bnode,
			"predicate"=> "foaf:lastName",
			"object"=> "Loijos"			
		),
		array(
			"subject"=> $bibo_bnode,
			"predicate"=> "rdfs:type",
			"object"=> "bibo:Document"
			),
		array(
			"subject"=> $bibo_bnode,
			"predicate"=> "dc:title",
			"object"=> "Life Cycle GHG Emissions of Foods"
			),					
		array(
			"subject"=> $bibo_bnode,
			"predicate"=> "dc:date",
			"object"=> "2008-07-14"
			),
		array(
			"subject"=> $bibo_bnode,
			"predicate"=> "bibo:authorList",
			"object"=> $person_bnode
			),	
		array(
			"subject"=> $bibo_bnode,
			"predicate"=> "bibo:uri",
			"object"=> "http://www.socrata.com/dataset/Life-Cycle-GHG-Emissions-of-Foods/f66f-ewq4"
			),
		array(
			"subject"=> $bibo_bnode,
			"predicate"=> "bibo:uri",
			"object"=> "http://foodprint.awardspace.com/foodprintmethods.pdf"
			),
		array(
			"subject"=> $bibo_bnode,
			"predicate"=> "bibo:owner",
			"object"=> $organization_bnode
			),
		array(
			"subject"=> $organization_bnode,
			"predicate"=> "rdfs:type",
			"object"=> "foaf:Organization"
			),			
		array(
			"subject"=> $organization_bnode,
			"predicate"=> "foaf:name",
			"object"=> "Foodprint"
			),
	);
	
	@$this->arcmodel->addTriples($triples);		

	if ($handle) {
		while (!feof($handle)) {
	        $line = fgets($handle);
	        $line_array = explode(",",$line);
			$model_bnode = "http://db.opensustainability.info/rdfspace/lca/" . str_replace(" ", "", strtolower($line_array[0])) . rand(9999999, 100000000) ."";
			$process_bnode = "_:process" . rand(9999999, 100000000);
			$exchange_bnode = "_:exchange" . rand(9999999, 100000000);
			$effect_bnode = "_:effect" . rand(9999999, 100000000);
			$quantity_bnode = "_:quantity" . rand(9999999, 100000000);
			$exchange2_bnode = "_:exchange" . rand(9999999, 100000000);
			$effect2_bnode = "_:effect" . rand(9999999, 100000000);
			$quantity2_bnode = "_:quantity" . rand(9999999, 100000000);
			$product_bnode = "_:product" . rand(9999999, 100000000);
			$geography_bnode = "_:geography" . rand(9999999, 100000000);
			$icir_bnode =  "_:icir" . rand(9999999, 100000000);
			$iaq_bnode =  "_:quantity" . rand(9999999, 100000000);
			$ia_bnode =  "_:impactassessment" . rand(9999999, 100000000);
			$iamcd_bnode = "_:iamcd" . rand(9999999, 100000000);
			$eac_bnode = "_:eac" . rand(9999999, 100000000);
			// Columns: Process_name,class_name,reference_to_nomenclature,quantitative_reference_type,quantitative_reference_amount,quantitative_reference_name,quantitative_reference_unit,area_name,reference_to_nomenclature_,parameter_value,symbol,direction,group,receiving_environment,name_text,geographical_location
			$triples = array (
				array(
					"subject"=> $model_bnode,
					"predicate"=> "eco:models",
					"object"=> $process_bnode
					),
				array(
					"subject"=> $model_bnode,
					"predicate"=> "eco:models",
					"object"=> $product_bnode
					),										
				array(
					"subject"=> $model_bnode,
					"predicate"=> "rdfs:type",
					"object"=> "eco:FootprintModel"
					),
				array(
					"subject"=> $model_bnode,
					"predicate"=> "eco:hasDataSource",
					"object"=> $bibo_bnode
					),									
				array(
					"subject"=> $process_bnode,
					"predicate"=> "rdfs:type",
					"object"=> "eco:AbstractProcess"
					),	
				array(
					"subject"=> $process_bnode,
					"predicate"=> "rdfs:label",
					"object"=> $line_array[0]
					),				
				array(
					"subject"=> $process_bnode,
					"predicate"=> "eco:hasClassification",
					"object"=> $line_array[1]
					),
				array(
					"subject"=> $model_bnode,
					"predicate"=> "eco:hasUnallocatedExchange",
					"object"=> $exchange_bnode
					),	
				array(
					"subject"=> $exchange_bnode,
					"predicate"=> "rdfs:type",
					"object"=> "eco:EcosphereExchange"
					),									
				array(
					"subject"=> $exchange_bnode,
					"predicate"=> "eco:hasEffect",
					"object"=> $effect_bnode
					),				
				array(
					"subject"=> $effect_bnode,
					"predicate"=> "eco:hasEffectAggregationCategory",
					"object"=> $eac_bnode
					),				
				array(
					"subject"=> $eac_bnode,
					"predicate"=> "rdfs:type",
					"object"=> "fasc:Compartment"
					),
				array(
					"subject"=> $eac_bnode,
					"predicate"=> "fasc:CompartmentMedium",
					"object"=> "fasc:air"
					),
				array(
					"subject"=> $effect_bnode,
					"predicate"=> "rdfs:type",
					"object"=> "eco:Output"
					),
				array(
					"subject"=> $effect_bnode,
					"predicate"=> "eco:hasFlowable",
					"object"=> "oselemflow:carbonDioxide"
					),
				array(
					"subject"=> $exchange_bnode,
					"predicate"=> "eco:hasQuantity",
					"object"=> $quantity_bnode
					),
				array(
					"subject"=> $quantity_bnode,
					"predicate"=> "eco:hasUnitOfMeasure",
					"object"=> $line_array[10]
					),
				array(
					"subject"=> $quantity_bnode,
					"predicate"=> "eco:hasMagnitude",
					"object"=> $line_array[9]
					),					
				array(
					"subject"=> $model_bnode,
					"predicate"=> "eco:hasUnallocatedExchange",
					"object"=> $exchange2_bnode
					),	
				array(
					"subject"=> $exchange2_bnode,
					"predicate"=> "rdfs:type",
					"object"=> "eco:Exchange"
					),									
				array(
					"subject"=> $exchange2_bnode,
					"predicate"=> "eco:hasEffect",
					"object"=> $effect2_bnode
					),				
				array(
					"subject"=> $effect2_bnode,
					"predicate"=> "eco:hasEffectAggregationCategory",
					"object"=> $eac_bnode
					),				
				array(
					"subject"=> $effect2_bnode,
					"predicate"=> "rdfs:type",
					"object"=> "eco:Output"
					),
				array(
					"subject"=> $effect2_bnode,
					"predicate"=> "eco:hasTransferable",
					"object"=> $product_bnode
					),
				array(
					"subject"=> $exchange2_bnode,
					"predicate"=> "eco:hasQuantity",
					"object"=> $quantity2_bnode
					),
				array(
					"subject"=> $quantity2_bnode,
					"predicate"=> "eco:hasUnitOfMeasure",
					"object"=> $line_array[6]
					),
				array(
					"subject"=> $quantity2_bnode,
					"predicate"=> "eco:hasMagnitude",
					"object"=> $line_array[4]
					),															
				array(
					"subject"=> $product_bnode,
					"predicate"=> "rdfs:type",
					"object"=> "eco:Product"
					),
				array(
					"subject"=> $product_bnode,
					"predicate"=> "rdfs:label",
					"object"=> $line_array[0]
					),	
				array(
					"subject"=> $ia_bnode,
					"predicate"=> "rdfs:type",
					"object"=> "eco:ImpactAssessment"
					),
				array(
					"subject"=> $ia_bnode,
					"predicate"=> "eco:computedFrom",
					"object"=> $model_bnode
					),			
				array(
					"subject"=> $ia_bnode,
					"predicate"=> "eco:assessmentOf",
					"object"=> $process_bnode
					),
				array(
					"subject"=> $ia_bnode,
					"predicate"=> "eco:hasImpactCategoryIndicatorResult",
					"object"=> $icir_bnode
					),		
				array(
					"subject"=> $icir_bnode,
					"predicate"=> "rdfs:type",
					"object"=> "eco:ImpactCategoryIndicatorResult"
					),
				array(
					"subject"=> $icir_bnode,
					"predicate"=> "eco:hasImpactAssessmentMethodCategoryDescription",
					"object"=> $iamcd_bnode
					),	
				array(
					"subject"=> $iamcd_bnode,
					"predicate"=> "eco:hasImpactCategory",
					"object"=> "eco:climateChange"
					),
				array(
					"subject"=> $iamcd_bnode,
					"predicate"=> "eco:hasImpactCategoryIndicator",
					"object"=> "ossia:C02e"
					),				
				array(
					"subject"=> $iamcd_bnode,
					"predicate"=> "eco:hasImpactCharacterizationFactor",
					"object"=> "n/a"
					),
				array(
					"subject"=> $iamcd_bnode,
					"predicate"=> "eco:hasImpactCharacterizationModel",
					"object"=> "n/a"
					),											
				array(
					"subject"=> $icir_bnode,
					"predicate"=> "eco:hasQuantity",
					"object"=> $iaq_bnode
					),		
				array(
					"subject"=> $iaq_bnode,
					"predicate"=> "eco:hasUnitOfMeasure",
					"object"=> $line_array[10]
					),
				array(
					"subject"=> $iaq_bnode,
					"predicate"=> "eco:hasMagnitude",
					"object"=> $line_array[9]
					),				
				);
				if (isset($line_array[15])) {					
					$triples[] = array(
						"subject"=> $process_bnode,
						"predicate"=> "eco:hasGeoLocation",
						"object"=> $line_array[15] 
						);
				}
			
				//$bigassarray = array_merge($bigassarray, $triples);
				@$this->arcmodel->addTriples($triples);	
			
		}
	

		//@$this->arcmodel->addTriples($bigassarray);
	}
	else {
		echo "not working";
	}
			
	fclose($handle);
}







public function crmd() {
	$handle = fopen('application/data/datasets/crmd/csv/crmd.csv', "r");
	// Columns: Process_name,class_name,reference_to_nomenclature,quantitative_reference_type,quantitative_reference_amount,quantitative_reference_name,quantitative_reference_unit,area_name,reference_to_nomenclature_,parameter_value,symbol,direction,group,receiving_environment,name_text,geographical_location
	$bibo_bnode = "http://db.opensustainability.info/rdfspace/bibliography/Canadian-Raw-Materials-Database" . rand(9999999, 100000000) ."";  	
	
	$person_bnode = "http://db.opensustainability.info/rdfspace/people/MurrayHaight" . rand(9999999, 100000000) .""; 
	$organization_bnode = "http://db.opensustainability.info/rdfspace/organizations/UniversityofWaterloo" . rand(9999999, 100000000) .""; 
	
	$triples = array(
		array(
			"subject"=> $person_bnode,
			"predicate"=> "rdfs:type",
			"object"=> "foaf:Person"			
		),
		array(
			"subject"=> $person_bnode,
			"predicate"=> "foaf:firstName",
			"object"=> "Murray"			
		),
		array(
			"subject"=> $person_bnode,
			"predicate"=> "foaf:lastName",
			"object"=> "Haight"			
		),
		array(
			"subject"=> $bibo_bnode,
			"predicate"=> "rdfs:type",
			"object"=> "bibo:Webpage"
			),
		array(
			"subject"=> $bibo_bnode,
			"predicate"=> "dc:title",
			"object"=> "Canadian Raw Materials Database"
			),					
		array(
			"subject"=> $bibo_bnode,
			"predicate"=> "dc:date",
			"object"=> "2006-07-00"
			),
		array(
			"subject"=> $bibo_bnode,
			"predicate"=> "bibo:authorList",
			"object"=> $person_bnode
			),	
		array(
			"subject"=> $bibo_bnode,
			"predicate"=> "bibo:uri",
			"object"=> "http://crmd.uwaterloo.ca"
			),
		array(
			"subject"=> $bibo_bnode,
			"predicate"=> "bibo:owner",
			"object"=> $organization_bnode
			),
		array(
			"subject"=> $organization_bnode,
			"predicate"=> "rdfs:type",
			"object"=> "foaf:Organization"
			),			
		array(
			"subject"=> $organization_bnode,
			"predicate"=> "foaf:name",
			"object"=> "University of Waterloo"
			),
	);
	$this->arcmodel->addTriples($triples);
	//$bigassarray = $triples;	
	if ($handle) {
		while (!feof($handle)) {
	        $line = fgets($handle);
	        $line_array = explode(",",$line);
			$model_bnode = "http://db.opensustainability.info/rdfspace/lca/" . str_replace(")","",str_replace("(", "", str_replace(" ", "", $line_array[0]))) . rand(9999999, 100000000) ."";
			$process_bnode = "_:process" . rand(9999999, 100000000);
			$exchange_bnode = "_:exchange" . rand(9999999, 100000000);
			$effect_bnode = "_:effect" . rand(9999999, 100000000);
			$quantity_bnode = "_:quantity" . rand(9999999, 100000000);
			$exchange2_bnode = "_:exchange" . rand(9999999, 100000000);
			$effect2_bnode = "_:effect" . rand(9999999, 100000000);
			$quantity2_bnode = "_:quantity" . rand(9999999, 100000000);
			$product_bnode = "_:product" . rand(9999999, 100000000);
			$geography_bnode = "_:geography" . rand(9999999, 100000000);
	
			$triples = array (
				array(
					"subject"=> $model_bnode,
					"predicate"=> "eco:models",
					"object"=> $process_bnode
					),
				array(
					"subject"=> $model_bnode,
					"predicate"=> "eco:models",
					"object"=> $product_bnode
					),
				array(
					"subject"=> $model_bnode,
					"predicate"=> "rdfs:type",
					"object"=> "eco:FootprintModel"
					),
				array(
					"subject"=> $model_bnode,
					"predicate"=> "eco:hasDataSource",
					"object"=> $bibo_bnode
					),									
				array(
					"subject"=> $process_bnode,
					"predicate"=> "rdfs:type",
					"object"=> "eco:AbstractProcess"
					),	
				array(
					"subject"=> $process_bnode,
					"predicate"=> "rdfs:label",
					"object"=> $line_array[0]
					),				
				array(
					"subject"=> $process_bnode,
					"predicate"=> "eco:hasClassification",
					"object"=> $line_array[1]
					),
				array(
					"subject"=> $process_bnode,
					"predicate"=> "eco:hasGeoLocation",
					"object"=> "http://sws.geonames.org/6251999/about.rdf"
					),
				array(
					"subject"=> $model_bnode,
					"predicate"=> "eco:hasUnallocatedExchange",
					"object"=> $exchange2_bnode
					),	
				array(
					"subject"=> $exchange2_bnode,
					"predicate"=> "rdfs:type",
					"object"=> "eco:Exchange"
					),									
				array(
					"subject"=> $exchange2_bnode,
					"predicate"=> "eco:hasEffect",
					"object"=> $effect2_bnode
					),								
				array(
					"subject"=> $effect2_bnode,
					"predicate"=> "rdfs:type",
					"object"=> "eco:Output"
					),
				array(
					"subject"=> $effect2_bnode,
					"predicate"=> "eco:hasTransferable",
					"object"=> $product_bnode
					),
				array(
					"subject"=> $exchange2_bnode,
					"predicate"=> "eco:hasQuantity",
					"object"=> $quantity2_bnode
					),
				array(
					"subject"=> $quantity2_bnode,
					"predicate"=> "eco:hasUnitOfMeasure",
					"object"=> $line_array[5]
					),
				array(
					"subject"=> $quantity2_bnode,
					"predicate"=> "eco:hasMagnitude",
					"object"=> $line_array[4]
					),															
				array(
					"subject"=> $product_bnode,
					"predicate"=> "rdfs:type",
					"object"=> "eco:Product"
					),
				array(
					"subject"=> $product_bnode,
					"predicate"=> "rdfs:label",
					"object"=> $line_array[0]
					)
				);
			$this->arcmodel->addTriples($triples);
			//$bigassarray = array_merge($bigassarray, $triples);
			
			$handle_io =  fopen('application/data/datasets/crmd/csv/crmd_io_'.trim($line_array[7]).'.csv', "r");
			if ($handle_io) {
				while (!feof($handle_io)) {
			        $line_io = fgets($handle_io);
			        $line_array_io = explode(",",$line_io);
					
					$io = array(	
						"direction_" => trim($line_array_io[0]),
						"group_" => trim($line_array_io[1]),
						"receiving_environment_" => trim($line_array_io[2]),
						"amount_name_" => trim($line_array_io[3]),
						"symbol_" => trim($line_array_io[4]),
						"parameter_value_" => trim($line_array_io[5])
					);
					//  "data_collection_" => trim($line_array_io[6])
					if ($io["symbol_"] == "qudt:Kilogram") {
						$io["symbol_"] = "qudt:Kilogram";
					} elseif ($io["symbol_"] == "qudt:Liter") {
						$io["symbol_"] = "qudt:Liter";
					}
					
					if ($io["direction_"] == "output") {
						$exchange_type = "eco:EcosphereExchange";		
						$tf = "eco:hasTransferable";											
					} else {
						$exchange_type = "eco:TechnosphereExchange";
						$tf = "eco:hasFlowable";		
					}	
					if ($io["receiving_environment_"] == "land") {
						$io["receiving_environment_"] = "soil";												
					} 
					if ($io["receiving_environment_"] == "soil" || $io["receiving_environment_"] == "water" || $io["receiving_environment_"] == "air") {
						$io["receiving_environment_"] = "fasc:" . $io["receiving_environment_"];
					}
					
					$exchange_bnode = "_:exchange" . rand(9999999, 100000000);
					$effect_bnode = "_:effect" . rand(9999999, 100000000);
					$quantity_bnode = "_:quantity" . rand(9999999, 100000000);
					$eac_bnode = "_:eac" . rand(9999999, 100000000);
								
					$triples_io = array (
						array(
							"subject"=> $model_bnode,
							"predicate"=> "eco:hasUnallocatedExchange",
							"object"=> $exchange_bnode
							),
						array(
								"subject"=> $exchange_bnode,
								"predicate"=> "rdfs:type",
								"object"=> $exchange_type
							),			
						array(
							"subject"=> $exchange_bnode,
							"predicate"=> "eco:hasEffect",
							"object"=> $effect_bnode
							),
						array(
							"subject"=> $effect_bnode,
							"predicate"=> "rdfs:type",
							"object"=> "eco:" . ucfirst(trim($io["direction_"]))
							),
						array(
							"subject"=> $effect_bnode,
							"predicate"=> $tf,
							"object"=> $io["amount_name_"]
							),
						array(
							"subject"=> $exchange_bnode,
							"predicate"=> "eco:hasQuantity",
							"object"=> $quantity_bnode
							),
						array(
							"subject"=> $quantity_bnode,
							"predicate"=> "eco:hasUnitOfMeasure",
							"object"=> trim($io["symbol_"])
							),
						array(
							"subject"=> $quantity_bnode,
							"predicate"=> "eco:hasMagnitude",
							"object"=> $io["parameter_value_"]
							),
						);
					
						if ($io["receiving_environment_"] != "" && $io["receiving_environment_"] != "technosphere") {
							$triples_io[] = array(
								"subject"=> $effect_bnode,
								"predicate"=> "eco:hasEffectAggregationCategory",
								"object"=> $eac_bnode
								);			
							$triples_io[] = array(
								"subject"=> $eac_bnode,
								"predicate"=> "rdfs:type",
								"object"=> "fasc:Compartment"
								);
							$triples_io[] = array(
								"subject"=> $eac_bnode,
								"predicate"=> "fasc:CompartmentMedium",
								"object"=> $io["receiving_environment_"]
								);
						}	
					$this->arcmodel->addTriples($triples_io);		
					//$bigassarray = array_merge($bigassarray, $triples_io);
				}
			} 	else {
					echo "\nFailure to open crmd_io_".trim($line_array[7]);
				}
			fclose($handle_io);
			$handle_ia =  fopen('application/data/datasets/crmd/csv/crmd_ia_'.trim($line_array[7]).'.csv', "r");
			if ($handle_ia) {
				while (!feof($handle_ia)) {
			        $line_ia = fgets($handle_ia);
			        $line_array_ia = explode(",",$line_ia);
					$ia_bnode =  "_:impactassessment" . rand(9999999, 100000000);
					$icir_bnode =  "_:icir" . rand(9999999, 100000000);
					$iaq_bnode =  "_:quantity" . rand(9999999, 100000000);
					$iamcd_bnode = "_:iamcd" . rand(9999999, 100000000);
					$ia = array(	
						"impact_category_" => trim($line_array_ia[0]),
						"value_" => trim($line_array_ia[1]),
						"unit_" => trim($line_array_ia[2])
					);
					
					if ($ia["impact_category_"] == "Waste") {
						$ia["impact_category_"] = "ossia:waste";
						$ia["impact_category_indicator_"] = "ossia:waste";						
					} elseif ($ia["impact_category_"] == "Energy Use") {
						$ia["impact_category_"] = "ossia:resourceConsumption";
						$ia["impact_category_indicator_"] = "ossia:energy";
					} elseif ($ia["impact_category_"] == "Carbon Dioxide Emissions") {
						$ia["impact_category_"] = "eco:climateChange";
						$ia["impact_category_indicator_"] = "ossia:CO2e";						
					} elseif ($ia["impact_category_"] == "Water Use") {
						$ia["impact_category_"] = "ossia:resourceConsumption";
						$ia["impact_category_indicator_"] = "ossia:water";						
					} else {
						$ia["impact_category_"] = "";
						$ia["impact_category_indicator_"] = "";
					}
						
				$triples_ia = array(
					array(
						"subject"=> $ia_bnode,
						"predicate"=> "rdfs:type",
						"object"=> "eco:ImpactAssessment"
						),
					array(
						"subject"=> $ia_bnode,
						"predicate"=> "eco:computedFrom",
						"object"=> $model_bnode
						),			
					array(
						"subject"=> $ia_bnode,
						"predicate"=> "eco:assessmentOf",
						"object"=> $process_bnode
						),
					array(
						"subject"=> $ia_bnode,
						"predicate"=> "eco:hasImpactCategoryIndicatorResult",
						"object"=> $icir_bnode
						),		
					array(
						"subject"=> $icir_bnode,
						"predicate"=> "rdfs:type",
						"object"=> "eco:ImpactCategoryIndicatorResult"
						),					
					array(
						"subject"=> $icir_bnode,
						"predicate"=> "eco:hasImpactAssessmentMethodCategoryDescription",
						"object"=> $iamcd_bnode
						),	
					array(
						"subject"=> $iamcd_bnode,
						"predicate"=> "eco:hasImpactCategory",
						"object"=> $ia["impact_category_"]
						),
					array(
						"subject"=> $iamcd_bnode,
						"predicate"=> "eco:hasImpactCategoryIndicator",
						"object"=> $ia["impact_category_indicator_"]
						),				
					array(
						"subject"=> $iamcd_bnode,
						"predicate"=> "eco:hasImpactCharacterizationFactor",
						"object"=> "n/a"
						),
					array(
						"subject"=> $iamcd_bnode,
						"predicate"=> "eco:hasImpactCharacterizationModel",
						"object"=> "n/a"
						),												
					array(
						"subject"=> $icir_bnode,
						"predicate"=> "eco:hasQuantity",
						"object"=> $iaq_bnode
						),		
					array(
						"subject"=> $iaq_bnode,
						"predicate"=> "eco:hasUnitOfMeasure",
						"object"=> $ia["unit_"]
						),
					array(
						"subject"=> $iaq_bnode,
						"predicate"=> "eco:hasMagnitude",
						"object"=> $ia["value_"]
						),				
					);
				$this->arcmodel->addTriples($triples_ia);	
				}
				
				//$bigassarray = array_merge($bigassarray, $triples_io);
			} else {
				echo "\nFailure to open crmd_ia_".trim($line_array[7]);
			}
			
			fclose($handle_ia);
			
		}

	}
	else {
		echo "not working";
	}
	fclose($handle);
	//var_dump($bigassarray);
	//$this->arcmodel->addTriples($bigassarray);
}






}