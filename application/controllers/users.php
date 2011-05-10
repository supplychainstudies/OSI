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

class Users extends CI_Controller {
	var $CI;
	var $user_table = 'users';
	var $openid_table = 'users_openids';
	var $foaf_table = 'users_foaf';
		
	public function Users() {
		parent::__construct();
		$this->load->model(Array('usersmodel','lcamodel'));	
		$this->lang->load('openid', 'english');
	    $this->load->library(Array('openid','form_extended', 'form_validation', 'SimpleLoginSecure'));
	    $this->load->helper('url');

	}
	
	public function index() {
		// Step 1: Find out if someone is already logged in
			// If so, go to dashboard
			//var_dump($_POST);
		if($this->session->userdata('id') == true) {
			redirect('/users/dashboard');
		// If not logged in, figure out whether there is post info from janrain
		} else {
			// If there is post info from janrain, figure out whether this person is already in the system
			if ($_POST) {
				if (isset($_POST['token']) == true) {
					// if they are already in the system, log them in and send them to the dashboard
					$auth_info = $this->janrainAuthInfo();
					if ($this->simpleloginsecure->openID($auth_info['profile']['identifier']) != false) {
						$this->session->set_userdata('id', $this->simpleloginsecure->openID($auth_info['profile']['identifier']));
						redirect('/users/dashboard');
					// If they are not in the system yet, pass them to the register form
					} else {
						$this->register($auth_info);
					}				
				} elseif ((isset($_POST['user_name']) == true && isset($_POST['password']) == true) || (isset($_POST['user_name_']) == true && isset($_POST['password_']) == true)) {
					if (isset($_POST['user_name_']) == true) {
						$_POST['user_name'] = $_POST['user_name_'];
						$_POST['password'] = $_POST['password_'];
					}
					if ($this->simpleloginsecure->login($_POST['user_name'], $_POST['password']) == true) {
						redirect('/users/dashboard');
					} else {
						redirect('/users/loginerror');
					}
				}
			// If there is no post from Janrain, redirect them to the register page
			} else {
				redirect('users/register');
			}
		} // end of not logged in
	}
	
	public function loginerror() {
		$data = $this->form_extended->load('login'); 
		$the_form = "<div<p>Hm, your user name or password doesn't seem to be right. Want to try again?</p></div>";
		$the_form .= $this->form_extended->build();
		$the_form .= "<div><p> Or <a href=\"users/register\">Register</a> with us.</p></div>";
		$this->script(Array('form.js','register.js'));
		$this->style(Array('style.css','form.css'));
		$this->data("form_string", $the_form);
		$this->display("Form", "form_view");
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
			$refer = "http://footprinted.org";
		}
			
		header ("Location: " . $refer);			
	}
	
	public function delete() {
		$this->simpleloginsecure->delete($user_id);
	}

	public function logout() {
		$this->simpleloginsecure->logout();
		$this->session->unset_userdata('id');		
		$this->session->sess_destroy();
		redirect("/");		
	}
	
	
	
	public function janrainAuthInfo() {
		$rpx_api_key = '5d4fd93930fd5fb1e3c91eeaf3340da29ff4a617';

		/*
		Set this to true if your application is Pro or Enterprise.
		Set this to false if your application is Basic or Plus.
		*/
		$engage_pro = false;

		/* STEP 1: Extract token POST parameter */
		$token = $_POST['token'];

		if(strlen($token) == 40) {//test the length of the token; it should be 40 characters

		  /* STEP 2: Use the token to make the auth_info API call */
		  $post_data = array('token' => $token,
		                     'apiKey' => $rpx_api_key,
		                     'format' => 'json',
		                     'extended' => 'true');

		  $curl = curl_init();
		  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($curl, CURLOPT_URL, 'https://rpxnow.com/api/v2/auth_info');
		  curl_setopt($curl, CURLOPT_POST, true);
		  curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
		  curl_setopt($curl, CURLOPT_HEADER, false);
		  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		  curl_setopt($curl, CURLOPT_FAILONERROR, true);
		  $result = curl_exec($curl);
		  curl_close($curl);	
		  $auth_info = json_decode($result, true);
		  return $auth_info;
		}
	}
	
	public function register($auth_info = false) {
		$pass_data = array ();
		if ($auth_info != false) {
		  if(isset($auth_info['profile']['identifier']) == true) {
				$pass_data['openid'] = $auth_info['profile']['identifier'];
			}
		  if(isset($auth_info['profile']['preferredUsername']) == true) {
				$pass_data['user_name'] = $auth_info['profile']['preferredUsername'];
			}
		  if(isset($auth_info['profile']['email']) == true) {
				$pass_data['email'] = $auth_info['profile']['email'];
			}
		}
		$this->data("pass_data", $pass_data);
		$this->form_extended->load('register'); 
		$the_form = '<p> <a class="rpxnow" onclick="return false;" href="https://opensustainability.rpxnow.com/openid/v2/signin?token_url=http%3A%2F%2Ffootprinted.org%2Fusers%2F">Use an Open ID login &rsaquo; &rsaquo;</a> </p>';
		$the_form .= $this->form_extended->build();
		$this->script(Array('form.js','register.js','janrain.js'));
		$this->style(Array('style.css','form.css'));
		$this->data("form_string", $the_form);
		$this->display("Form", "form_view");					
	}
	
	public function registered() {
		// Note: Most validation had already been done using jquery
		// Step 1: Check recaptcha
		if ($this->form_validation->run()) {
			// Recaptcha is fine!
			$this->CI =& get_instance();
			// Step 1: Write the user name, email, password to db
			// Step 2: Get the unique id	
			
			$id = $this->simpleloginsecure->create($_POST['email_'], $_POST['password_'], $_POST['user_name_'], true);
		
			// Step 3: If there is an openid, write to db
			$openid_array = $_POST['openid_'];
			if ($openid_array != "") {
				foreach($openid_array as $openid) {
					if ($openid != "") {
					$data = array(
								'user_id' => $id,
								'openid_url' => $openid
							);

					$this->CI->db->set($data); 

					if(!$this->CI->db->insert($this->openid_table)) //There was a problem! 
						return false;
					}
				}
			}
			// Step 4: If there is a foaf URI, write to db
			$foaf_array = $_POST['foaf_'];
			if ($foaf_array != "") {
				foreach($foaf_array as $foaf) {
					if ($foaf != "") {
						$data = array(
									'user_id' => $id,
									'foaf_uri' => $foaf
								);

						$this->CI->db->set($data); 

						if(!$this->CI->db->insert($this->foaf_table)) //There was a problem! 
							return false;
					}		
				}
			}	
		
			if($this->simpleloginsecure->login($_POST['user_name_'], $_POST['password_'])) {
			    redirect('/users/dashboard/');
			}		
		} else {
		// Recaptcha isn't fine, reload page
			echo "oops";
			//redirect('/users/register/');
		}
	}
	
	public function dashboard() {
		$user_data = "";
		if($this->session->userdata('id') == true) {
		    $user_data = $this->simpleloginsecure->userInfo($this->session->userdata('id'));
			// IF there is Foaf data, send to dashboard
			if (isset($user_data["foaf_uri"]) == true){
				$user_activity = $this->arcmodel->getLCAsByPublisher( $user_data["foaf_uri"]);
				$this->data("user_activity", $user_activity);
			}			
		} else {
			echo "not logged in";
		}	

		$published = $this->lcamodel->getLCAsByPublisher($user_data['foaf_uri']);
		
		$this->data("user_data", $user_data);
		$this->data("published", $published);
		$this->style(Array('style.css'));

		$this->display("Dashboard", "dashboard_view");				
	}
	
}