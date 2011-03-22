<?php
/**
 * Controller for environmental information structures
 * 
 * @version 0.8.0
 * @author info@opensustainability.info
 * @package opensustainability
 * @subpackage controllers
 * @uses 
 */



class Converter extends SM_Controller {
	public function Converter() {
		parent::SM_Controller();
		$this->load->library(Array('xml'));
	}
	
	/***
    * @public
    * Allows you to edit an entry
	* This is not functional yet
    */	
	public function index($file = null, $format = null) {
		# LOAD XML FILE
		$file = "http://osi/assets/examples/Ecospold_biomass-8.8_miscanthus.xml";
		$format = "http://osi/assets/transforms/EcoSpold01toEcospold02.xsl";
		var_dump($file);
		$xml_doc = new DOMDocument();
		$xml_doc->load( $file );
		var_dump($xml_doc);
		# START XSLT
		$xslt_doc = new XSLTProcessor();

		# IMPORT STYLESHEET 1
		$xsl_output = new DOMDocument();
		$xsl_output->load( $format );
		$xslt_doc->importStylesheet( $xsl_output );
/*
		#IMPORT STYLESHEET 2
		$this->XSL = new DOMDocument();
		$this->XSL->load( 'template2.xsl' );
		$this->xslt->importStylesheet( $XSL );
*/
		#PRINT
		print $this->xslt->transformToXML( $xml_doc );
	}
}