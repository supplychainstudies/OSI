<?php
/**
 * Controller for initial load
 *
 * @version 0.7.5
 * @author sourcemap@media.mit.edu
 * @package sourcemap
 * @subpackage controllers
 */
class Home extends SM_Controller {
	
	public function Home() {
		parent::SM_Controller();
		$this->load->model(Array());
		$this->load->helper();
	} 
	
	/**
	 * Default controller, loads the home screen.
	 */
	public function index() {

		$menu = "<a href=\"info/browse\">Browse &raquo; </a><br />\n" . 
				"<a href=\"#\">Search &raquo; </a><br />\n" .
				"<a href=\"info/create/\">Submit Environmental Data &raquo; </a><br />\n" ; 
		$content = "Welcome to OpenSustainability.info, a website for open environmental data";
		$this->style(Array(''));
		$this->data('menu', $menu);		
		$this->data('content', $content);
		$this->data('box1', "");		
		$this->data('box2', "");		
		$this->display("Opensustainability.info", "home_view");
	}
	


}