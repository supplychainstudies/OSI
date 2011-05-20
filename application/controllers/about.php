<?php
/**
 * Controller for static information of Footprinted website
 *
 * @version 0.0.1
 * @author info@footprinted.org
 * @package opensustainabilityinfo
 * @subpackage controllers
 */

class About extends FT_Controller {
	
	public function About() {
		parent::__construct();
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
		$this->data('title', "Footprinted.org Team");
		$this->style(Array(''));
		$this->display("Footprinted.org", "team_view");
	}
	
	public function code()
	{		
		$this->data('title', "Footprinted.org Code");
		$this->style(Array(''));
		$this->display("Footprinted.org", "code_view");
	}
	public function API()
	{		
		$this->data('title', "Footprinted.org API");
		$this->style(Array(''));
		$this->display("Footprinted.org", "aboutAPI_view");
	}
	public function linkeddata()
	{		
		$this->data('title', "Why Footprinted.org is using linked data");
		$this->style(Array(''));
		$this->display("Footprinted.org", "linkeddata_view");
	}
	public function aboutdata()
	{		
		$this->data('title', "About Footprinted.org data");
		$this->style(Array(''));
		$this->display("Footprinted.org", "aboutdata_view");
	}
}
?>