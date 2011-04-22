<?php
include_once('arcremotemodel.php');
/**
 * This model uses the Arc2 library to insert, edit, and retrieve rdf data from the arc store 
 * 
 * @package opensustainability
 * @subpackage models
 */

class Unitmodel extends ArcRemoteModel{
	
	/**
	 * @ignore
	 */
	function Unitmodel(){
		parent::arcremotemodel();

	}
	
	public function getUnits() {
	/***
    * @public
    * Generates a form, or, in the case where post data is passed, submits the data to the DB
    */
		$quantityKinds = array(
			'DataRate',
			'Area',
			'Mass',
			'LiquidVolume',
			'Volume',
			'ThermalEnergy',
			'Power',
			'ElectricCurrent',
			'ElectricCharge',
			'EnergyAndWork'	
		);
		foreach ($quantityKinds as $quantityKind) {
			$results[$quantityKind] = $this->getQuantityKinds("qudtq:".$quantityKind);
		}
		return $results;
	}
	
	
	
} // End Class