<?php
/**
 * Controller for static information of OSI website
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
		$this->data('title', "About Open Sustainability Info");
		$this->style(Array(''));
		$this->display("Opensustainability.info", "info_view");
	}
	
	public function team()
	{		
		$this->data('title', "Team Open Sustainability Info");
		$this->style(Array(''));
		$this->display("Opensustainability.info", "team_view");
	}
	
	
	public function garden()
	{		
		$this->data('title', "App Garden");
		$this->style(Array(''));
		$this->display("Opensustainability.info", "garden_view");
	}
	
}
?>