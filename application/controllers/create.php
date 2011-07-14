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



class Create extends FT_Controller {
	public function __construct() {
		parent::__construct();
		$this->check_if_logged_in();
		$this->load->model(Array('unitmodel','ecomodel'));	
		$this->load->library(Array('form_extended'));
		$this->load->helper(Array('nameformat_helper'));
	}
	//public $URI;
	//public $data;
	//public $post_data;
	
	
	function _remap($section)
	{
	    $this->index($section);
	}
	
	
	public function index($section) {
		/*if (!isset($this->session->userdata('id'))) {
			redirect('users');
		}*/
	    /***
	    * @public
	    * Generates a form, or, in the case where post data is passed, submits the data to the DB
	    */
	        if ($post_data = $_POST) {
	            $data = $this->form_extended->load($section);     
	            $triples = $this->form_extended->build_triples("", $post_data, $data);
	            var_dump($triples);
	        } else {	        
		    	$data = $this->form_extended->load($section); 
	            $units = $this->unitmodel->getUnitMenu();
	            $impact_categories = $this->ecomodel->getImpactCategoryMenu();
	            $the_form = $impact_categories.'<input name="people_field" type="hidden" /><div class="dialog" name="people_dialog" id="people_dialog"></div>'.$units. $this->form_extended->build();
	            $this->style(Array('style.css', 'form.css'));
	            $this->script(Array('form.js'));
	            $this->data("view_string", $the_form);
	            $this->display("Form", "view");
	        }
	    }
}