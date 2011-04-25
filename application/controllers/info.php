<?php
/**
 * Controller for environmental information structures
 * 
 * @version 0.8.0
 * @author info@opensustainability.info
 * @package opensustainability
 * @subpackage controllers
 * @uses 
 */

class Info extends SM_Controller {
	public function Info() {
		parent::SM_Controller();
		$this->load->model(Array('arcmodel', 'arcremotemodel', 'mysqlmodel'));	
		$this->load->library(Array('form_extended', 'name_conversion'));
		$this->load->helper(Array('lcaformat_helper'));
	}
	public $URI;
	public $data;
	public $post_data;

	/***
    * @public
    * Shows the homepage
	* This is not functional for non-LCA entries and does not have search or filter capabilities yet
    */
	// Public function for exploring the repository
	public function browse() {
		
		
		// Querying the database for all records		
		@$records = @$this->arcmodel->getRecords();
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
				if (strpos($field, "dbpedia") !== false) {
					$set[$key][$_key] = @$this->getLabel($field, 'rdfs:type');		
				} else {
					$set[$key][$_key] = $field;
				}
			}
			
			
			/*/ Get all the Impacts 		
			@$impacts = $this->arcmodel->getImpacts($record['link']);
			// For each impact
			foreach ($impacts as $impact) {
				// Create a list of all the distinct impact categories
				if (in_array($impact['impactCategory'], $impact_categories) == false) {
					$impact_categories[] = $impact['impactCategory'];
				}		
				// append impacts to the correct record in the set variable
				foreach ($impact as $__key => $_field) {
					// if its a uri, get the label and store that instead
					// rewrite this into a better function later
					if (strpos($_field, "dbpedia") !== false) {
						@$set[$key][$impact['impactCategory']][$__key] = $this->getLabel($_field, 'rdfs:type');
					} else {
						$set[$key][$impact['impactCategory']][$__key] = $_field;
					}					
				}
			}*/				
		
		}
		$featured = $this->arcmodel->simplesearch("aluminum",1,0);
		foreach ($featured as $feature_uri) {
	    	$feature_info = array (
	              'uri' => $feature_uri,
	               'impactAssessments' => convertImpactAssessments(@$this->arcmodel->getImpactAssessments($feature_uri)),
	               'quantitativeReference' => convertQR(@$this->arcmodel->getQR($feature_uri))
	               );
	    }
		if ($feature_info['quantitativeReference']['unit'] == "qudtu:Kilogram") {
			$ratio = $feature_info['quantitativeReference']['amount'];
			$feature_info['quantitativeReference']['amount'] = 1;
			foreach ($feature_info['impactAssessments'] as &$impactAssessment) {
				$impactAssessment['amount'] = $impactAssessment['amount'] / $ratio;
			}
		}
	
		@$feature_uri['tooltips'] = $this->tooltips;
			//Load RSS for news
			 $this->load->library('RSSParser', array('url' => 'http://twitter.com/statuses/user_timeline/footprinted.rss', 'life' => 0));
			  //Get six items from the feed
			  $twitter = $this->rssparser->getFeed(6);			
		
		
		// Send data to the view
		$this->data("set", $set);
		$this->data("twitter", $twitter);
		$this->data("feature_info", $feature_info);
		$this->display("Browse","browse_view");		
	}

	/***
    * @home
    * Shows the provisional homepage
    */
	// Homepage for private beta
	public function index() {
		//Load RSS for news
		$this->load->library('RSSParser', array('url' => 'http://twitter.com/statuses/user_timeline/footprinted.rss', 'life' => 0));
		//Get six items from the feed
		$twitter = $this->rssparser->getFeed(6);			
		// Send data to the view
		$this->data("twitter", $twitter);
		// Send data to the view
		$this->display("Home","home_view");		
	}
}
