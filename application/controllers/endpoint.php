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



class Endpoint extends FT_Controller {
	public function Endpoint() {
		parent::__construct();
			$this->load->library('arc2/ARC2', '', 'arc');	
			$this->config->load('arc');	
			$this->config->load('arcdb');	
			$this->arc_config = array_merge($this->config->item("arc_info"), $this->config->item("db_arc_info"));	
			}	
		/**
		 * This function is a generic call to the arc store.
		 * @return Array of triples.
		 * @param $q string - query string.
		 */
		public function i($store="footprinted") {
			/* instantiation */
			$this->arc_config['store_name']=$store;
			@$ep = $this->arc->getStoreEndpoint($this->arc_config);

			if (!$ep->isSetUp()) {
			  @$ep->setUp(); /* create MySQL tables */
			}

			/* request handling */
			@$ep->go();
		}
		
}
