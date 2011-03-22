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


class data_dump2 extends SM_Controller {
	public function data_dump2() {
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

public function crmd() {
	$handle = fopen('application/data/datasets/crmd/csv/crmd.csv', r);
	// Columns: Process_name,class_name,reference_to_nomenclature,quantitative_reference_type,quantitative_reference_amount,quantitative_reference_name,quantitative_reference_unit,area_name,reference_to_nomenclature_,parameter_value,symbol,direction,group,receiving_environment,name_text,geographical_location
	if ($handle) {
		$data
		$data_processdescription = $this->form_extended->load('processdescription');	
		$data_inputsandoutputs = $this->form_extended->load('inputsandoutputs');
		$data_ia = $this->form_extended->load('impactassessment');
		$data_admininfo = $this->form_extended->load('administrativeinformation');
		while (!feof($handle)) {

	        $line = fgets($handle);

	        $line_array = explode(",",$line);
			$process_description = array(
				"process_name_" => trim(ucfirst(strtolower($line_array[0]))),
				"class_name_" => trim($line_array[1]),
				"reference_to_nomenclature_" => trim($line_array[2]),
				"quantitative_reference_type_" => trim($line_array[3]),
				"quantitative_reference_amount_" => trim($line_array[4]),
				"quantitative_reference_name_" => trim($line_array[6]),
				"quantitative_reference_unit_" => trim(ucfirst(strtolower($line_array[5])))
			);
			$uri_number = rand(1000000000,10000000000);
			$URI = "http://opensustainability.info/".$uri_number;
			$pre_triples = array();
			$lci_bnode = $this->name_conversion->toBNode('lifeCycleInventory');
			$process_bnode = $this->name_conversion->toBNode('process');
			$process_description_bnode = $this->name_conversion->toBNode('process');
			$pre_triples[] = Array("subject" => $URI, "predicate" => "rdfs:type", "object" => "lca");
			$pre_triples[] = Array("subject" => $URI, "predicate" => "lca:lifeCycleInventory", "object" => $lci_bnode);
			$pre_triples[] = Array("subject" => $lci_bnode, "predicate" => "lca:process", "object" => $process_bnode);				
			$triples = $this->form_extended->build_triples($process_bnode, $process_description, $data_processdescription);
			@$this->arcmodel->addTriples(array_merge($pre_triples, $triples));
	
			$handle_io =  fopen('application/data/datasets/crmd/csv/crmd_io_'.trim($line_array[7]).'.csv', r);
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
						"parameter_value_" => trim($line_array_io[5]),
						"data_collection_" => trim($line_array_io[6])
					);
		
					$triples = array();
					$triples = $this->form_extended->build_triples($process_bnode, $io, $data_inputsandoutputs);
					@$this->arcmodel->addTriples($triples);				
				}
			} 	else {
					echo "\nFailure to open crmd_io_".trim($line_array[7]);
				}
			fclose($handle_io);
			$handle_ia =  fopen('application/data/datasets/crmd/csv/crmd_ia_'.trim($line_array[7]).'.csv', r);
			if ($handle_ia) {
				while (!feof($handle_ia)) {
			        $line_ia = fgets($handle_ia);
			        $line_array_ia = explode(",",$line_ia);

					$ia = array(	
						"impact_category_" => trim($line_array_ia[0]),
						"value_" => trim($line_array_ia[1]),
						"unit_" => trim($line_array_ia[2])
					);
		
					$triples = array();
					$triples = $this->form_extended->build_triples($URI, $ia, $data_ia);
					@$this->arcmodel->addTriples($triples);			
				}
			} else {
				echo "\nFailure to open crmd_ia_".trim($line_array[7]);
			}
			
			fclose($handle_ia);

			$admininfo = array(	
				"registration_authority_" => "Canadian Standards Association",
				"date_completed_" => "01/01/1996",
				"publication_" => "http://crmd.uwaterloo.ca"
			);
		

			$triples = $this->form_extended->build_triples($lci_bnode, $admininfo, $data_admininfo);
			@$this->arcmodel->addTriples($triples);
		}

	}

	else {

		echo "not working";

	}

	fclose($handle);
}
}
