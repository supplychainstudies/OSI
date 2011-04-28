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



class Endpoint extends SM_Controller {
	public function Endpoint() {
		parent::SM_Controller();
			$this->load->library('arc2/ARC2', '', 'arc');	
			$this->config->load('arc');	
			$this->config->load('arcdb');	
			$this->arc_config = array_merge($this->config->item("arc_info"), $this->config->item("db_arc_info"));	
			$this->arc_lr_config = array_merge($this->config->item("arc_lr_info"), $this->config->item("db_arc_lr_info"));		
		}	
		/**
		 * This function is a generic call to the arc store.
		 * @return Array of triples.
		 * @param $q string - query string.
		 */
		public function index() {
			/* instantiation */
			@$ep = $this->arc->getStoreEndpoint($this->arc_config);

			if (!$ep->isSetUp()) {
			  @$ep->setUp(); /* create MySQL tables */
			}

			/* request handling */
			@$ep->go();
		}
		
		public function remote() {
			@$ep = $this->arc->getStoreEndpoint($this->arc_lr_config);

			if (!$ep->isSetUp()) {
			  @$ep->setUp(); /* create MySQL tables */
			}

			/* request handling */
			@$ep->go();			
		}
}
