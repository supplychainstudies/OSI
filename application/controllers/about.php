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
		$this->db->where('title',"About");	
		$rs = $this->db->get('texts');
		$this->data('text', $rs->result());
		$this->data('title', "About Footprinted");
		$this->style(Array(''));
		$this->display("Footprinted.org", "info_view");
	}	
	
	public function team()
	{	
		$this->db->where('title',"Team");	
		$rs = $this->db->get('texts');
		$this->data('text', $rs->result());
		$this->data('title', "Footprinted.org Team");
		$this->display("Footprinted.org", "info_view");
	}
	
	public function code()
	{		
		$this->db->where('title',"Code");	
		$rs = $this->db->get('texts');
		$this->data('text', $rs->result());
		$this->data('title', "Footprinted.org Code");
		$this->style(Array(''));
		$this->display("Footprinted.org", "info_view");
	}
	public function API()
	{		
		$this->db->where('title',"API");	
		$rs = $this->db->get('texts');
		$this->data('text', $rs->result());
		$this->data('title', "Footprinted.org API");
		$this->style(Array(''));
		$this->display("Footprinted.org", "info_view");
	}
	public function linkeddata()
	{		
		$this->db->where('title',"Linked Data");	
		$rs = $this->db->get('texts');
		$this->data('text', $rs->result());
		$this->data('title', "Why Footprinted.org is using linked data");
		$this->style(Array(''));
		$this->display("Footprinted.org", "info_view");
	}
	public function aboutdata()
	{		
		$this->db->where('title',"Data");	
		$rs = $this->db->get('texts');
		$this->data('text', $rs->result());
		$this->data('title', "About Footprinted.org data");
		$this->style(Array(''));
		$this->display("Footprinted.org", "info_view");
	}
}
?>