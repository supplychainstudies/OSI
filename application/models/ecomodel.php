<?php
/**
 * This model uses the Arc2 library to insert, edit, and retrieve rdf data from the arc store 
 * 
 * @package opensustainability
 * @subpackage models
 */
 
class Ecomodel extends FT_Model{
     
    /**
     * @ignore
     */
    function Ecomodel(){
        parent::__construct();
 
    }
     
    public function getAllImpactCategories() {
        return $this->arc_getAllImpactCategories();
         
    }
     
    public function getImpactCategoryMenu() {
        $categories = $this->arc_getAllImpactCategories();
        $menu_html = '<input name="impacts_field" type="hidden" />';
        $menus = array();
        $menus['main'] = "";
        foreach ($categories as $category){
            $menus['main'] .= '<option value="' . $category['uri'] . '">' . $category['label'] . '</option>';
            $menus[$category['label']] = "";
            $indicators = $this->arc_getImpactCategoryIndicators($category['uri']);
            foreach ($indicators as $indicator) {
                $menus[$category['label']] .= '<option value="' . $indicator['uri'] . '">' . $indicator['label'] . '</option>';
            }
        }
        foreach ($menus as $key=>$menu) {
            $show = "";
            if ($key == "main") {
                $show = " show";
            }
            $menu_html .= '<select class="hide' . $show . '" name="impacts_' . $key . '">' . $menu . '</select>';
            if ($key == "main") {
                $menu_html .= '<br />';
            }
        }
        return '<div class="dialog" id="impacts_dialog">' . $menu_html . '</div>';
         
    }
     
    private function arc_getAllImpactCategories() {
        $q = "select ?uri ?label where { " . 
            "?uri '" . $this->arc_config['ns']['rdf'] . "type' '" . $this->arc_config['ns']['eco'] . "ImpactCategory' . " . 
            "?uri '" . $this->arc_config['ns']['rdfs'] . "label' ?label . " . 
            "}";
             
        $results = $this->executeQuery($q, "remote");
        if (count($results) != 0) {
            return $results;
        } else {
            return false;
        }
    }
 
    private function arc_getImpactCategoryIndicators($uri) {
        $q = "select ?uri ?label where { " . 
            "?uri '" . $this->arc_config['ns']['rdf'] . "type' '" . $this->arc_config['ns']['eco'] . "ImpactAssessmentMethodCategoryDescription' . " . 
            "?uri '" . $this->arc_config['ns']['eco'] . "hasImpactCategory' '" . $uri . "' . " . 
            "?uri '" . $this->arc_config['ns']['rdfs'] . "label' ?label . " . 
            "}";
 
        $results = $this->executeQuery($q, "remote");
        if (count($results) != 0) {
            return $results;
        } else {
            return false;
        }
    }

	public function makeToolTip($uri, $tooltips) {
		if (isset($tooltips[$uri]) != true) {
			if (strpos($uri,":") !== false) {
				$tooltips[$uri]['label'] = $this->getLabel($uri,"remote");	
				$tooltips[$uri]['l'] = $tooltips[$uri]['label'];
			} 
		}
	}
     
}