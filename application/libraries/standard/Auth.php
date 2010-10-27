<?php

/**
 * Simple Authentication
 * 
 */
class Auth {
	var $CI;

	function Auth() {
		$this->CI =& get_instance();
		$this->load->library('standard/SimpleLoginSecure');
	}

    /*
     *  Returns where the user is logged_in or not
     */
    function checkUserLogin() {
      if($this->CI->session->userdata('logged_in')) { return true; }
      else {return false;}		
    }
    
    /**
     * Gets the logged_in user name and checks with the session data. 
     * returns true if correct otherwise false.
     */
    function getCurrentUser() {
      if($this->CI->checkUserLogin()) { return $this->CI->session->userdata('name'); } else { return false; }
    }
    
    /**
     * Returns true if the current user id is same as the session user id
     */
    function getCurrentUserID() {
      if($this->CI->checkUserLogin()) { return $this->CI->session->userdata('id'); } else { return false; }
    }
    
    /**
     * Checks if the user data is role to set it to admin and display the dashboard.
     */
    function getCurrentUserRole() {
      if($this->CI->checkUserLogin()) { return $this->CI->session->userdata('role'); } else { return false; }
    }
    
    /**
     * Checks the last modified data.
     */
    function getCurrentUserLastLogin() {
      if($this->CI->checkUserLogin()) { return $this->CI->session->userdata('modified'); } else { return false; }
    }
    
    /**
     * Returns the current user email if it exists.
     */
    function getCurrentUserEmail() {
      if($this->CI->checkUserLogin()) { return $this->CI->session->userdata('email'); } else { return false; }
    }
    
    /**
     * When clicked on logout, it ends the current session of the user.
     */
    function logoutCurrentUser() {
      $this->CI->simpleloginsecure->logout();		
    }
    
    /**
     * Checks the authenticity of the user name and password and
     * creates session for the user
     */
    function loginCurrentUser($username, $password) {
      if($this->CI->simpleloginsecure->login($username, $password)) {
	return "true";
      } 
      else { return "false"; }
    }

    function changeCurrentUserPassword($old, $new) {
      if($this->CI->simpleloginsecure->changepass($old, $new)) {
	return "true";
      } 
      else { return "false"; }
    }

    function setCurrentUserPassword($new, $email) {
      $this->CI->simpleloginsecure->setpass($new, $email); 
    }
    /**
     * Creates an user with the entered data,
     * this data is entered into the database.
     */
    function createUser($user, $pass, $email, $description, $contact) {
      $this->CI->simpleloginsecure->create($user, $email, $pass, $description, $contact);		
      $this->CI->emailRegistration($user, $pass, $email, $description, $contact);
    }
 
    public function resetForgottenPassword($email, $user) {
      $newpass = $this->CI->createRandomPassword();
      $this->CI->setCurrentUserPassword($newpass, $email);
      
      $this->CI->load->library('email');

      $this->CI->email->from('email', 'name');
      $this->CI->email->to($email); 

      $this->CI->email->subject('Password Reset');
      $this->CI->email->message('Hi '.$user.'. We have generated a new password for you.
Your new password is '.$newpass.'.');	

      if ( ! $this->CI->email->send())
	{
	  echo "Error with your email address!";
	}
	}
	
	private function createRandomPassword() { 
	    $chars = "abcdefghijklmnopqrstuvwxyz!!0123456789"; 
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

    /**
     * Sends an email confirming the registration of the user.
     * If the email address does not exist then it sends an error message.
     */
    function emailRegistration($user, $pass, $email, $description, $contact) {
      $this->CI->load->library('email');
      
      $this->CI->email->from('email', 'name');
      $this->CI->email->to($email); 
      
      $this->CI->email->subject('Welcome.');
      $this->CI->email->message('Hi '. $user . ' thanks for registering.');	
      
      if ( ! $this->CI->email->send())
	{
	  echo "Error with your email address!";
	}
    }
    

  }
?>