<?php
/**
 * Controller for caching in database
 * 
 * @version 0.8.0
 * @author info@footprinted.org
 * @package opensustainability
 * @subpackage controllers
 * @uses 
 */

class Cache extends FT_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model(Array('lcamodel', 'geographymodel', 'bibliographymodel','peoplemodel','commentsmodel','ecomodel'));	
		$this->load->library(Array('form_extended', 'xml'));
		$obj =& get_instance();    
        $obj->load->library(array('xml'));
        $this->ci =& $obj;
		$this->check_if_admin();
	}
	public $URI;
	public $data;
	public $post_data;


	// See if there is any new footprints and then save the URI and Name in the cache table
	public function cacheNames(){
		$records = $this->lcamodel->getRecords();
		// Initializing array
		foreach ($records as $r) {
			$uri = str_replace("http://footprinted.org/rdfspace/lca/", "",$r['uri']); 
			$longuri = "http://footprinted.org/rdfspace/lca/" . $uri;
			$title = $this->lcamodel->getTitle($longuri);
			//Search if it's in the db
			$this->db->where('uri', $uri); 
			$rs = $this->db->get('footprints');
			if($rs->result() == false){
				$data = array (
					'uri' => $uri,
					'name' => $title,
				);
				$this->db->insert('footprints', $data);
			}
		}
	}


			public function cacheImpactsToMasterDB(){
				$this->db->like('category','transportation');
				$rs = $this->db->get('footprints');	
				// Go through all the lcas
				foreach ($rs->result() as $r) {
					$this->db->where('uri',$r->uri);
					$this->db->where('impact',"Carbon Dioxide Equivalent");
					$impact = $this->db->get('impacts',1,0);
					$amount = NULL;
					foreach ($impact->result()as $i) { $amount = $i->amount;}
					$data = array (
						'co2e' => $amount
					);
					

					$this->db->where('uri', $r->uri);
					$this->db->update('footprints', $data);
				}
			}
		public function removeImpacts(){
			$this->db->like('category','transportation');	
			$rs = $this->db->get('footprints');	
			// Go through all the lcas
			foreach ($rs->result() as $r) {
				// Remove existing impacts
				$this->db->where('uri',$r->uri);
				$impacts = $this->db->get('impacts');
				foreach ($impacts->result() as $i) {
					$this->db->where('id',$i->id);
					$this->db->delete('impacts');
				}
			}
		}
			public function cacheImpacts(){
				 $this->db->like('category','transportation');	
				$rs = $this->db->get('footprints');	
				// Go through all the lcas
				foreach ($rs->result() as $r) {
					// Get the impacts
					$uri = $r->uri;
					$parts['impactAssessments'] = $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments("http://footprinted.org/rdfspace/lca/" . $uri));
					$parts['quantitativeReference'] = $this->lcamodel->convertQR($this->lcamodel->getQR("http://footprinted.org/rdfspace/lca/" . $uri));
					/* Normalize to 1 */
					$this->normalize($parts);
					foreach ($parts['impactAssessments'] as &$impactAssessment) {
						// Create an impact
						$data = array (
							'uri' => $r->uri,
							'amount' => $impactAssessment['amount'],
							'unit' => $impactAssessment['unit']['label'],
							'impact' => $impactAssessment['impactCategoryIndicator']['label'],
							);
						$this->db->insert('impacts', $data);
					}
					// Update the unit for the footprint
					$data2 = array (
						'unit' => $parts['quantitativeReference']['unit']['label']
					);
					$this->db->where('uri', $uri);
					$this->db->update('footprints', $data2);
				}
			}

			public function cacheCategories(){
				$rs = $this->db->get('footprints');		
				// Initializing array
				foreach ($rs->result() as $r) {
					$uri = str_replace("http://footprinted.org/rdfspace/lca/", "",$r->uri); 
					$longuri = "http://footprinted.org/rdfspace/lca/" . $uri;
					$categoryOf = $this->lcamodel->getCategories($longuri);
					$category= "";
					if($categoryOf != false){
						foreach ($categoryOf as $c) {
							$category .= $c['uri'] . ';';
						}
					}
					$data = array (
						'category' => $category
					);
					$this->db->where('uri', $uri);
					$this->db->update('footprints', $data);
				}
			}

			public function cacheEverything(){ 
				// Querying the database for all records		
				$records = $this->lcamodel->getRecords();
				// Initializing array
				foreach ($records as $r) {
						$uri = str_replace("http://footprinted.org/rdfspace/lca/", "",$r['uri']); 
						$longuri = "http://footprinted.org/rdfspace/lca/" . $uri;
						$impactAssessments = $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments($longuri));
						$bibliography = $this->bibliographymodel->convertBibliography($this->bibliographymodel->getBibliography($longuri));
						$geography = $this->lcamodel->convertGeography($this->lcamodel->getGeography($longuri));
						$quantitativeReference = $this->lcamodel->convertQR($this->lcamodel->getQR($longuri));
						$categoryOf = $this->lcamodel->getCategories($longuri);

						// Get the year
						$year= "";
						foreach ($bibliography as $b) { 
							$year = substr_replace($b['date'], '', 4); 
							$ref = $b["title"] . "Authors: ";
							if (isset($b['authors']) == true) {
								foreach ($b['authors'] as $author) {
									$ref .=  $author['lastName'] . ", " .$author['firstName'] . "; ";
								}
							}
						}
						$category= "";
						if($categoryOf != false){
							foreach ($categoryOf as $c) {
								$category = $c['label'];
							}
						}
						// Get the country
						$country= "";
						if($geography != false){
							foreach ($geography as $g) { $country = $g['name']; }
						}					
						/* Normalize to 1 */
						$ratio = $quantitativeReference['amount'];					
						foreach ($impactAssessments as $impact) {
							if($impact['impactCategoryIndicator']['label'] == "Carbon Dioxide Equivalent"){
								//Normalize to one
								$co2 = $impact['amount'] / $ratio;
								$unit = $quantitativeReference['unit']['label'];
								//Change unit
								if (strpos("Gram", $impact['unit']['label']) !== false) {
									$co2 = $co2*1000;
								}
								if ($impact['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Pound" ) {
									$co2 = $co2*0.45359237;
								}
								if (strpos("Gram", $quantitativeReference['unit']['label']) !== false) {
									$unit = "Kilogram";
									$co2 = $co2/1000;
								}
								if (strpos("Kilogram", $quantitativeReference['unit']['label']) !== false) {
									$unit = "Kilogram";
								}
								if ($quantitativeReference['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Ounce" ) {
									$unit = "Kilogram";
									$co2 = $co2/0.028345;
								}
								if ($quantitativeReference['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Pound" ) {
									$unit = "Kilogram";
									$co2 = $co2/0.45359237;
								}
							}

							if($impact['impactCategoryIndicator']['label'] == "Water"){
								//Normalize to one
								$water = $impact['amount'] / $ratio;
								$unit = $quantitativeReference['unit']['label'];
								//Change unit
								if (strpos("Gram", $quantitativeReference['unit']['label']) !== false) {
									$unit = "Kilogram";
									$water = $water/1000;
								}
								if (strpos("Kilogram", $quantitativeReference['unit']['label']) !== false) {
									$unit = "Kilogram";
								}
								if ($quantitativeReference['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Ounce" ) {
									$unit = "Kilogram";
									$water = $water/0.028345;
								}
								if ($quantitativeReference['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Pound" ) {
									$unit = "Kilogram";
									$water = $water/0.45359237;
								}
							}						

							if($impact['impactCategoryIndicator']['label'] == "Energy"){
								//Normalize to one
								$energy = $impact['amount'] / $ratio;
								$unit = $quantitativeReference['unit']['label'];
								//Change unit
								if (strpos("Gram", $quantitativeReference['unit']['label']) !== false) {
									$unit = "Kilogram";
									$energy = $energy/1000;
								}
								if (strpos("Kilogram", $quantitativeReference['unit']['label']) !== false) {
									$unit = "Kilogram";
								}
								if ($quantitativeReference['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Ounce" ) {
									$unit = "Kilogram";
									$energy = $energy/0.028345;
								}
								if ($quantitativeReference['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Pound" ) {
									$unit = "Kilogram";
									$energy = $energy/0.45359237;
								}
							}

							if($impact['impactCategoryIndicator']['label'] == "Waste"){
								//Normalize to one
								$waste = $impact['amount'] / $ratio;
								$unit = $quantitativeReference['unit']['label'];
								//Change unit
								if (strpos("Gram", $quantitativeReference['unit']['label']) !== false) {
									$unit = "Kilogram";
									$waste = $waste/1000;
								}
								if (strpos("Kilogram", $quantitativeReference['unit']['label']) !== false) {
									$unit = "Kilogram";
								}
								if ($quantitativeReference['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Ounce" ) {
									$unit = "Kilogram";
									$waste = $waste/0.028345;
								}
								if ($quantitativeReference['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Pound" ) {
									$unit = "Kilogram";
									$waste = $waste/0.45359237;
								}
							}
						}

						$data = array (
							'uri' => $uri,
							'name' => $quantitativeReference['name'],
							'unit' => $unit,
							'geography' => $country,
							'year' => $year,
							'co2e' => $co2,						
							'water' => $water,
							'waste' => $waste,
							'energy' => $energy,
							'category' => $category,
							'ref' => $ref
							);
						$this->db->insert('footprints', $data); 
					}


			}

	/* Private function that normalizes to 1 functional unit and to kilograms if possible */
	private function normalize(&$parts){
		/* Normalize to 1 */
		$oldamount = $parts['quantitativeReference']['amount'];
		$ratio = $parts['quantitativeReference']['amount'];
		$parts['quantitativeReference']['amount'] = 1;
		// If grams	
		if (strpos("Gram", $parts['quantitativeReference']['unit']['label']) !== false) {
			$ratio = $oldamount / 1000;
			$parts['quantitativeReference']['unit']['label'] = "Kilogram"; $parts['quantitativeReference']['unit']['abbr'] = "kg";
		}	
		// If ounces
		if ($parts['quantitativeReference']['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Ounce" ) {
			$ratio = $oldamount * 0.028345;
			$parts['quantitativeReference']['unit']['label'] = "Kilogram"; $parts['quantitativeReference']['unit']['abbr'] = "kg";
		}
		// If pounds
		if ($parts['quantitativeReference']['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Pound") {
			$ratio = $oldamount * 0.45359237;
			$parts['quantitativeReference']['unit']['label'] = "Kilogram";$parts['quantitativeReference']['unit']['abbr'] = "kg";
		}
		if ($parts['quantitativeReference']['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Pound") {
			$ratio = $oldamount * 0.45359237;
			$parts['quantitativeReference']['unit']['label'] = "Kilogram";$parts['quantitativeReference']['unit']['abbr'] = "kg";
		}
		if ($parts['quantitativeReference']['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#TableSpoon") {
			$ratio = $oldamount * 0.0147867648;
			$parts['quantitativeReference']['unit']['label'] = "Liter";$parts['quantitativeReference']['unit']['abbr'] = "L";
		}
		if ($parts['quantitativeReference']['unit']['label'] == "Ton Mile") {
			$ratio = $oldamount * 1.609344;
			$parts['quantitativeReference']['unit']['label'] = "Liter";$parts['quantitativeReference']['unit']['abbr'] = "Ton Km";
		}
		if ($parts['quantitativeReference']['unit']['label'] == "Per Person Per Mile") {
			$ratio = $oldamount * 1.609344;
			$parts['quantitativeReference']['unit']['label'] = "Liter";$parts['quantitativeReference']['unit']['abbr'] = "Person Km";
		}
		// Normalizes the flows
		if (isset($parts['exchanges']) == true) {
			foreach ($parts['exchanges'] as &$exchanges) {
				$exchanges['amount'] = $exchanges['amount'] / $ratio;
				if ($exchanges['unit']['label'] == "Gram") {
					$exchanges['amount']/=1000; $exchanges['unit']['label'] = "Kilogram"; $exchanges['unit']['abbr'] = "kg";
				}
				if ($exchanges['unit']['label'] == "Pound Mass") {
					$exchanges['amount'] = $exchanges['amount'] * 0.45359237; 
					$exchanges['unit']['label'] = "Kilogram"; $exchanges['unit']['abbr'] = "kg";
					}
				}
			}
			// Normalizes the impacts
			if (isset($parts['impactAssessments']) == true) {
				foreach ($parts['impactAssessments'] as &$impactAssessment) {
					$impactAssessment['amount'] = $impactAssessment['amount'] / $ratio;
					if ($impactAssessment['unit']['label'] == "Gram") { 
						$impactAssessment['amount']/=1000; 
						$impactAssessment['unit']['label'] = "Kilogram"; $impactAssessment['unit']['abbr'] = "kg";
					}
					if ($impactAssessment['unit']['label'] == "Pound Mass") { 
						$impactAssessment['amount']*=0.45359237; 
						$impactAssessment['unit']['label'] = "Kilogram"; $impactAssessment['unit']['abbr'] = "kg";
					}
				}
			}
		}

	} // End Class