<?php
/**
 * Controller for authentication functionality (login, register).1
 * 
 * @author dev@sourcemap.org
 * @package sourcemap
 * @subpackage controllers
 * @uses UsersModel
 */


class Auth extends SM_Controller {
	public function Auth() {
		parent::SM_Controller();
		$this->load->model(Array('UsersModel'));
		$this->config->set_item('uri_protocol', 'PATH_INFO');
		$this->load->helper('string');	
		//$this->load->helper(Array('googlemaps', 'time', 'locale', 'text', 'http'));
	    
	}
	
	/**
	 * Default controller, redirects appropriately.
	 */
	public function index() {
	  	if($this->sm_auth->getCurrentUser() != "") { redirect('/dashboard/'); } 
	  else { redirect('/auth/register/'); }
	}
	
	/**
	 * Presents registration.
	 * @uses recaptcha
	 */
	public function register() {
		$this->load->helper('recaptcha');
		$this->data("errors", $this->_registerCheck($_POST));
		$this->style(Array('site/auth.css'));
		$this->script(Array('site/register.js'));
		$this->contextual(Array('logincallout'));		
		$this->view("authDisplay", "auth/register_view");
		$this->display("Register or Login", "auth_view");
	}
	
	/**
	 * Presents a stand alone login page.
	 */
	function login() {
		$this->style(Array('site/auth.css'));
		$this->view("authDisplay", "auth/login_view");
		$this->display("Login", "auth_view");
	}
	
	function loginopenid() {
	  $result = $this->_registerUser($_POST);
	  if ($result["errors"] = "registered") {
	    redirect('/dashboard/'); 
	    
	  } else if ("loggedin") {
	    redirect('/user/'.$result["name"]); 
	    
	  } else {
	    $this->data("errors", $result["errors"]);
	    $this->style(Array('site/auth.css'));
	    $this->contextual(Array('logincallout'));		
	    $this->view("authDisplay", "auth/register_view");
	    $this->display("Register or Login", "auth_view");
	  }	  
	}
	
	function _registerUser($_POST) {
	  $errors = "";
	  if (isset($_POST['token'])) {
	    $token = $_POST['token'];
	    
	    $apikey = 'c6a330c05bb8bf1e91522b5a78e5d12d58e65ca6';
	    $post_data = array('token' => $_POST['token'],
			       'apiKey' => $apikey,
			       'format' => 'json');
	   

	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_URL, 'https://rpxnow.com/api/v2/auth_info');
	    curl_setopt($curl, CURLOPT_POST, true);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
	    curl_setopt($curl, CURLOPT_HEADER, false);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    $raw_json = curl_exec($curl);
	    curl_close($curl); 	    
	    
	    $auth_info = json_decode($raw_json, true);
	    

	    if ($auth_info['stat'] == 'ok') {
	      $profile = $auth_info['profile'];
	      if (isset($profile['identifier'])) {
		$identifier = $profile['identifier'];
	      }
	      if (isset($profile['name'])) {
		$name = $profile['name'];
		if (isset($name['givenName'])) {
		  $fullname = $name['givenName'];
		} else {
		  $fullname = $profile['displayName'];
		}
	      }	 
	      
	      if (isset($profile['verifiedEmail'])) {
		$email = $profile['verifiedEmail'];
	      }
	      
	      $auto_password = $this->genpassword();	      
	      
	      if (!$this->UsersModel->checkUserName($fullname)) {		
		$username = $this->UsersModel->setUserName($fullname); 		
	      } else {
		$username = $fullname;
	      }	      

	      $check_email = $this->UsersModel->checkUserMail($email);
	      
	      if ($check_email == true) {		
		$this->sm_auth->autoRegister($username, $auto_password, $email, $description=null);
		$last_id = $this->UsersModel->getUserId($username);	
		$this->UsersModel->openidusers($identifier, $last_id);
		$errors = "registered";
		redirect('/dashboard/'); 
	      } else {
		$user = $this->UsersModel->getUserByEmail($email);
		$username = $user->name;
		$id = $user->id;
		$identifier = $this->UsersModel->getIdByUser($id);
		if(isset($identifier)) {
		  $identifier = $identifier->identifier;
		}
		
		if(isset($identifier) || isset($username)) {
		  $this->sm_auth->logUser($username, $email);
		  $errors = "loggedin";
		  redirect('/user/'.$username); 
		}
	      }
	      
	    } else {
	      
	      $errors .= "Make sure you enter a valid information.";
	    }
	  }

