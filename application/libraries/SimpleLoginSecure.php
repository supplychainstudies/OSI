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
	function create($user_email = '', $user_pass = '', $user_name = '', $auto_login = true) 
	{
		$this->CI =& get_instance();
		


		//Make sure account info was sent
		if($user_email == '' OR $user_pass == '') {
			return array("Error"=>"Missing Email or Password");
		}
		
		//Check against user table
		$this->CI->db->where('user_email', $user_email); 
		$query = $this->CI->db->getwhere($this->user_table);
		if ($query->num_rows() > 0) //user_email already exists
			return array("Error"=>"Email already Exists");
		$this->CI->db->where('user_name', $user_email); 
		$query = $this->CI->db->getwhere($this->user_table);
		if ($query->num_rows() > 0) //user_name already exists
			return array("Error"=>"Username already Exists");
				
		//Hash user_pass using phpass
		$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
		$user_pass_hashed = $hasher->HashPassword($user_pass);

		//Insert account into the database
		$data = array(
					'user_email' => $user_email,
					'user_pass' => $user_pass_hashed,
					'user_name' => $user_name,
					'user_date' => date('c'),
					'user_modified' => date('c'),
				);

		$this->CI->db->set($data); 

		if(!$this->CI->db->insert($this->user_table)) //There was a problem! 
			return false;						
				
		if($auto_login)
			$this->login($user_email, $user_pass);
		
		return $this->CI->db->insert_id();
	}
	
	/**
	 * Return User info
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function userInfo($user_name) 
	{
		$this->CI =& get_instance();
		//Check against user table
		$query = $this->CI->db->query('select user_id, user_name, user_email, user_date, user_modified, user_date from users where user_name="' . $user_name . '"');	
		$record = $query->row_array();
		$id = $record['user_id'];
		$query = $this->CI->db->query('select users_foaf.foaf_uri as foaf_uri from users_foaf where users_foaf.user_id='. $id);	
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			$record['foaf_uri'] = $row['foaf_uri'];
		}
		
		$query = $this->CI->db->query('select users_openids.openid_url as openid_url from users_openids where users_openids.user_id=' . $id);	
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			$record['openid_url'] = $row['openid_url'];
		}
		
		return $record;
	}
	
	function openID($openid_url) 
	{
		$this->CI =& get_instance();

		$query = $this->CI->db->query('select users.user_name as user_name from users, users_openids where users_openids.user_id=users.user_id and users_openids.openid_url="' . $openid_url . '"');	
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			return $row['user_name'];
		} else {
			return false;
		}
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
		if($this->CI->session->userdata('id') == $user_name)
			return true;
		
		
		//Check against user table
		$this->CI->db->where('user_name', $user_name); 
		$query = $this->CI->db->getwhere($this->user_table);

		
		if ($query->num_rows() > 0) 
		{
			$user_data = $query->row_array(); 

			$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);

			if(!$hasher->CheckPassword($user_pass, $user_data['user_pass']))
				return false;

			//Destroy old session
			$this->CI->session->sess_destroy();
			
			//Create a fresh, brand new session
			$this->CI->session->sess_create();

			$this->CI->db->simple_query('UPDATE ' . $this->user_table  . ' SET user_last_login = NOW() WHERE user_id = ' . $user_data['user_id']);

			//Set session data
			unset($user_data['user_pass']);
			$user_data['id'] = $user_data['user_name']; // modification by Bianca Sayan to tie neatly with OpenID		
			unset($user_data['user_name']);
			//$user_data['user'] = $user_data['user_email']; // for compatibility with Simplelogin
			//$user_data['logged_in'] = true;
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

		return $this->CI->db->delete($this->user_table, array('user_id' => $user_id));
	}
	
}
?>
