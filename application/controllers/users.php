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


class Users extends FT_Controller {
	var $CI;
	var $user_table = 'users';
	var $openid_table = 'users_openids';
	var $foaf_table = 'users_foaf';
		
	public function Users() {
		parent::__construct();
		$this->load->model(Array('usersmodel','lcamodel'));	
		$this->lang->load('openid', 'english');
	    $this->load->library(Array('openid','form_extended', 'form_validation', 'SimpleLoginSecure', 'recaptcha'));
	    $this->load->helper('url');

	}
	
	public function index() {
		// Step 1: Find out if someone is already logged in
			// If so, go to dashboard
			//var_dump($_POST);
		if($this->session->userdata('id') == true) {
			redirect('/lca/featured');
		// If not logged in, figure out whether there is post info from janrain
		} else {
			// If there is post info from janrain, figure out whether this person is already in the system
			if ($_POST) {
				if (isset($_POST['token']) == true) {
					// if they are already in the system, log them in and send them to the dashboard
					$auth_info = $this->janrainAuthInfo();
					if ($this->simpleloginsecure->openID($auth_info['profile']['identifier']) != false) {
						$this->session->set_userdata('id', $this->simpleloginsecure->openID($auth_info['profile']['identifier']));
						redirect('/lca/featured');
					// If they are not in the system yet, pass them to the register form
					} else {
						$this->register($auth_info);
					}				
				} elseif ((isset($_POST['user_name']) == true) || (isset($_POST['user_name_']) == true)) {
					$this->login();
					/*					
					if (isset($_POST['user_name_']) == true) {
						$_POST['user_name'] = $_POST['user_name_'];
						$_POST['password'] = $_POST['password_'];
					}

					if ($this->simpleloginsecure->login($_POST['user_name'], $_POST['password']) == true) {
						redirect('/lca/featured');
					} else {
						redirect('/users/login');
					}
					*/
				}
			// If there is no post from Janrain, redirect them to the register page
			} else {
				redirect('users/register');
			}
		} // end of not logged in
	}
/*	
	public function loginerror() {
		var_dump($_SERVER['HTTP_REFERER']);
		$data = $this->form_extended->load('login'); 
		$the_form = "<div<p>Hm, your user name or password doesn't seem to be right. Want to try again?</p></div>";
		$the_form .= $this->form_extended->build();
		$the_form .= "<div><p> Or <a href=\"users/register\">Register</a> with us.</p></div>";
		$this->script(Array('form.js','register.js'));
		$this->style(Array('style.css','form.css'));
		$this->data("form_string", $the_form);
		$this->display("Form", "form_view");
	}
*/	
	public function login() { 	
		if (isset($_SERVER['HTTP_REFERER']) == true) { 
			if (strpos($_SERVER['HTTP_REFERER'], base_url()."users") === false) {
				$this->session->set_userdata(array('last_page' => $_SERVER['HTTP_REFERER']));
			}
		}
		if ($this->session->userdata('last_page')) {
			$refer = $this->session->userdata('last_page');
		} else {
			$refer = base_url()."lca/featured";
		}
		if (isset($_POST['open_id']) == true || isset($_POST['open_id_']) == true) {
			if (isset($_POST['open_id_']) == true) {
				$_POST['open_id'] = $_POST['open_id_'];
			}
			if ($this->loginopenid($_POST['open_id'])) {
			} else {
				$fail = "Hm, your openid doesn't seem to be right.";
			}
		} elseif (isset($_POST['user_name']) == true || isset($_POST['user_name_']) == true) {	
			if (isset($_POST['user_name_']) == true) {
				$_POST['user_name'] = $_POST['user_name_'];
				$_POST['password'] = $_POST['password_'];
			}
			if($this->simpleloginsecure->login($_POST['user_name'], $_POST['password']) == true) {
			} else {
				$fail = "Hm, your user name or password doesn't seem to be right. Want to try again?";
			}
		} 
		if($this->session->userdata('id')) {
			$this->session->unset_userdata('last_page');
			redirect($refer);		
		} else {
			$data = $this->form_extended->load('login'); 
			$the_form = "";
			if (isset($fail) == true) {
				$the_form = "<div><p>".$fail."</p></div>";
			}
			$the_form .= $this->form_extended->build();
			$the_form .= "<div><p> Or <a href=\"/users/register\">Register</a> with us.</p></div>";
			$this->script(Array('form.js','register.js'));
			$this->style(Array('style.css','form.css'));
			$this->data("form_string", $the_form);
			$this->display("Form", "form_view");			
		} 	
	}
	