	  return array ("errors" => $errors, "name" => $username);
	}

	function genpassword($len = 6) {	
	  $password = '';
	  for($i=0; $i<$len; $i++)
	    $password .= chr(rand(0, 25) + ord('a'));
	  return $password;
	}
	 

	/**
	 * Logs a user in if they pass valid credentials.
	 * @param $username The username of the user.
	 * @param $password The user's password
	 */
	public function login_post($username=null, $password=null) {
		if($username == null) { $username = $this->input->post('user_name'); }
		if($password == null) { $password = $this->input->post('password'); }
		if($this->sm_auth->loginCurrentUser($username, $password) == "true") {
			if(strstr($_SERVER['HTTP_REFERER'], "auth/register") || strstr($_SERVER['HTTP_REFERER'], "home") || $_SERVER['HTTP_REFERER'] == base_url()) { redirect('dashboard'); }  
			else {redirect($_SERVER['HTTP_REFERER']); }
		} 
		else { $this->forgotpassword("That login didn't work, did you forget your password?"); }
	}
	
	/**
	 * Logs out the current user.
	 */
	public function logout() { $this->sm_auth->logoutCurrentUser(); redirect(''); }
	
	/**
	 * Handles forgotten passwords
	 * @param $error string Any errors from previous submissions
	 */
	public function forgotpassword($error = '') {
		if($this->sm_auth->checkUserLogin()) {
			redirect('dashboard');
		}
		else {
			if($this->input->post('email') != '') {
				$email = $this->input->post('email');
				$user = $this->UsersModel->getUserByEmail($email);
				$this->sm_auth->resetForgottenPassword($email, $user->name);
				redirect('');
			}
			$this->data("errors", $error);
			$this->style(Array('site/auth.css'));
			$this->view("authDisplay", "auth/forgot_view");
			$this->display("Forgot Password", "auth_view");	
		}	
	}
	
	/**
	 * Changes a users password.
	 */		
	public function changepassword() {
		$password = json_decode($this->input->post("data"));		
		$this->output->set_output($this->sm_auth->changeCurrentUserPassword($password->old, $password->change));		
	}
	
	/**
	 * Handles registration checking, creates users or produces errors.
	 * @param $form the form (post data ) containing necessary registration information.
	 * @return string The errors in registration ("" if nothing).
	 */
	private function _registerCheck($form) {
		$errors = "";
		if(isset($form['submitted'])) {
			if(isset($form['reg_user_name']) 
			&& isset($form['reg_password']) 
			&& isset($form['confirmpassword']) 
			&& isset($form['email'])) 
			{
				$check = $this->_registryEntryCheck($form['reg_user_name'], 
 								    $form['reg_password'], 
								    $form['confirmpassword'], 
								    $form['email']);
				if($check != "") { $errors = $check;} 
				else {
					$resp = null;
					if(isset($form['recaptcha_challenge_field']) 
					&& isset($form['recaptcha_response_field'])) 
					{
						$privatekey = "6Ld8BwkAAAAAADz-pFe_twgSmK-pJ8wY4CJlLCFF";
						$resp = recaptcha_check_answer ($privatekey, 
										$_SERVER["REMOTE_ADDR"], 
										$form['recaptcha_challenge_field'], 
										$form['recaptcha_response_field']);
					} // Recaptcha result
					else { $resp->is_valid = true; }
					if (!$resp->is_valid) {
						if($errors != "") { $errors .= " & "; }
						$errors .= "You missed the captcha. Try again and be precise - capitals, punctuation and spacing count."; 
					} // Check if captcha is entered
					else {
						if(!(isset($form['contact']))) { $form['contact'] = 'FALSE';}
						$this->sm_auth->createUser($form['reg_user_name'], $form['reg_password'], $form['email'], $form['description'], $form['contact']);
						$this->sm_auth->loginCurrentUser($form['reg_user_name'], $form['reg_password']);
						redirect('/dashboard/');
					}
				}
			} // Check if form is complete
			else {
				if($errors != "") { $errors .= " & "; }
				$errors .= "Please complete the entire Registration Form.";
			}
		} // Form was submitted
		return $errors;
	}
	
	/**
	 * Determines if a username is already registered, passwords match, and email is unique and valid.
	 * @param $user string The username.
	 * @param $pass string The password
	 * @param $confpass The confirmation of the password
	 * @param $email The user email
	 * @return string The errors in registration ("" if nothing).
	 */
	private function _registryEntryCheck($user, $pass, $confpass, $email) {
		$errors = "";
		if(!($this->UsersModel->checkUserName($user))) {
			if($errors != "") { $errors .= " & "; }
			$errors .= "That username is unavailable, choose another one.";
		}
		if(!($this->UsersModel->checkUserMail($email))) {
			if($errors != "") { $errors .= " & "; }
			$errors .= "That email address is unavailable, choose another one.";
		}
		if(!($pass == $confpass)) {
			if($errors != "") { $errors .= " & "; } 
			$errors .= "Make sure your passwords match.";
		}
		if (!(preg_match("/^[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?$/iD", $email))) {
			if($errors != "") { $errors .= " & "; }
			$errors .= "Make sure you enter a valid email address.";
		}
		return $errors;
	}

	
    /**
     * Ajax handler, checks permission type and sets the objects permission.
     */
    public function setpermissions() {
        if(!req_is_json_post()) {
            $this->output->set_header('HTTP/1.1 400 Bad Request');
            $this->output->set_header('Content-Type: application/json');
            $this->output->set_output(
                json_encode(array('error' => 'Please post json.'))
            );
            return;
        }
        $data = json_decode(file_get_contents('php://input'));
	
	$oid = $data->oid;		  
	if(isset($data->visibility)) {
	  $visibility = $data->visibility; 
	  $this->ObjectsModel->updateObjectVisibility($visibility, $oid);
	}
	if(isset($data->permission_type)) {
	  $permission_type = $data->permission_type;
	  	  	  
	  if($permission_type == "useredit") {
            $user = $this->sm_auth->getCurrentUser();
            $user_id = $this->sm_auth->getCurrentUserID();
            $this->ObjectsModel->updatePermissions($permission_type, $oid, $user_id);
	  } else if($permission_type == "groupedit") {
            $groupname = $data->group_name;
            $slugname = $this->GroupsModel->getSlugByName($groupname);
            $group_id =$this->GroupsModel->get_group_id($slugname);
            $this->ObjectsModel->updatePermissions($permission_type, $oid, $group_id);
	  } else if($permission_type == "everyoneedit") {
	    $this->ObjectsModel->updatePermissions($permission_type, $oid);
            // The visibility is automatically set to public if it is everyoneedit
            $this->ObjectsModel->updateObjectVisibility("public", $oid);
	  }
	}
        
        $this->output->set_header('HTTP/1.1 202 Accepted');
        $this->output->set_header('Content-Type: application/json');
        $this->output->set_output(
            json_encode(array('status' => 'saved'))
        );

    }



} // End Auth




?>