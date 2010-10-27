<?php
/**
 * Controller for administrative functionality.
 * 
 * @version 0.8.0
 * @author sourcemap@media.mit.edu
 * @package sourcemap
 * @subpackage controllers
 * @uses Authmodel PartsModel string
 */
class Tools extends SM_Controller {		
	function Tools() {
		parent::SM_Controller();
	}
	
	function simpletester() {
		$this->load->library('Wick');
		$GLOBALS['wick'] = $this->wick;
		error_reporting('E_NONE');
		$this->load->library('standard/simpletester');	
		$this->output->append_output($this->simpletester->Run());
	}
	function dbupdater() {
		error_reporting('E_NONE');
		$this->load->library('standard/dbupdater', Array($this->db));	
		$this->dbupdater->runUpdate();
	}
}
