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

class Info extends FT_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model(Array('lcamodel','unitmodel'));
		$this->load->library(Array('form_extended'));
		//$this->load->helper(Array('lcaformat_helper'));
	}
	public $URI;
	public $data;
	public $post_data;
	public $tooltips = array();
	
	/***
    * @public
    * Shows the homepage
	* This is not functional for non-LCA entries and does not have search or filter capabilities yet
    */
	// Public function for exploring the repository
	public function featured() {		
		$this->check_if_admin();
		// Querying the database for all featured URIs		
		$this->db->select('uri');
		$this->db->order_by("uri", "ASC"); 
		$featured = $query = $this->db->get('featured');
		// Initializing array
		$set = array();

		foreach ($featured->result() as $feature) {
				$uri = $feature->uri;
				$set[$uri]['uri'] = $uri;
				$set[$uri]['impactAssessments'] = $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments("http://footprinted.org/rdfspace/lca/" . $uri));
				$set[$uri]['bibliography'] = $this->bibliographymodel->convertBibliography($this->bibliographymodel->getBibliography("http://footprinted.org/rdfspace/lca/" . $uri));
				$set[$uri]['modeled'] = $this->lcamodel->convertModeled($this->lcamodel->getModeled("http://footprinted.org/rdfspace/lca/" . $uri));
				$set[$uri]['geography'] = $this->lcamodel->convertGeography($this->lcamodel->getGeography("http://footprinted.org/rdfspace/lca/" . $uri));
				$set[$uri]['quantitativeReference'] = $this->lcamodel->convertQR($this->lcamodel->getQR("http://footprinted.org/rdfspace/lca/" . $uri));
				$set[$uri]['sameAs'] = $this->lcamodel->convertLinks($this->lcamodel->getSameAs("http://footprinted.org/rdfspace/lca/" . $uri));
				$set[$uri]['categoryOf'] = $this->lcamodel->getCategories("http://footprinted.org/rdfspace/lca/" . $uri);
			 	foreach ($set[$uri] as $key=>$part) {
					if ($parts[$key] === false || $parts[$key] == false || count($parts[$key]) == 0) {
						unset($parts[$key]);
					}
				}

				/* If the functional unit is mass, normalize to 1kg */
				if (strpos("Kilogram", $set[$uri]['quantitativeReference']['unit']['label']) !== false) {
					$ratio = $set[$uri]['quantitativeReference']['amount'];
					$set[$uri]['quantitativeReference']['amount'] = 1;
					foreach ($set[$uri]['impactAssessments'] as &$impactAssessment) {
						$impactAssessment['amount'] = $impactAssessment['amount'] / $ratio;
					}
				}
				// If grams
				if (strpos("Gram", $set[$uri]['quantitativeReference']['unit']['label']) !== false) {
					$ratio = $set[$uri]['quantitativeReference']['amount'] / 1000;
					// Remember to complete afterwards
					$set[$uri]['quantitativeReference']['unit']['label'] = "Kilogram";
					$set[$uri]['quantitativeReference']['amount'] = 1;
					foreach ($set[$uri]['impactAssessments'] as &$impactAssessment) {
						$impactAssessment['amount'] = $impactAssessment['amount'] / $ratio;
						if (strpos("Gram", $impactAssessment['unit']['label']) !== false) { $impactAssessment['amount']/=1000; $impactAssessment['unit']['label'] = "Kilogram"; }
					}
				}
				// If ounces
				if ($set[$uri]['quantitativeReference']['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Ounce" ) {
					$ratio = $set[$uri]['quantitativeReference']['amount'] / 0.028345;
					// Remember to complete afterwards
					$set[$uri]['quantitativeReference']['unit']['label'] = "Kilogram";
					$set[$uri]['quantitativeReference']['amount'] = 1;
					foreach ($set[$uri]['impactAssessments'] as &$impactAssessment) {
						$impactAssessment['amount'] = $impactAssessment['amount'] / $ratio;
						if ($impactAssessment['unit']['label'] == "Gram") { $impactAssessment['amount']/=1000; $impactAssessment['unit']['label'] = "Kilogram"; }
					}
				}
				// If pounds
				if ($set[$uri]['quantitativeReference']['unit']['label'] == "http://data.nasa.gov/qudt/owl/unit#Pound" ) {
					$ratio = $parts['quantitativeReference']['amount'] / 0.45359237;
					// Remember to complete afterwards
					$set[$uri]['quantitativeReference']['unit']['label'] = "Kilogram";
					$set[$uri]['quantitativeReference']['amount'] = 1;
					foreach ($set[$uri]['impactAssessments'] as &$impactAssessment) {
						$impactAssessment['amount'] = $impactAssessment['amount'] / $ratio;
						if ($impactAssessment['unit']['label'] == "Gram") { $impactAssessment['amount']/=1000; $impactAssessment['unit']['label'] = "Kilogram"; }
					}
				}
				
	    }		
		// Send data to the view
		$this->data("set", $set);
		//$this->data("twitter", $twitter);
		$this->display("Browse","homapage_view");		
	}
	
	/***
    * @public
    * Shows the homepage
	* This is not functional for non-LCA entries and does not have search or filter capabilities yet
    */
	// Public function for exploring the repository
	public function browse() {
		$this->check_if_admin();
		// Querying the database for all records		
		$records = $this->lcamodel->getRecords();
		// Initializing array
		$set = array();
		// Add tooltips
		$this->tooltips = array();

		// Filling the arry with the records
		foreach ($records as $key => $record) {	
			// Go through each field
			foreach ($record as $_key => $field) {
				// if its a uri, get the label and store that instead 
				// rewrite this into a better function later
					$set[$key][$_key] = $field;
			}
		}
		$featured = $this->lcamodel->simplesearch("aluminum",1,0);
		foreach ($featured as $feature) {
	    	$feature_info = array (
	              'uri' => $feature['uri'],
	               'impactAssessments' => $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments($feature['uri'])),
	               'quantitativeReference' => $this->lcamodel->convertQR($this->lcamodel->getQR($feature['uri']))
	               );
	    }
		if ($feature_info['quantitativeReference']['unit'] == "qudtu:Kilogram") {
			$ratio = $feature_info['quantitativeReference']['amount'];
			$feature_info['quantitativeReference']['amount'] = 1;
			foreach ($feature_info['impactAssessments'] as &$impactAssessment) {
				$impactAssessment['amount'] = $impactAssessment['amount'] / $ratio;
			}
		}
			//Load RSS for news
		//$this->load->library('RSSParser', array('url' => 'http://twitter.com/statuses/user_timeline/footprinted.rss', 'life' => 0));
			  //Get six items from the feed

		//$twitter = $this->rssparser->getFeed(6);			
		
		// Send data to the view
		$this->data("set", $set);
		//$this->data("twitter", $twitter);
		$this->display("Browse","browse_view");		
	}

	/***
    * @home
    * Shows the provisional homepage
    */
	// Homepage for private beta
	public function index() {
		//Load RSS for news
		//$this->load->library('RSSParser', array('url' => 'http://twitter.com/statuses/user_timeline/footprinted.rss', 'life' => 0));
		//Get six items from the feed
		//$twitter = $this->rssparser->getFeed(6);			
		// Send data to the view
		//$this->data("twitter", $twitter);
		// Send data to the view
		$this->display("Home","home_view");		
	}
	
	
}
