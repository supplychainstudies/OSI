<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('phpass-0.1/PasswordHash.php');

define('PHPASS_HASH_STRENGTH', 8);
define('PHPASS_HASH_PORTABLE', false);

/**
 * SimpleLoginSecure Class
 *
 * Makes authentication simple and secure.
 *
 * Simplelogin expects the following database setup. If you are not using 
 * this setup you may need to do some tweaking.
 *   
 * 
 *   CREATE TABLE `users` (
 *     `user_id` int(10) unsigned NOT NULL auto_increment,
 *     `user_email` varchar(255) NOT NULL default '',
 *     `user_pass` varchar(60) NOT NULL default '',
 *     `user_date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Creation date',
 *     `user_modified` datetime NOT NULL default '0000-00-00 00:00:00',
 *     `user_last_login` datetime NULL default NULL,
 *     PRIMARY KEY  (`user_id`),
 *     UNIQUE KEY `user_email` (`user_email`),
 *   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 * 
 * @package   SimpleLoginSecure
 * @version   1.0.1
 * @author    Alex Dunae, Dialect <alex[at]dialect.ca>
 * @copyright Copyright (c) 2008, Alex Dunae
 * @license   http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      http://dialect.ca/code/ci-simple-login-secure/
 */
class SimpleLoginSecure
{
	var $CI;
	var $user_table = 'users';

	/**
	 * Create a user account
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function create($user_name = '', $user_email = '', $user_pass = '', $description = '', $contact="FALSE", $auto_login = true) 
	{
		$this->CI =& get_instance();
		


		//Make sure account info was sent
		if($user_name == '' OR $user_email == '' OR $user_pass == '') {
			return false;
		}
		
		//Check against user table
		$this->CI->db->where('name', $user_name); 
		$query = $this->CI->db->getwhere($this->user_table);
		
		if ($query->num_rows() > 0) //user_email already exists
			return false;

		//Hash user_pass using phpass
		$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
		$user_pass_hashed = $hasher->HashPassword($user_pass);

		//Insert account into the database
		$data = array(
					'name' => $user_name,			
					'email' => $user_email,
					'pass' => $user_pass_hashed,
					'description' => $description,	
					'contactable' => $contact,														
					'created' => date('c'),
					'modified' => date('c'),
				);

		$this->CI->db->set($data); 

		if(!$this->CI->db->insert($this->user_table)) //There was a problem! 
			return false;						
				
		if($auto_login)
			$this->login($user_name, $user_pass);
		
		return true;
	}

	function changepass($oldpass = '', $newpass = '') 
	{
		$this->CI =& get_instance();
					
		//Hash user_pass using phpass
		$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
				
		//Check against user table
		$this->CI->db->where('name', $this->CI->session->userdata('name')); 		
		$query = $this->CI->db->getwhere($this->user_table);

		
		if ($query->num_rows() <= 0) 
			return false;
			
		$user_data = $query->row_array();
			
		if(!$hasher->CheckPassword($oldpass, $user_data['pass']))
			return false;
			
		$user_pass_hashed = $hasher->HashPassword($newpass);

		//Insert account into the database
		$data = array(
					'pass' => $user_pass_hashed
				);

		$this->CI->db->set($data); 
		$this->CI->db->where("name", $this->CI->session->userdata('name')); 
		
		if(!$this->CI->db->update($this->user_table)) //There was a problem! 
			return false;						
				
		return true;
	}
	
	function setpass($newpass = '', $email = '') 
	{
		$this->CI =& get_instance();
					
		//Hash user_pass using phpass
		$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
					
		$user_pass_hashed = $hasher->HashPassword($newpass);

		//Insert account into the database
		$data = array(
					'pass' => $user_pass_hashed
				);

		$this->CI->db->set($data); 
		$this->CI->db->where("email", $email); 
		
		if(!$this->CI->db->update($this->user_table)) //There was a problem! 
			return false;						
				
		return true;
	}
	/**
	 * Login and sets session variables
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function login($user_name = '', $user_pass = '') 
	{
		$this->CI =& get_instance();

		if($user_name == '' OR $user_pass == '')
			return false;


		//Check if already logged in
		if($this->CI->session->userdata('name') == $user_name)
			return true;
		
		
		//Check against user table
		$this->CI->db->where('name', $user_name); 
		$query = $this->CI->db->getwhere($this->user_table);

		
		if ($query->num_rows() > 0) 
		{
			$user_data = $query->row_array(); 

			$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);

			if(!$hasher->CheckPassword($user_pass, $user_data['pass']))
				return false;

			//Destroy old session
			$this->CI->session->sess_destroy();
			
			//Create a fresh, brand new session
			$this->CI->session->sess_create();

			$this->CI->db->simple_query('UPDATE ' . $this->user_table  . ' SET last_login = NOW() WHERE id = ' . $user_data['id']);

			//Set session data
			unset($user_data['pass']);
			$user_data['user'] = $user_data['name']; // for compatibility with Simplelogin
			$user_data['logged_in'] = true;
			$this->CI->session->set_userdata($user_data);
			
			return true;
		} 
		else 
		{
			return false;
		}	

	}

	/**
	 * Logout user
	 *
	 * @access	public
	 * @return	void
	 */
	function logout() {
		$this->CI =& get_instance();		

		$this->CI->session->sess_destroy();
	}

	/**
	 * Delete user
	 *
	 * @access	public
	 * @param integer
	 * @return	bool
	 */
	function delete($user_id) 
	{
		$this->CI =& get_instance();
		
		if(!is_numeric($user_id))
			return false;			

		return $this->CI->db->delete($this->user_table, array('id' => $user_id));
	}
	
}
?>
