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



class Users extends SM_Controller {
	public function Users() {
		parent::SM_Controller();
		//$this->load->model(Array('arcmodel', 'arcremotemodel', 'mysqlmodel'));	
		$this->load->library(Array('SimpleLoginSecure'));
		$this->lang->load('openid', 'english');
	    $this->load->library('openid');
	    $this->load->helper('url');

	}
	
	public function login() { 
		if (isset($_SERVER['HTTP_REFERER']) == true) 
			$this->session->set_userdata(array('last_page' => $_SERVER['HTTP_REFERER']));
		if($this->session->userdata('id')) {			
		} elseif (isset($_POST['user_name']) == true) {
			if ($_POST['open_id'] != "") {
				$this->loginopenid($_POST['open_id']);
			} elseif ($_POST['user_name'] != "" && $_POST['password'] != "") {
				if($this->simpleloginsecure->login($_POST['user_name'], $_POST['password'])) {
					$this->session->unset_userdata(array('loginfail'));  
				}
				else {
					$this->session->set_userdata(array('loginfail'  => 'fail'));  
				}				
			}
		} 
		if ($this->session->userdata('last_page')) {
			$refer = $this->session->userdata('last_page');
			$this->session->unset_userdata('last_page');
		} else if (isset($_SERVER['HTTP_REFERER']) == true) {
			$refer = $_SERVER['HTTP_REFERER'];
		} else {
			$refer = "http://opensustainability.info";
		}
			
		header ("Location: " . $refer);			
	}
	
	public function delete() {
		$this->simpleloginsecure->delete($user_id);
	}

	public function logout() {
		$this->simpleloginsecure->logout();
		$this->session->unset_userdata('logged_in');
		$this->session->unset_userdata('id');		
		$this->session->sess_destroy();
		header ("Location: " . $_SERVER['SERVER_NAME']);		
	}
	
	public function create() {
		if (isset($_POST['user_name']) == true && isset($_POST['password']) == true) {
			$this->simpleloginsecure->create($_POST['user_name'], $_POST['password']);
		} else {
			$the_form = '<form method="post">' . 
						'<h1 class="level0">Login</h1><div class="level0">' . 
						'<ul class="layout">' .
						'<li>' .
						'<label>User Name</label>' .
						'<input id="user_name" name="user_name" type="text" />' . 
						'</li>' . 
						'<li>' .
						'<label>Password</label>' .
						'<input id="password" name="password" type="password" />' . 
						'</li>' .
						'<input type="submit">' . 						
						'</ul></div>' .
						'</form>';
			$this->style(Array('style.css'));
			$this->data("form_string", $the_form);
			$this->display("Form", "form_view");			
		}
		
	}
		
	public function loginopenid($user_id) {
		 // Before we check if the username is in fact an OpenID, we must secure
		// the variable. OpenID refers to the url as $user_id, so be sure to assign
		// the value of the inputted openid to $user_id. Here is have used
		// $this->username but this might change on your system.
		$user_id = htmlspecialchars($user_id);

		// Load the neccessary OpenID libraries as shown by the example.
		$this->lang->load('openid', 'english');
		$this->load->library('openid');

		$this->config->load('openid');
		$req = $this->config->item('openid_required');
		$opt = $this->config->item('openid_optional');
		$policy = site_url($this->config->item('openid_policy'));
		$request_to = site_url($this->config->item('openid_request_to'));

		$this->openid->set_request_to($request_to);
		$this->openid->set_trust_root(base_url());
		$this->openid->set_args(null);
		$this->openid->set_sreg(true, $req, $opt, $policy);
		$pape_policy_uris = array();
		$this->openid->set_pape(true, $pape_policy_uris);
		$this->openid->authenticate($user_id);
	}
    // Policy
    function policy()
    {
      $this->load->view('view_policy');
    }
    
    // set message
    function _set_message($error, $msg, $val = '', $sub = '%s')
    {
        //return str_replace($sub, $val, $this->lang->line($msg));
		if ($error) {
			header( 'Location: /users/policy' ) ;
		}
    }
    
    // Check
    public function check()
    {    
      $this->config->load('openid');
      $request_to = site_url($this->config->item('openid_request_to'));
      
      $this->openid->set_request_to($request_to);
    $response = $this->openid->getResponse();

    switch ($response->status)
    {
        case Auth_OpenID_CANCEL:
            $data['msg'] = $this->lang->line('openid_cancel');
            break;
        case Auth_OpenID_FAILURE:
			$this->session->set_userdata(array('loginfail'  => 'fail'));  
            $data['error'] = $this->_set_message('openid_failure', $response->message);
            break;
        case Auth_OpenID_SUCCESS:
            $openid = $response->getDisplayIdentifier();
            $esc_identity = htmlspecialchars($openid, ENT_QUOTES);
			$this->session->set_userdata(array('id' => $openid));
            $this->session->unset_userdata('loginfail');  


			if ($this->session->userdata('last_page')) {
				$refer = $this->session->userdata('last_page');
				$this->session->unset_userdata('last_page');
			} else if (isset($_SERVER['HTTP_REFERER']) == true) {
				$refer = $_SERVER['HTTP_REFERER'];
			} else {
				$refer = "http://opensustainability.info";
			}

			header ("Location: " . $refer);			

            //$sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
            //$sreg = $sreg_resp->contents();
            //$pape_resp = Auth_OpenID_PAPE_Response::fromSuccessResponse($response);
		}
	}
	
}