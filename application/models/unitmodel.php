<?php

class Unitmodel extends FT_Model{
	function Unitmodel(){
		parent::__construct();
		$this->arc_config['store_name'] = "qudt";
	}
	
	public function makeToolTip($uri, $tooltips) {
		$this->arc_config['store_name'] = "qudt";
		if (isset($tooltips[$uri]) != true) {
			if (strpos($uri,":") !== false) {
				$tooltips[$uri] = array();
				$tooltips[$uri]['label'] = $this->getLabel($uri);	
				$tooltips[$uri]['abbr'] = $this->getAbbr($uri);
				$tooltips[$uri]['l'] = $tooltips[$uri]['abbr'];
				$tooltips[$uri]['quantityKind'] = $this->getQuantityKind($uri);				
				if ($tooltips[$uri]['l'] == false) { 
					$uri_parts = explode(":", $uri);
					return $uri_parts[1];
				} 
			} 
		}
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
	
	public function getQuantityKinds($object) {
		if (strpos($object,":") !== false) {
			$xarray = explode(":", $object);
			$the_object = $this->arc_config['ns'][$xarray[0]] . $xarray[1];
		} elseif (strpos($object,"http://") !== false) {
			$the_object = $object;
		} else {
			$the_object = $object;
		}
		
		$q = "select DISTINCT ?uri ?label where { " .
			"?uri '" . $this->arc_config['ns']['qudt'] . "quantityKind' '" . $the_object . "' . " . 	
			"?uri '" . $this->arc_config['ns']['rdfs'] . "label' ?label . " . 	
			"?uri '" . $this->arc_config['ns']['rdf'] . "type' ?type . " . 
			//"?type '" . $this->arc_config['ns']['rdfs'] . "subClassOf' ?stuf . " . 	
			"FILTER regex(?type, '" . $this->arc_config['ns']['qudt'] . "', 'i')" . 		
			"}";
		$results = $this->executeQuery($q,"remote");
		if (count($results) != 0) {
			return $results;
		} else {
			return false;
		}			
	}
	
	
	public function getAbbr($uri) {
		return $this->getSomething($uri, "qudt:abbreviation");	
	}

	public function getQuantityKind($uri) {
		$kind_uri =  $this->getSomething($uri, "qudt:quantityKind");
		return $this->getLabel($kind_uri);	
	}	
	
} // End Class