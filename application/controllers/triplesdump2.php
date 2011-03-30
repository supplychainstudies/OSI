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




















	public function ICE_references() {
		$handle = fopen('application/data/datasets/ICE_REFERENCES.csv', "r");	
		// Ref No.	Title	Author	Year	Organisation	ISBN	Volume		page range	Journal	doi	location	book name	chapter #	misc	websites Conference
		$authors = array();
		$authors_check = array();
		$journal_check = array();
		$organization_check = array();
		$book_check = array();
		$conference_check = array();
		if ($handle) {
			while (!feof($handle)) {
				$triples = array();
		        $line = fgets($handle);
				$line = str_replace('"', '', $line);
		        $line_array = explode(",",$line);
				foreach($line_array as &$val) {
					$val = str_replace("~", ",", $val);
				}

					// Organizations
					$organization_uris = array();
					if (trim($line_array[4]) != "") {
						foreach(explode(";", trim($line_array[4])) as $organization) {
							$aliases = explode(",", $organization);
							$alias_uris = array();
							foreach ($aliases as $alias) {
								if (in_array($alias, $organization_check) == true) {	
									$alias_uris[] = array_search($alias, $organization_check);
								} 
							}
							if (count($alias_uris) == 0) {
									$organization_bnode = $this->name_conversion->toURI("organization", $aliases[0]);
									$organization_check[$organization_bnode] = $alias;
									$triples[] = array(
										"subject"=> $organization_bnode,
										"predicate"=> "rdfs:type",
										"object"=> "foaf:Organization"
										);	
									foreach ($aliases as $alias) {
										$triples[] = array(
											"subject"=> $organization_bnode,
											"predicate"=> "foaf:name",
											"object"=> trim($alias)
											);
									}				
									$organization_uris[] = $organization_bnode;					
							} else {
									$organization_uris = array_merge($organization_uris, $alias_uris);
							}
						}
					} else {
						$organization_uris = "";
					}
		
					// Authors
					$author_uris = array();
					if (trim($line_array[2]) != "") {
						foreach(explode(",", trim($line_array[2])) as $author) {
							$author_array = explode(" ", trim($author));
							if (in_array($author, $authors_check) != true) {	
								$foaf_bnode = $this->name_conversion->toURI("person", $author);
								$authors_check[$foaf_bnode] = $author;
								$triples[] = array(
									"subject"=> $foaf_bnode,
									"predicate"=> "rdfs:type",
									"object"=> "foaf:Person"
									);					
								if (count($author_array) == 1) {
									$triples[] = array(
										"subject"=> $foaf_bnode,
										"predicate"=> "foaf:lastName",
										"object"=> trim($author_array[0])
										);
								} elseif (count($author_array) == 2) {
									$triples[] = array(
										"subject"=> $foaf_bnode,
										"predicate"=> "foaf:firstName",
										"object"=> trim($author_array[0])
										);
									$triples[] = array(
										"subject"=> $foaf_bnode,
										"predicate"=> "foaf:lastName",
										"object"=> trim($author_array[1])
										);
								} elseif (count($author_array) == 3) {
									$triples[] = array(
										"subject"=> $foaf_bnode,
										"predicate"=> "foaf:firstName",
										"object"=> trim($author_array[0]) . " " . trim($author_array[1])
										);
									$triples[] = array(
										"subject"=> $foaf_bnode,
										"predicate"=> "foaf:lastName",
										"object"=> trim($author_array[2])
										);
								}	elseif (count($author_array) == 4) {
									$triples[] = array(
										"subject"=> $foaf_bnode,
										"predicate"=> "foaf:firstName",
										"object"=> trim($author_array[0]) . " " . trim($author_array[1])
										);
									$triples[] = array(
										"subject"=> $foaf_bnode,
										"predicate"=> "foaf:lastName",
										"object"=> trim($author_array[2]) . " " . trim($author_array[3])
										);
								}	elseif (count($author_array) == 5) {
									$triples[] = array(
										"subject"=> $foaf_bnode,
										"predicate"=> "foaf:firstName",
										"object"=> trim($author_array[0]) . " " . trim($author_array[1]) . " " . trim($author_array[2])
										);
									$triples[] = array(
										"subject"=> $foaf_bnode,
										"predicate"=> "foaf:lastName",
										"object"=> trim($author_array[3]) . trim($author_array[4])
										);
								}
								$author_uris[] = $foaf_bnode;					
							} else {
								$author_uris[] = array_search($author, $authors_check);
							}
						}
					} else {
						$author_uris = "";
					}
										
					// Journals
					if (trim($line_array[9]) != "" && in_array(trim($line_array[9]), $journal_check) == false) {
						
						$journal_bnode = $this->name_conversion->toURI("journal", $line_array[9]);
						$journal_check[$journal_bnode] = trim($line_array[9]);
						$triples[] = array(
							"subject"=> $journal_bnode,
							"predicate"=> "rdfs:type",
							"object"=> "bibo:Journal"
							);
						$triples[] = array(
							"subject"=> $journal_bnode,
							"predicate"=> "dc:title",
							"object"=> trim($line_array[9])
							);
					} elseif (trim($line_array[9]) != "" && in_array(trim($line_array[9]), $journal_check) == true) {
						$journal_uri = array_search(trim($line_array[9]), $journal_check);
					} else {
						$journal_uri = "";
					}
		
					// Book
					if (trim($line_array[12]) != "" && in_array(trim($line_array[12]), $book_check) == false) {
						
						$book_bnode = $this->name_conversion->toURI("book", $line_array[12]);
						$book_check[$book_bnode] = trim($line_array[12]);
						$triples[] = array(
							"subject"=> $book_bnode,
							"predicate"=> "rdfs:type",
							"object"=> "bibo:Book"
							);
						$triples[] = array(
							"subject"=> $book_bnode,
							"predicate"=> "dc:title",
							"object"=> trim($line_array[12])
							);
					} elseif (trim($line_array[12]) != "" && in_array(trim($line_array[12]), $book_check) == true) {
						$book_uri = array_search(trim($line_array[12]), $book_check);
					} else {
						$book_uri = "";
					}
										
					// Conferences
					if (trim($line_array[16]) != "" && in_array(trim($line_array[16]), $conference_check) == false) {
						
						$conference_bnode = $this->name_conversion->toURI("conference", $line_array[16]);
						$conference_check[$conference_bnode] = trim($line_array[16]);
						$triples[] = array(
							"subject"=> $conference_bnode,
							"predicate"=> "rdfs:type",
							"object"=> "bibo:Conference"
							);
						$triples[] = array(
							"subject"=> $conference_bnode,
							"predicate"=> "dc:title",
							"object"=> trim($line_array[16])
							);
						if (trim($line_array[11]) != "") {
							$triples[] = array(
								"subject"=> $conference_bnode,
								"predicate"=> "event:place",
								"object"=> trim($line_array[11])
								);							
						}
						$time = explode("-",trim($line_array[3]));
						if (count($time) == 1) {
							$triples[] = array(
								"subject"=> $conference_bnode,
								"predicate"=> "event:time",
								"object"=> trim($time[0])
								);							
						}elseif (count($time) == 2) {
							$timeinterval_bnode = "_:interval" . rand(9999999, 100000000) ."";
							$triples[] = array(
								"subject"=> $conference_bnode,
								"predicate"=> "event:time",
								"object"=> $timeinterval_bnode
								);						
							$triples[] = array(
								"subject"=> $timeinterval_bnode,
								"predicate"=> "rdfs:type",
								"object"=> "time:Interval"
								);
							$triples[] = array(
								"subject"=> $timeinterval_bnode,
								"predicate"=> "timeline:beginsAtDateTime",
								"object"=> trim($time[0])
								);
							$triples[] = array(
								"subject"=> $timeinterval_bnode,
								"predicate"=> "timeline:endsAtDateTime",
								"object"=> trim($time[1])
								);	
						}						
					} elseif (trim($line_array[16]) != "" && in_array(trim($line_array[16]), $conference_check) == true) {
						$conference_uri = array_search(trim($line_array[16]), $conference_check);
					} else {
						$conference_uri = "";
					}		
		
					$reference_bnode = $this->name_conversion->toURI("bibliography", $line_array[1]);
					echo $line_array[0] . " - " . $reference_bnode. "<br />\n";
					$reference = array(	
						"dc:title" => trim($line_array[1]),
						"bibo:authorList" => $author_uris,
						"dc:date" => trim($line_array[3]),
						"dc:creator" => $organization_uris,
						"bibo:isbn" => trim($line_array[5]),
						"bibo:volume" => trim($line_array[6]),
						"bibo:issue" => trim($line_array[7]),
						"bibo:doi" => trim($line_array[10]),
						"bibo:chapter" => trim($line_array[13]),
						"bibo:locator" => trim($line_array[14]),
						"bibo:uri" => trim($line_array[15])
					);
					if ($line_array[8] != "") {
						$pages = explode("-",trim($line_array[8]));
						$reference["bibo:pageStart"] = trim($pages[0]);
						$reference["bibo:pageEnd"] = trim($pages[1]);
					}
					if (isset($journal_uri) == true) {
						$reference["bibo:isPartOf"] = $journal_uri;					
					} elseif (isset($book_uri) == true) {
						$reference["bibo:isPartOf"] = $book_uri;					
					}
					if (isset($conference_uri) == true) {
						$reference["bibo:presentedAt"] = $conference_uri;					
					}
					
					foreach ($reference as $key=>$field) {
						if ($field != "" && is_array($field) == false) {
							$triples[] = array(
								"subject"=> $reference_bnode,
								"predicate"=> $key,
								"object"=> $field
								);
						} elseif ($field != "" && is_array($field) == true) {
							foreach ($field as $value) {
								$triples[] = array(
									"subject"=> $reference_bnode,
									"predicate"=> $key,
									"object"=> $value
									);
							}
						}
					}						

				//var_dump($triples);
				$this->arcmodel->addTriples($triples);		
			// End of while (!feof($handle)) 	
			}
		//	End of if($handle)	
		}
	// End of function
	}



	public function ICE() {
		$handle = fopen('application/data/datasets/ICE.csv', "r");
		// Columns: Name	unit	EE	Standard Deviation	Minimum EE	Maximum EE	CO2	Co2e	GWP	Process Description	References		
		
		$handle_reference = fopen('application/data/datasets/ice_references_relation.csv', "r");	
		$references = array();
		if ($handle_reference) {
			while (!feof($handle_reference)) {
		        $line = fgets($handle_reference);
		        $line_array = explode(",",$line);
				$references[$line_array[0]] = $line_array[1];
			}
		}

		if ($handle) {
			while (!feof($handle)) {
		        $line = fgets($handle);
		        $line_array = explode(",",$line);
				$model_bnode = $this->name_conversion->toURI("lca",$line_array[0]);
				$process_bnode = "_:process" . rand(9999999, 100000000);
				$exchange_bnode = "_:exchange" . rand(9999999, 100000000);
				$effect_bnode = "_:effect" . rand(9999999, 100000000);
				$quantity_bnode = "_:quantity" . rand(9999999, 100000000);
				$exchange2_bnode = "_:exchange" . rand(9999999, 100000000);
				$effect2_bnode = "_:effect" . rand(9999999, 100000000);
				$quantity2_bnode = "_:quantity" . rand(9999999, 100000000);
				$exchange3_bnode = "_:exchange" . rand(9999999, 100000000);
				$effect3_bnode = "_:effect" . rand(9999999, 100000000);
				$quantity3_bnode = "_:quantity" . rand(9999999, 100000000);
				$product_bnode = "_:product" . rand(9999999, 100000000);
				$geography_bnode = "_:geography" . rand(9999999, 100000000);
				$icir_bnode =  "_:icir" . rand(9999999, 100000000);
				$iaq_bnode =  "_:quantity" . rand(9999999, 100000000);
				$ia_bnode =  "_:impactassessment" . rand(9999999, 100000000);
				$iamcd_bnode = "_:iamcd" . rand(9999999, 100000000);
				$eac_bnode = "_:eac" . rand(9999999, 100000000);
				$icir2_bnode =  "_:icir" . rand(9999999, 100000000);
				$iaq2_bnode =  "_:quantity" . rand(9999999, 100000000);
				$ia2_bnode =  "_:impactassessment" . rand(9999999, 100000000);
				$iamcd2_bnode = "_:iamcd" . rand(9999999, 100000000);
				$eac2_bnode = "_:eac" . rand(9999999, 100000000);
				$icir3_bnode =  "_:icir" . rand(9999999, 100000000);
				$iaq3_bnode =  "_:quantity" . rand(9999999, 100000000);
				$ia3_bnode =  "_:impactassessment" . rand(9999999, 100000000);
				$iamcd3_bnode = "_:iamcd" . rand(9999999, 100000000);
				$eac3_bnode = "_:eac" . rand(9999999, 100000000);
				$uncertainty_bnode = "_:uncertainty" . rand(9999999, 100000000);
				$uncertainty2_bnode = "_:uncertainty" . rand(9999999, 100000000);
				$uncertainty3_bnode = "_:uncertainty" . rand(9999999, 100000000);
				$uncertainty4_bnode = "_:uncertainty" . rand(9999999, 100000000);
				
				$triples = array (
					array(
						"subject"=> $model_bnode,
						"predicate"=> "eco:models",
						"object"=> $process_bnode
						),
					array(
						"subject"=> $model_bnode,
						"predicate"=> "rdfs:description",
						"object"=> $line_array[10]
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
						"object"=> $line_array[1]
						),
					array(
						"subject"=> $quantity2_bnode,
						"predicate"=> "eco:hasMagnitude",
						"object"=> "1"
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
						"subject"=> $process_bnode,
						"predicate"=> "eco:hasGeoLocation",
						"object"=> "http://sws.geonames.org/2635167/about.rdf" 
						)
					);	
						
					// if carbon
					if ($line_array[6] != "") {			
						$triples[] = array(
						"subject"=> $model_bnode,
						"predicate"=> "eco:hasUnallocatedExchange",
						"object"=> $exchange_bnode
						);	
						$triples[] = array(
						"subject"=> $exchange_bnode,
						"predicate"=> "rdfs:type",
						"object"=> "eco:EcosphereExchange"
						);									
						$triples[] = array(
						"subject"=> $exchange_bnode,
						"predicate"=> "eco:hasEffect",
						"object"=> $effect_bnode
						);				
						$triples[] = array(
						"subject"=> $effect_bnode,
						"predicate"=> "eco:hasEffectAggregationCategory",
						"object"=> $eac_bnode
						);			
						$triples[] = array(
						"subject"=> $eac_bnode,
						"predicate"=> "rdfs:type",
						"object"=> "fasc:Compartment"
						);
						$triples[] = array(
						"subject"=> $eac_bnode,
						"predicate"=> "fasc:CompartmentMedium",
						"object"=> "fasc:air"
						);
						$triples[] = array(
						"subject"=> $effect_bnode,
						"predicate"=> "rdfs:type",
						"object"=> "eco:Output"
						);
						$triples[] = array(
						"subject"=> $effect_bnode,
						"predicate"=> "eco:hasFlowable",
						"object"=> "oselemflow:carbonDioxide"
						);
						$triples[] = array(
						"subject"=> $exchange_bnode,
						"predicate"=> "eco:hasQuantity",
						"object"=> $quantity_bnode
						);
						$triples[] = array(
						"subject"=> $quantity_bnode,
						"predicate"=> "eco:hasUnitOfMeasure",
						"object"=> "qudt:Kilogram"
						);
						$triples[] = array(
						"subject"=> $quantity_bnode,
						"predicate"=> "eco:hasMagnitude",
						"object"=> $line_array[6]
						);	
					}	
						
						
					// Is there a record for embodied energy?	
					if ($line_array[2] != "") {	
						$triples[] = array(
						"subject"=> $model_bnode,
						"predicate"=> "eco:hasUnallocatedExchange",
						"object"=> $exchange2_bnode
						);	
						$triples[] = array(
						"subject"=> $exchange2_bnode,
						"predicate"=> "rdfs:type",
						"object"=> "eco:Exchange"
						);									
						$triples[] = array(
						"subject"=> $exchange3_bnode,
						"predicate"=> "eco:hasEffect",
						"object"=> $effect3_bnode
						);			
						$triples[] = array(
						"subject"=> $effect3_bnode,
						"predicate"=> "eco:hasEffectAggregationCategory",
						"object"=> $eac_bnode
						);			
						$triples[] = array(
						"subject"=> $effect3_bnode,
						"predicate"=> "rdfs:type",
						"object"=> "eco:Input"
						);
						$triples[] = array(
						"subject"=> $effect3_bnode,
						"predicate"=> "eco:hasFlowable",
						"object"=> "Energy"
						);
						$triples[] = array(
						"subject"=> $exchange3_bnode,
						"predicate"=> "eco:hasQuantity",
						"object"=> $quantity3_bnode
						);
						$triples[] = array(
						"subject"=> $quantity3_bnode,
						"predicate"=> "eco:hasUnitOfMeasure",
						"object"=> $line_array[1]
						);
						$triples[] = array(
						"subject"=> $quantity3_bnode,
						"predicate"=> "eco:hasMagnitude",
						"object"=> $line_array[2]
						);

					if ($line_array[3] != "" || $line_array[4] != "" || $line_array[5] != "") {
						$triples[] = array(
						"subject"=> $quantity3_bnode,
						"predicate"=> "eco:hasUncertaintyDistribution",
						"object"=> $uncertainty4_bnode
						);				
						$triples[] = array(
							"subject"=> $uncertainty4_bnode,
							"predicate"=> "rdfs:type",
							"object"=> "ecoUD:LogNormalDistribution"
							);
						$triples[] = array(
							"subject"=> $uncertainty4_bnode,
							"predicate"=> "ecoUD:meanValue",
							"object"=> $line_array[2]
							);
						if ($line_array[3] != "") {
							$triples[] = array(
								"subject"=> $uncertainty4_bnode,
								"predicate"=> "ecoUD:standardDeviation95WithPedigreeUncertainty",
								"object"=> $line_array[3]
								);							
						}
						if ($line_array[4] != "") {
							$triples[] = array(
								"subject"=> $uncertainty4_bnode,
								"predicate"=> "ecoUD:minValue",
								"object"=> $line_array[4]								
								);							
						}
						if ($line_array[5] != "") {
							$triples[] = array(
								"subject"=> $uncertainty4_bnode,
								"predicate"=> "ecoUD:maxValue",
								"object"=> $line_array[5]								
								);							
						}	
					}	
				}
				
				// if there is CO2e											
				if ($line_array[7] != "") {
					$triples[] = array(
						"subject"=> $ia_bnode,
						"predicate"=> "eco:hasImpactCategoryIndicatorResult",
						"object"=> $icir_bnode
						);		
					$triples[] = array(
						"subject"=> $icir_bnode,
						"predicate"=> "rdfs:type",
						"object"=> "eco:ImpactCategoryIndicatorResult"
						);
					$triples[] = array(
						"subject"=> $icir_bnode,
						"predicate"=> "eco:hasImpactAssessmentMethodCategoryDescription",
						"object"=> $iamcd_bnode
						);	
					$triples[] = array(
						"subject"=> $iamcd_bnode,
						"predicate"=> "eco:hasImpactCategory",
						"object"=> "eco:climateChange"
						);
					$triples[] = array(
						"subject"=> $iamcd_bnode,
						"predicate"=> "eco:hasImpactCategoryIndicator",
						"object"=> "ossia:C02e"
						);				
					$triples[] = array(
						"subject"=> $iamcd_bnode,
						"predicate"=> "eco:hasImpactCharacterizationFactor",
						"object"=> "n/a"
						);
					$triples[] = array(
						"subject"=> $iamcd_bnode,
						"predicate"=> "eco:hasImpactCharacterizationModel",
						"object"=> "n/a"
						);											
					$triples[] = array(
						"subject"=> $icir_bnode,
						"predicate"=> "eco:hasQuantity",
						"object"=> $iaq_bnode
						);		
					$triples[] = array(
						"subject"=> $iaq_bnode,
						"predicate"=> "eco:hasUnitOfMeasure",
						"object"=> "qudt:Kilogram"
						);
					$triples[] = array(
						"subject"=> $iaq_bnode,
						"predicate"=> "eco:hasMagnitude",
						"object"=> $line_array[7]
						);
					if ($line_array[8] != "" || $line_array[9] != "") {
						$triples[] = array(
						"subject"=> $iaq_bnode,
						"predicate"=> "eco:hasUncertaintyDistribution",
						"object"=> $uncertainty_bnode
						);				
						$triples[] = array(
							"subject"=> $uncertainty_bnode,
							"predicate"=> "rdfs:type",
							"object"=> "ecoUD:LogNormalDistribution"
							);
						$triples[] = array(
							"subject"=> $uncertainty_bnode,
							"predicate"=> "ecoUD:meanValue",
							"object"=> $line_array[2]
							);
						if ($line_array[4] != "") {
							$triples[] = array(
								"subject"=> $uncertainty_bnode,
								"predicate"=> "ecoUD:minValue",
								"object"=> $line_array[8]								
								);							
						}
						if ($line_array[5] != "") {
							$triples[] = array(
								"subject"=> $uncertainty_bnode,
								"predicate"=> "ecoUD:maxValue",
								"object"=> $line_array[9]								
								);							
						}	
					}
				}
				
				
				// if Energy
				if ($line_array[2] != "") {
					$triples[] = array(
					"subject"=> $ia2_bnode,
					"predicate"=> "eco:hasImpactCategoryIndicatorResult",
					"object"=> $icir2_bnode
					);		
					$triples[] = array(
					"subject"=> $icir2_bnode,
					"predicate"=> "rdfs:type",
					"object"=> "eco:ImpactCategoryIndicatorResult"
					);
					$triples[] = array(
					"subject"=> $icir2_bnode,
					"predicate"=> "eco:hasImpactAssessmentMethodCategoryDescription",
					"object"=> $iamcd2_bnode
					);	
					$triples[] = array(
					"subject"=> $iamcd2_bnode,
					"predicate"=> "eco:hasImpactCategory",
					"object"=> "ossia:ResourceConsumption"
					);
					$triples[] = array(
					"subject"=> $iamcd2_bnode,
					"predicate"=> "eco:hasImpactCategoryIndicator",
					"object"=> "ossia:Energy"
					);				
					$triples[] = array(
					"subject"=> $iamcd2_bnode,
					"predicate"=> "eco:hasImpactCharacterizationFactor",
					"object"=> "n/a"
					);
					$triples[] = array(
					"subject"=> $iamcd2_bnode,
					"predicate"=> "eco:hasImpactCharacterizationModel",
					"object"=> "n/a"
					);										
					$triples[] = array(
					"subject"=> $icir2_bnode,
					"predicate"=> "eco:hasQuantity",
					"object"=> $iaq2_bnode
					);		
					$triples[] = array(
					"subject"=> $iaq2_bnode,
					"predicate"=> "eco:hasUnitOfMeasure",
					"object"=> "qudt:Megajoules"
					);
					$triples[] = array(
					"subject"=> $iaq2_bnode,
					"predicate"=> "eco:hasMagnitude",
					"object"=> $line_array[2]
					);

				if ($line_array[3] != "" || $line_array[4] != "" || $line_array[5] != "") {
					$triples[] = array(
					"subject"=> $iaq2_bnode,
					"predicate"=> "eco:hasUncertaintyDistribution",
					"object"=> $uncertainty2_bnode
					);				
					$triples[] = array(
						"subject"=> $uncertainty2_bnode,
						"predicate"=> "rdfs:type",
						"object"=> "ecoUD:LogNormalDistribution"
						);
					$triples[] = array(
						"subject"=> $uncertainty2_bnode,
						"predicate"=> "ecoUD:meanValue",
						"object"=> $line_array[2]
						);
					if ($line_array[3] != "") {
						$triples[] = array(
							"subject"=> $uncertainty2_bnode,
							"predicate"=> "ecoUD:standardDeviation95WithPedigreeUncertainty",
							"object"=> $line_array[3]
							);							
					}
					if ($line_array[4] != "") {
						$triples[] = array(
							"subject"=> $uncertainty2_bnode,
							"predicate"=> "ecoUD:minValue",
							"object"=> $line_array[4]								
							);							
					}
					if ($line_array[5] != "") {
						$triples[] = array(
							"subject"=> $uncertainty2_bnode,
							"predicate"=> "ecoUD:maxValue",
							"object"=> $line_array[5]								
							);							
					}	
				}
			}
				
				
					// if CO2?
					if ($line_array[6] != "") {	
					$triples[] = array(
							"subject"=> $ia3_bnode,
							"predicate"=> "eco:hasImpactCategoryIndicatorResult",
							"object"=> $icir_bnode
							);		
					$triples[] = array(
							"subject"=> $icir3_bnode,
							"predicate"=> "rdfs:type",
							"object"=> "eco:ImpactCategoryIndicatorResult"
							);
					$triples[] = array(
							"subject"=> $icir3_bnode,
							"predicate"=> "eco:hasImpactAssessmentMethodCategoryDescription",
							"object"=> $iamcd_bnode
							);	
					$triples[] = array(
							"subject"=> $iamcd3_bnode,
							"predicate"=> "eco:hasImpactCategory",
							"object"=> "eco:climateChange"
							);
					$triples[] = array(
							"subject"=> $iamcd3_bnode,
							"predicate"=> "eco:hasImpactCategoryIndicator",
							"object"=> "ossia:CO2"
							);				
					$triples[] = array(
							"subject"=> $iamcd3_bnode,
							"predicate"=> "eco:hasImpactCharacterizationFactor",
							"object"=> "n/a"
							);
					$triples[] = array(
							"subject"=> $iamcd3_bnode,
							"predicate"=> "eco:hasImpactCharacterizationModel",
							"object"=> "n/a"
							);											
					$triples[] = array(
							"subject"=> $icir3_bnode,
							"predicate"=> "eco:hasQuantity",
							"object"=> $iaq3_bnode
							);		
					$triples[] = array(
							"subject"=> $iaq3_bnode,
							"predicate"=> "eco:hasUnitOfMeasure",
							"object"=> "qudt:Kilogram"
							);
					$triples[] = array(
							"subject"=> $iaq3_bnode,
							"predicate"=> "eco:hasMagnitude",
							"object"=> $line_array[6]
							);
					}
					$refs = explode("-", $line_array[11]);
					foreach ($refs as $ref) {
						$triples[] = array(
							"subject"=> $model_bnode,
							"predicate"=> "eco:hasDataSource",
							"object"=> $references[$ref]
							);						
					}
					var_dump($triples);			
					//@$this->arcmodel->addTriples($triples);	
			}
		}
		else {
			echo "not working";
		}
			
		fclose($handle);
	}


// End of Class
}


