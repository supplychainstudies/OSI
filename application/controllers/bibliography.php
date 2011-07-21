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



class Bibliography extends FT_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model(Array('bibliographymodel'));	
		$this->load->library(Array('form_extended'));
		$this->load->helper(Array('nameformat_helper'));
	}
	
	/***
    * @public
    * Generates a form, or, in the case where post data is passed, submits the data to the DB
    */
	public function create() {
		$this->check_if_logged_in();
		$data = $this->form_extended->load('bibliography'); 
	    $the_form = '<input name="people_field" type="hidden" /><div class="dialog" name="people_dialog" id="people_dialog"></div>'.$this->form_extended->build();
	    $this->style(Array('style.css', 'form.css'));
	    $this->script(Array('form.js'));
	    $this->data("view_string", $the_form);
	    $this->display("Form", "view");
	 }
	
	public function browse() {
		$refs = $this->bibliographymodel->getRefsAPA();
		var_dump($refs);
	}

}