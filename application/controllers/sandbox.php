<?php

class Sandbox extends FT_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->check_if_admin(); 
		//$this->load->library(Array('formats'));
		$this->load->model(Array('lcamodel','loadmodel','geographymodel','nacemodel'));
	}
	
	function meow() {
		$this->lcamodel->dumptag();
	}
	
	function foot() {
		$this->lcamodel->blah();
	}
	function nace() {
		$this->nacemodel->dump();
	}
	
	function nacetest() {
		var_dump($this->nacemodel->getURIbyCode("A01.1"));
	}
	
	function countries() {
		//$this->geographymodel->getAllCountries2();
	}
	
	function cc() {
		$boop=$this->geographymodel->getURIbyAlpha3("RER");
		var_dump($boop);
		//$this->geographymodel->eu();
		//$this->geographymodel->bridge();
	}
	
	function testprotection(){
		echo "Hi Admin";
	}
	
	function cats() {
		$this->lcamodel->getAllUsedCategories();
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
