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



class Endpoint extends SM_Controller {
	public function Endpoint() {
		parent::SM_Controller();
			$this->load->library('arc2/ARC2', '', 'arc');			
		}	

		// Configuration information for accessing the arc store
		public $osi_config = array(
		  /* db */
		  'db_host' => 'localhost', /* default: localhost */
		  'db_name' => 'opensustainability',
		  'db_user' => 'root',
		  'db_pwd' => 'root',
		  /* store */
		'store_name' => 'arc_os',
		'ns' => array(
		'lca' => 'http://opensustainability.info/vocab#',
	    'dcterms' => 'http://purl.org/dc/terms/',
		'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
		'sioc' => 'http://rdfs.org/sioc/ns',
		'ISO14048' => 'http://opensustainability.info/vocab#',
		'ecospold' => 'http://',
		'earthster' => '',
		'elcd' => ''
	   ),		    
		  'endpoint_features' => array(
		    'select', 'construct', 'ask', 'describe', // allow read
		    'load', 'insert', 'delete',               // allow update
		    'dump'                                    // allow backup
		  )

		);
		/**
		 * This function is a generic call to the arc store.
		 * @return Array of triples.
		 * @param $q string - query string.
		 */
		public function index() {
			/* instantiation */
			@$ep = $this->arc->getStoreEndpoint($this->osi_config);

			if (!$ep->isSetUp()) {
			  @$ep->setUp(); /* create MySQL tables */
			}

			/* request handling */
			@$ep->go();
		}
	

}
