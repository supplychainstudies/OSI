<?php

class Sandbox extends SM_Controller {
	
	function Sandbox() {
		parent::SM_Controller();
		$this->load->library(Array('formats'));
		$this->load->model(Array('arcmodel','loadmodel'));
	}
	
	
	function geothis() {
		$this->arcmodel->getGeonames("http://sws.geonames.org/6295630/about.rdf");
	}
	function loadthis() {
		$this->loadmodel->dumpEco();
		$this->loadmodel->dumpqudt();
	}
	
	function fixgeo() {
		$this->loadmodel->fixgeo();
	}
	
	function fixfoaf() {
		$this->arcmodel->fixfoaf();
	}
	
	function fixMJ() {
		$this->arcmodel->fixMJ();
	}
}
?>
