<?php

class Unitmodel extends FT_Model{
	function Unitmodel(){
		parent::__construct();
		$this->arc_config['store_name'] = "qudt";
	}
	// Adds human legible labels to units
	public function makeToolTip($uri) {
		$this->arc_config['store_name'] = "qudt";
		if ($uri == "http://data.nasa.gov/qudt/owl/unit#Kilogram"){
			$tooltips = array();
			$tooltips['label'] = "Kilogram";	
			$tooltips['abbr'] = "kg";
			$tooltips['l'] = "Kilogram";
			$tooltips['quantityKind'] = "Mass";
		}else{
			if (strpos($uri,":") !== false) {
				$tooltips = array();
				$tooltips['label'] = $this->getLabel($uri);	
				$tooltips['abbr'] = $this->getAbbr($uri);
				$tooltips['l'] = $tooltips['abbr'];
				$tooltips['quantityKind'] = $this->getQuantityKind($uri);
			}else{
				$tooltips = array();
				$tooltips['label'] = $uri;	
				$tooltips['abbr'] = $uri;
				$tooltips['l'] = $uri;
				$tooltips['quantityKind'] = "";
			}
		}
		return $tooltips;
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
		$q = "select DISTINCT ?uri ?label where { " .
			"?uri qudt:quantityKind ".$object." . " . 	
			"?uri rdfs:label ?label . " . 	
			"?uri rdf:type ?type . " . 	
			"FILTER regex(?type, '" . $this->arc_config['ns']['qudt'] . "', 'i')" . 		
			"}";		
		$results = $this->executeQuery($q);
		if (count($results) != 0) {
			return $results;
		} else {
			return false;
		}			
	}
	
	
	public function getAbbr($uri) {
		return $this->getSomething($uri, "qudt:abbreviation");	
	}

	public function getURIbyAbbr($abbr) {
		$q = "SELECT ?uri where { ?uri qudt:abbreviation '".$abbr."' }";
		$results = $this->executeQuery($q);
		if (count($results) != 0) {
			return $results[0]['uri'];
		} else {
			return false;
		}
	}

	public function getQuantityKind($uri) {
		$kind_uri =  $this->getSomething($uri, "qudt:quantityKind");
		return $this->getLabel($kind_uri);	
	}	
	
	public function getUnitMenu() {
		$units = $this->getUnits();
		$menu_html = '<form id="unit_form">';
        $menu_html .= '<input name="unit_field" type="hidden" />';
        $menus = array();
        $menus['main'] = "";
        foreach ($units as $unit_category=>$unit_set){
            $menus['main'] .= '<option value="' . $unit_category . '">' . $unit_category . '</option>';
            $menus[$unit_category] = "";
            foreach ($unit_set as $unit) {
                $menus[$unit_category] .= '<option value="' . $unit['uri'] . '">' . $unit['label'] . '</option>';
            }
        }
		unset($units);
        foreach ($menus as $key=>$menu) {
            $show = "";
            if ($key == "main") {
                $show = " show";
            }
            $menu_html .= '<select class="hide' . $show . '" name="unit_' . $key . '">' . $menu . '</select>';
            if ($key == "main") {
                $menu_html .= '<br />';
            }
        }
		unset($menus);
		$menu_html .= '<div style="width:360px"><input type="submit" class="button" id="unit_submit" value="save" style="margin-top:25px;width:80px;height:40px;font-size:16px;"/></div></form>';		
        return '<div class="dialog" id="unit_dialog">' . $menu_html . '</div>';
	}
	
} // End Class