	public function delete() {
		$this->check_if_admin();
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
		if (isset($this->pass_data) == false) {
			$this->pass_data = array (); }
		if ($auth_info != false) {
		  if(isset($auth_info['profile']['identifier']) == true) {
				$this->pass_data['openid_'] = $auth_info['profile']['identifier'];
			}
		  if(isset($auth_info['profile']['preferredUsername']) == true) {
				$this->pass_data['user_name_'] = $auth_info['profile']['preferredUsername'];
			}
		  if(isset($auth_info['profile']['email']) == true) {
				$this->pass_data['email_'] = $auth_info['profile']['email'];
			}
		}
		$this->data("pass_data", $this->pass_data);
		$this->form_extended->load('register'); 
		//$the_form = '<p> <a class="rpxnow" onclick="return false;" href="https://opensustainability.rpxnow.com/openid/v2/signin?token_url=http%3A%2F%2Ffootprinted.org%2Fusers%2F">Use an Open ID login &rsaquo; &rsaquo;</a> </p>';
		$the_form = "<p>Footprinted is in closed beta version. If you want to test the site please send as an email.</p>";
		if (isset($this->error) == true) {
			$the_form = '<p class="error">'.$this->error.'</p>';
		}
		$the_form .= $this->form_extended->build();
		$this->script(Array('form.js','register.js','janrain.js'));
		$this->style(Array('style.css','form.css'));
		$this->data("form_string", $the_form);
		$this->display("Form", "form_view");					
	}
	
	private function checkPasscode($passcode) {
		$passcodes = array(
			"KTH","ISIE","UWATERLOO", "MIT"
		);
		if (in_array(strtoupper($passcode), $passcodes) !== false) {
			return true;
		} else {
			return false;
		}
	}
	
	// Gives back the user API key, if not existing, it autogenerates one
	public function getAPIkey(){
		if($this->session->userdata('id') == true) {
		    $this->db->where('user_name',$this->session->userdata('id'));
			$user = $this->db->get('users',1,0);
			$user = $user->result();
			if($user[0]->key){
				$this->data("key", $user[0]->key);
			} else {
				// Generate a random key
				$key = $this->createRandomKey();
				// Check that it doesn't exist
				$unique = false;
				while($unique == false){
					$this->db->where('key',$key);
					$this->db->from('users');
					if ($this->db->count_all_results() == 0){
						$unique = true;
					}else{
						$key = $this->createRandomKey();
					}
				}
				$data = array (
					'key' => $key
					);
				$this->db->where('user_name',$this->session->userdata('id'));	
				$this->db->update('users', $data);
				$this->data("key", $key);
			}
			$this->display("Dashboard", "api_key_view");
		} else {
			$this->index();
		}	
	}
	
	private function createRandomKey() {
	    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
	    srand((double)microtime()*1000000);
	    $i = 0;
	    $pass = '' ;
	    while ($i <= 7) {
	        $num = rand() % 33;
	        $tmp = substr($chars, $num, 1);
	        $pass = $pass . $tmp;
	        $i++;
	    }
	    return $pass;
	}

	
	public function registered() {
		// Note: Most validation had already been done using jquery
		// Step 1: Check recaptcha		
		if ($_POST) {
			$this->pass_data = array();
		  if(isset($_POST['openid_']) == true) {
				if ($_POST['openid_'] != "") {
					$this->pass_data['openid_'] = $_POST['openid_']; }
			}
		  if(isset($_POST['user_name_']) == true) {
				if ($_POST['user_name_'] != "") {
					$this->pass_data['user_name_'] = $_POST['user_name_']; }
			}
		  if(isset($_POST['email_']) == true) {
				if ($_POST['email_'] != "") {
					$this->pass_data['email_'] = $_POST['email_']; }
			}
			if(isset($_POST['foaf_']) == true) {
				if ($_POST['foaf_'] != "") {
					$this->pass_data['foaf_'] = $_POST['foaf_']; }
			}
			if(isset($_POST['registration_code_']) == true) {
				if ($_POST['registration_code_'] != "") {
					$this->pass_data['registration_code_'] = $_POST['registration_code_']; }
			}		
			if ($this->checkPasscode($_POST['registration_code_']) == true) {
				$this->CI =& get_instance();
				// Step 1: Write the user name, email, password to db
				// Step 2: Get the unique id				
				$id = $this->simpleloginsecure->create($_POST['email_'], $_POST['password_'], $_POST['user_name_'], true);
				if (is_int($id) == true) {
					if ($_POST['openid_'] != "") {
							$data = array(
										'user_id' => $id,
										'openid_url' => $_POST['openid_']
									);

							$this->CI->db->set($data); 

							if(!$this->CI->db->insert($this->openid_table)) {
							//There was a problem! 
								$this->error = "For some reason, we couldn't use the info you provided. Try again?";
								$this->register();
							}
					}		
					if ($_POST['foaf_'] == "") {
						$_POST['foaf_'] = toURI("people",$_POST['user_name_']);				
					}
					$data = array(
								'user_id' => $id,
								'foaf_uri' => $_POST['foaf_']
							);

					$this->CI->db->set($data); 
					if(!$this->CI->db->insert($this->foaf_table)) //There was a problem! 
						return false;		
					$this->login();	
				} else {
					$this->error = "Something went wrong. Try again?";
				}
			} else {
				$this->error = "You don't have a Registration Code! Want one? Email us.";
				$this->register();
			}
		} else {
			redirect(base_url());
		}
	}
	
