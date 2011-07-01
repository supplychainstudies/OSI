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
 
 
 
class People extends FT_Controller {
    public function People() {
        parent::__construct();
        $this->load->model(Array('peoplemodel')); 
        $this->load->library(Array('form_extended','session'));
		$this->load->helper(Array('nameformat_helper'));
    }
    public $URI;
    public $data;
    public $post_data;
     
    public function everybody() {
        $this->peoplemodel->everybody();
    }
     
    public function lookup() {
    /***
    * @public
    * Generates a form, or, in the case where post data is passed, submits the data to the DB
    */
        $values = $_REQUEST;
        $foaf_array = array();
        if (isset($values['firstName']) == true) {
            $foaf_array['firstName'] = $values['firstName'];        
        }
        if (isset($values['lastName']) == true) {
            $foaf_array['lastName'] = $values['lastName'];
        }
        if (isset($values['email']) == true) {
            $foaf_array['email'] = $values['email'];    
        }
        $results = $this->peoplemodel->searchPeople($foaf_array);
        echo json_encode($results);
    }
     
    public function create() {
    /***
    * @public
    * Generates a form, or, in the case where post data is passed, submits the data to the DB
    */
	$this->check_if_logged_in();
     if($_POST) {
            $data = $this->form_extended->load('person'); 
			$uri_name = "";
			if ($_POST['firstName_'] != "" && $_POST['firstName_'] != "" ) {
				$uri_name = $_POST['firstName_'].$_POST['firstName_'];
			} elseif ($_POST['email_'] != "") {
				$uri_name = $_POST['email_'];
			} 
            $uri = toURI("people",$uri_name);
            $triples = $this->form_extended->build_group_triples($uri, $_POST, $data); 
			var_dump($triples);
            //@$this->peoplemodel->addTriples($triples);
            // Show the whole entry
            //$this->view($URI); 
			var_dump($this->session->userdata('convert_json'));
			if ($this->session->userdata('convert_json') == true) {
				redirect("/converter/forms");
			}
        } else {
            $data = $this->form_extended->load('person'); 
            $the_form = $this->form_extended->build();
            $this->style(Array('style.css', 'form.css'));
            $this->script(Array('form.js', 'toggle.js', 'lookup.js'));
            $this->data("view_string", $the_form);
            $this->display("Form", "view");
        }
    }


}