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



class Dataconverter extends SM_Controller {
	public function dataconverter() {
		parent::SM_Controller();
		$this->check_if_logged_in();
	}
	
	public function index() {
		$es1 = array();
		$ilcd = array();
			if (($handle = fopen("assets/data/COMPARTMENT_MAP_ES1_TO_ES2.csv", "r")) !== FALSE) {
			    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$es1[$data[0]] = $data[1];
				}
			}
		    fclose($handle);
			if (($handle = fopen("assets/data/COMPARTMENT_MAP_ILCD_TO_ES2.csv", "r")) !== FALSE) {
			    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$ilcd[$data[0]] = $data[1];
				}
			}
		    fclose($handle);
			$output = '<?xml version="1.0" encoding="UTF-8"?>
			<rdf:RDF
			  xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
			  xmlns:owl="http://www.w3.org/2002/07/owl#"
			  xmlns:es01="http://www.EcoInvent.org/EcoSpold01"
			  xmlns:ilcd="http://ilcd"
			  xmlns:dc="http://purl.org/dc/elements/1.1/">
			
			<validCompartments
				xmlns="http://www.EcoInvent.org/EcoSpold02"
				xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
				xsi:schemaLocation="http://www.EcoInvent.org/EcoSpold02 ../../Schemas/MasterData/EcoSpold02Compartments.xsd"
				contextId="DE659012-50C4-4e96-B54A-FC781BF987AB"
				majorRelease="1"
				minorRelease="0">';
			if (($handle = fopen("assets/data/ES2_COMPARTMENTS.csv", "r")) !== FALSE) {
			    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$output .= '<compartment rdf:id="' . $data[0] . '">
						<name>' . $data[1] . '</name>
						<subcompartment
							id="' . $data[0] . '">
							<name>' . $data[2] . '</name>
						</subcompartment>';

						foreach (array_keys($es1, $data[0]) as $key) {
							$output .= '<owl:sameAs rdf:resource="es1#' . $key . '" />';
						}
						foreach (array_keys($ilcd, $data[0]) as $key) {
							$output .= '<owl:sameAs rdf:resource="ilcd#' . $key . '" />';
						}
					$output .= '</compartment>';	
			    }	
			}
			fclose($handle);
			$output .= "</validCompartments></rdf:RDF>";
			header('Content-Type: text/xml');
			print $output;
	}
	
	public function flow() {
		$es1 = array();
		$ilcd = array();
			if (($handle = fopen("assets/data/FLOW_MAP_ES1_TO_ILCD.csv", "r")) !== FALSE) {
			    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$es1[$data[0]] = $data[1];
				}
			}
		    fclose($handle);
			if (($handle = fopen("assets/data/FLOW_MAP_ILCD_TO_ES2.csv", "r")) !== FALSE) {
			    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$ilcd[$data[0]] = $data[1];
				}
			}
		    fclose($handle);
			$output = '<?xml version="1.0" encoding="UTF-8"?>
			<rdf:RDF
			  xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
			  xmlns:owl="http://www.w3.org/2002/07/owl#"
			  xmlns:es1_elem_flows="http://footprinted.org/assets/data/es1_elem_flows.rdf"
			  xmlns:ilcd_elem_flows="http://footprinted.org/assets/data/ilcd_elem_flows.rdf"
			  xmlns:es1_compartments="http://footprinted.org/assets/data/es1_compartments.rdf"
			  xmlns:ilcd_compartments="http://footprinted.org/assets/data/ilcd_compartments.rdf"
			  xmlns:dc="http://purl.org/dc/elements/1.1/">
			
			<elementalFlows
				xmlns="http://www.EcoInvent.org/EcoSpold02"
				xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
				xsi:schemaLocation="http://www.EcoInvent.org/EcoSpold02 ../../Schemas/MasterData/EcoSpold02Compartments.xsd"
				contextId="DE659012-50C4-4e96-B54A-FC781BF987AB"
				majorRelease="1"
				minorRelease="0">';
			if (($handle = fopen("assets/data/ES2_ELEM_FLOWS.csv", "r")) !== FALSE) {
			    while (($data = fgetcsv($handle, 1000, ",", '"')) !== FALSE) {
					$output .= '<elementalFlow rdf:id="' . $data[0] . '">
						<CAS>' . $data[1] . '</CAS>
						<formula>' . $data[2] . '</formula>
						<name>' . str_replace("<", "less than", $data[3]) . '</name>						
						<unitId rdf:resource="es2_units#unit">
						' . $data[4] . '
						</unitId>
						<compartmentId rdf:resource="es2_compartments#compartment">
						' . $data[4] . '
						</compartmentId>';
						//foreach (array_keys($es1, $data[0]) as $key) {
						//	$output .= '<owl:sameAs rdf:resource="es1_elem_flows#' . $key . '" />';
						//}
						foreach (array_keys($ilcd, $data[0]) as $key) {
							$output .= '<owl:sameAs rdf:resource="ilcd_elem_flows#' . $key . '" />';
							foreach (array_keys($es1, $key) as $key2) {
								$output .= '<owl:sameAs rdf:resource="es1_elem_flows#' . $key2 . '" />';

							}
						}
					$output .= '</elementalFlow>';	
			    }	
			}
			fclose($handle);
			$output .= "</elementalFlows></rdf:RDF>";
			header('Content-Type: text/xml');
			print $output;
	}
	
	
	
	public function units() {
		$es1 = array();
		$ilcd = array();
			if (($handle = fopen("assets/data/UNIT_MAP_ES1_TO_ILCD.csv", "r")) !== FALSE) {
			    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$es1[$data[0]] = $data[1];
				}
			}
		    fclose($handle);
			if (($handle = fopen("assets/data/UNIT_MAP_ILCD_TO_ES2.csv", "r")) !== FALSE) {
			    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$ilcd[$data[0]] = $data[1];
				}
			}
		    fclose($handle);
			$output = '<?xml version="1.0" encoding="UTF-8"?>
			<rdf:RDF
			  xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
			  xmlns:owl="http://www.w3.org/2002/07/owl#"
			  xmlns:es1_elem_flows="http://footprinted.org/assets/data/es1_elem_flows.rdf"
			  xmlns:ilcd_elem_flows="http://footprinted.org/assets/data/ilcd_elem_flows.rdf"
			  xmlns:es1_compartments="http://footprinted.org/assets/data/es1_compartments.rdf"
			  xmlns:ilcd_compartments="http://footprinted.org/assets/data/ilcd_compartments.rdf"
			  xmlns:dc="http://purl.org/dc/elements/1.1/">
			
			<units
				xmlns="http://www.EcoInvent.org/EcoSpold02"
				xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
				xsi:schemaLocation="http://www.EcoInvent.org/EcoSpold02 ../../Schemas/MasterData/EcoSpold02Compartments.xsd"
				contextId="DE659012-50C4-4e96-B54A-FC781BF987AB"
				majorRelease="1"
				minorRelease="0">';
			if (($handle = fopen("assets/data/ES2_UNITS.csv", "r")) !== FALSE) {
			    while (($data = fgetcsv($handle, 1000, ",", '"')) !== FALSE) {
					$output .= '<unit rdf:id="' . $data[0] . '">
						<name>' . str_replace("<", "less than", $data[1]) . '</name>';
						foreach (array_keys($ilcd, $data[0]) as $key) {
							$output .= '<owl:sameAs rdf:resource="ilcd_units#' . $key . '" />';
							foreach (array_keys($es1, $key) as $key2) {
								$output .= '<owl:sameAs rdf:resource="es1_units#' . $key2 . '" />';

							}
						}
					$output .= '</unit>';	
			    }	
			}
			fclose($handle);
			$output .= "</units></rdf:RDF>";
			header('Content-Type: text/xml');
			print $output;
	}	
}
?>
