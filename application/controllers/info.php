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
			
		// Querying the database for all featured URIs		
		$this->db->select('uri');
		$this->db->order_by("uri", "ASC"); 
		$featured = $query = $this->db->get('featured');
		// Initializing array
		$set = array();

		foreach ($featured->result() as $feature) {
				$uri = "http://footprinted.org/rdfspace/lca/".$feature->uri;
				$reference = $this->lcamodel->convertQR($this->lcamodel->getQR($uri));
				$impactAssessments = $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments($uri));
				$co2= 0;
				$water = 0;
				$ratio = $reference['amount'];
					foreach ($impactAssessments as $impact) {
					if($impact['impactCategoryIndicator'] == 'Carbon Dioxide Equivalent'){
						$co2 = $impact['amount'] / $ratio;
						if($impact['unit']=="qudtu:Gram"){ $co2 /= 1000; }
					}
					if($impact['impactCategoryIndicator'] == 'Water'){
						$water = $impact['amount'] / $ratio;
						if($impact['unit']=="qudtu:Gram"){ $water /= 1000; }
					}
				}
	    		$set[$uri] = array (
	               'uri' => $uri,
				   'categories' => $this->lcamodel->getCategories($uri),
	               'quantitativeReference' => $reference,
				   'co2' => $co2,
					'water' => $water
				);
	    }		
		// Send data to the view
		$this->data("set", $set);
		//$this->data("twitter", $twitter);
		$this->display("Browse","featured_view");		
	}
	
	/***
    * @public
    * Shows the homepage
	* This is not functional for non-LCA entries and does not have search or filter capabilities yet
    */
	// Public function for exploring the repository
	public function browse() {
			
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