	public function takenEmail() {
		if (isset($_REQUEST['email']) == true) {
			$results = $this->simpleloginsecure->takenEmail($_REQUEST['email']);
			if ($results == true) { echo "true"; } else { echo "false"; }	
		}		
	}
	
	public function takenName() {
		if (isset($_REQUEST['name']) == true) {
			$results = $this->simpleloginsecure->takenName($_REQUEST['name']);		
			if ($results == true) { echo "true"; } else { echo "false"; }		
		}
	}
	
	/*
	Shows the dashboard control panel for the users
	*/
	public function dashboard() {
		$user_data = "";
		$published = "";
		if($this->session->userdata('id') == true) {
		    $user_data = $this->simpleloginsecure->userInfo($this->session->userdata('id'));
			$id = $this->session->userdata('id');
			$this->db->where('user_name',$id);
			$this->db->limit('1');
			$rs = $this->db->get('users');
			// Send data to the view
			$this->data("set", $rs->result());
			// IF there is friend of a friend data, send to dashboard
			if (isset($user_data["foaf_uri"]) == true){
				// Get the user activity (such as comments)
				$user_activity = $this->lcamodel->getLCAsByPublisher( $user_data["foaf_uri"]);
				// Get the LCAs that the user has published
				$published = $this->lcamodel->getLCAsByPublisher($user_data['foaf_uri']);
				$this->data("user_activity", $user_activity);
				$this->data("published", $published);
			}			
		} else {
			$this->index();
		}	
		$this->data("user_data", $user_data);
		$this->style(Array('style.css'));
		$this->display("Dashboard", "dashboard_view");				
	}
	// Edit yourprofile                            
	public function editprofile(){
		if($this->session->userdata('id') == true) {
		$id = $this->session->userdata('id');
		$this->db->where('user_name',$id);
		$this->db->limit('1');
		$rs = $this->db->get('users');
		// Send data to the view
		$this->data("set", $rs->result());
		$this->display("Admin","admin/edit_profile");
		}
	}
	// Show public profile                            
	public function showprofiles(){
		//$this->check_if_logged_in();
		// Get ID from form
		parse_str($_SERVER['QUERY_STRING'],$_GET); 
		if (isset($_GET["id"]) == false){
			$id = $this->session->userdata('id');
		}else{
			$id = $_GET["id"];
		}
		$this->db->where('user_name',$id);
		$this->db->limit('1');
		$rs = $this->db->get('users');
		$this->data("set", $rs->result());
		$allusers = $this->db->get('users');
		$this->data("allusers", $allusers->result());
		
		$user_data = $this->simpleloginsecure->userInfo($id);
		if (isset($user_data["foaf_uri"]) == true){
			// Get the user activity (such as comments)
			//$user_activity = $this->lcamodel->getLCAsByPublisher( $user_data["foaf_uri"]);
			// Get the LCAs that the user has published
			$published = $this->lcamodel->getLCAsByPublisher($user_data['foaf_uri']);
			$this->data("published", $published);
		}
		// Send data to the view
		$this->display("All users","admin/your_profile");
	}
	public function allusers(){
		$this->check_if_logged_in();
		// Get ID from form
		$allusers = $this->db->get('users');
		$this->data("allusers", $allusers->result());
		// Send data to the view
		$this->display("All users","admin/all_users");
	}
	
	// Save the changes for editing
	public function saveprofile(){
		$this->check_if_logged_in();

		$id = $this->input->post('id');
		 
		// Create array for database fields & data  
		$data = array();
		$data['user_email'] = $this->input->post('user_email');
		$data['firstname'] = $this->input->post('firstname');
		$data['surname'] = $this->input->post('surname');
		$data['bio'] = $this->input->post('bio');
		$this->db->where('user_id', $id);
		$result = $this->db->update('users', $data);
		redirect('users/dashboard');
	}
	
	
	
	
	
	
}