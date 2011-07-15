<?php
class Searchtablemodel extends FT_Model{
    public function Searchtablemodel(){
        parent::__construct();
		$this->load->model(Array('lcamodel','unitmodel','geographymodel','ecomodel','opencycmodel','dbpediamodel','bibliographymodel'));
    }
	var $CI;

	public function addToSearchTable($uri){
		// Add to db search
		// uri, name, unit, year, country, co2e, water,	energy, waste, category, ref, public
		$whole_uri = 'http://footprinted.org/rdfspace/lca/'.$uri;
		$parts = array();
		$parts['impactAssessments'] = $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments($whole_uri));
		$parts['bibliography'] = $this->bibliographymodel->cite_APA($this->bibliographymodel->convertBibliography($this->bibliographymodel->getBibliography($whole_uri)));
		$parts['geography'] = $this->lcamodel->convertGeography($this->lcamodel->getGeography($whole_uri));
		$parts['year'] = $this->lcamodel->getYear($whole_uri);
		$parts['quantitativeReference'] = $this->lcamodel->convertQR($this->lcamodel->getQR($whole_uri));
		$parts['categoryOf'] = $this->lcamodel->getCategories($whole_uri);
		foreach ($parts as $key=>$part) {
			if ($parts[$key] === false || $parts[$key] == false || count($parts[$key]) == 0) {
				unset($parts[$key]);
			}
		}
		$parts = $this->lcamodel->normalize($parts);
		$data = array(
					'uri' => $uri,
					'name' => $parts['quantitativeReference']['name'],
					'unit' =>$parts['quantitativeReference']['unit']['label'],
					'public' => "0",
					'ref' => $parts['bibliography'][0]
				);

		if (isset($parts['geography']['name']) == true) {
			$data['country'] = $parts['geography']['name'];
		}
		if (isset($parts['year']) == true) {
			$data['year'] = $parts['year'];
		}
		$data['category'] = "";
		$cat = $this->lcamodel->getCategories($whole_uri);
		if (isset($cat) == true) {
			if (is_array($cat) == true) {
				foreach ($cat as $category) {
					$data['category'] .= $category['label'].";";
				}
			}
		}

		if (isset($parts['impactAssessments']) == true) {
			foreach ($parts['impactAssessments'] as $ia) {
				if ($ia['impactCategoryIndicator'] = "Carbon Dioxide equivalent") {
					$data['co2e'] = $ia['amount'];
				}
				if ($ia['impactCategoryIndicator'] = "Waste") {
					$data['Waste'] = $ia['amount'];
				}
				if ($ia['impactCategoryIndicator'] = "Water") {
					$data['Water'] = $ia['amount'];
				}
				if ($ia['impactCategoryIndicator'] = "Energy") {
					$data['Energy'] = $ia['amount'];
				}
			}
		}
		$this->CI =& get_instance();
		$this->CI->db->set($data); 

		if(!$this->CI->db->insert('footprints')) //There was a problem! 
			return false;			
	}
}