<?php

class Sandbox extends FT_Controller {
	
	function Sandbox() {
		parent::__construct();
		//$this->load->library(Array('formats'));
		$this->load->model(Array('lcamodel','loadmodel'));
	}
	
	function fixlabels() {
		$this->lcamodel->fixLabels();
	}
	
	function geothis() {
		$this->arcmodel->getGeonames("http://sws.geonames.org/6295630/about.rdf");
	}
	function loadthis() {
		$this->loadmodel->dumpEco();
		$this->loadmodel->dumpqudt();
	}
	
	function loadq() {
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
	
	function addSameAs () {
		$this->lcamodel->addSameAs();
	}
}
?>
