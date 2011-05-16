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

class Search extends FT_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model(Array('lcamodel', 'geographymodel', 'bibliographymodel','peoplemodel','commentsmodel','ecomodel','opencycmodel'));	
		$this->load->library(Array('form_extended', 'xml'));
		$this->load->helper(Array('nameformat_helper','linkeddata_helper'));
		$obj =& get_instance();    
        $obj->load->library(array('xml'));
        $this->ci =& $obj;
	}
	
	public function keyword($keyword = "") {
		if ($keyword == "") {
			echo "Form!";
		} else {
			$records = $this->lcamodel->simpleSearch($keyword, "100000", 0);
			$set = array();
			foreach ($records as $key => $record) {	
				// Go through each field
				foreach ($record as $_key => $field) {
					// if its a uri, get the label and store that instead 
					// rewrite this into a better function later
						$set[$key][$_key] = $field;
				}
			}
			$this->data("set", $set);			
		}
		$this->display("Search","search_view");
	}
	
	public function includes($io) {
		
	}
	
	public function geography($geo) {
		
	}
	
	public function category($uri= "") {		
		if ($uri == "") {
			$categories = array(
				array(
					"uri" => "Mx4rvUCoPtoTQdaZVdw2OtjsAg",
					"label" => "chemical compound"
				),
				array(
					"uri"=>"Mx4rv-6HepwpEbGdrcN5Y29ycA",
					"label"=> "transportation"
					),
				array(
					"uri"=>"Mx4rvVibU5wpEbGdrcN5Y29ycA",
					"label"=> "textile"
					),
				array(
					"uri"=>"Mx4rwQr0i5wpEbGdrcN5Y29ycA",
					"label"=> "building material"
				),
				array(
					"uri"=>"Mx4rvVi9A5wpEbGdrcN5Y29ycA",	
					"label"=> "food"
				),
				array(
					"uri"=>"Mx4rvViSe5wpEbGdrcN5Y29ycA",
					"label"=> "commodity"
				)		
			);
		} else {
			$search_term = $this->opencycmodel->getOpenCycLabel("http://sw.opencyc.org/concept/".$uri);
			$this->data("search_term", $search_term);
			$categories = $this->opencycmodel->getOpenCycSearchCategories("http://sw.opencyc.org/concept/".$uri);
		}
		if ($uri != "") {
			$records = $this->lcamodel->getLCAsByCategory("http://sw.opencyc.org/concept/".$uri);
			$set = array();
			foreach ($records as $key => $record) {	
				// Go through each field
				foreach ($record as $_key => $field) {
					// if its a uri, get the label and store that instead 
					// rewrite this into a better function later
						$set[$key][$_key] = $field;
				}
			}
			$this->data("set", $set);			
		}
		$this->data("menu", $categories);
		$this->display("Search","search_view");
		
		/*
		//$rs = $this->opencycmodel->getOpenCycSearchCategories("http://sw.opencyc.org/concept/".$uri);
		foreach ($rs as $r) {
			$menu .= '<a href="/search/category/'.str_replace("http://sw.opencyc.org/concept/","",$r['uri']).'">'.$r['label'].'</a><br />';
		} */	
		
	}
	
}