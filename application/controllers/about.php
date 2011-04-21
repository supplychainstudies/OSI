<?php
/**
 * Controller for static information of Footprinted website
 *
 * @version 0.0.1
 * @author info@opensustainability.info
 * @package opensustainabilityinfo
 * @subpackage controllers
 */

class About extends SM_Controller {
	
	public function About() {
		parent::SM_Controller();
		$this->load->model(Array());
		$this->load->helper();
	}
	
	public function index()
	{		
		$this->data('title', "About Footprinted");
		$this->style(Array(''));
		$this->display("Footprinted.org", "info_view");
	}	
	
	public function team()
	{		
		$this->data('title', "Foorprinted.org Team");
		$this->style(Array(''));
		$this->display("Footprinted.org", "team_view");
	}
	
}
?>