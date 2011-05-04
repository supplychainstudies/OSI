<?php

/**
 * Representation of users as model, handles transactions for
 * users so they can be listed or queried. 
 * 
 * @author Matthew Hockenberry
 * @package sourcemap
 * @subpackage models
 */


class UsersModel extends Model{
	
	/**
	 * @ignore
	 */
	function UsersModel(){
		parent::__construct();

	}


	/**
	 * Delete(Archive) a user
	 */
	public function userDelete($name) {
	  $archive = array (
			    'category' => addslashes("archived"),
			    );
	  $this->db->where('name', $name);
	  $this->db->update('users', $archive);

	  
	} 
	
	/**
	 * From the parameter $id from the table user 
	 * retrieves the username and returns it as a string
	 * 
	 * @param integer $id
	 * @return string (username)
	 */
	function getUserById($id)
	{	
	  $this->db->select('*');				
	  $this->db->from('users');		
	  $this->db->where('id', $id);
	  
	  $query = $this->db->get();	  

	  return array_pop($query->result());
	}

	function getUsersByIds($ids)	
	{	
	  $this->db->select('*');				
	  $this->db->from('users');		
	  $this->db->where_in('id', $ids);	  

	  $query = $this->db->get();	  

	  return $query->result();
	}
	
	/**
	 * From the parameter $name from the 
	 * 
	 * @param string $name
	 * @return object (User)
	 */
	function getUserByName($name)
	{	
		$this->db->select('*');				
		$this->db->from('users');		
		$this->db->where('name', "$name");

		$query = $this->db->get();		

		return array_pop($query->result());
	}
	
	function getUserByEmail($email)
	{	
		$this->db->select('*');				
		$this->db->from('users');		
		$this->db->where('email', "$email");

		$query = $this->db->get();		

		return array_pop($query->result());
	}

	function getIdByUser($user_id){

	         $this->db->select('identifier');
		 $this->db->from('openidusers');
		 $this->db->where('user_id', $user_id);

		 $query = $this->db->get();		

		return array_pop($query->result());
	}

	
	function checkTos($username) {
	        $this->db->select('flags');
		$this->db->from('users');
		$this->db->where('name', $username);

		$query = $this->db->get();		
		
		return array_pop($query->result());
	}

	function updateTos($username) {
	  $tos = array (
			 'flags' => 1
			 );
	  $this->db->where('name', $username);
	  $this->db->update('users', $tos);
	}


	/**
	 * Gets the list of all users.
	 * @return Array Array of all users.
	 * @param integer $limit Limit number of users.
	 * @param integer $offset Offset to return users.
	 * @param string $sort How users should be sorted.
	 */
	function getUsers($limit = null, $offset = 0, $sort = 'last_login DESC'){
			$users = $this->db->query("SELECT DISTINCT users.name, users.email, users.id, users.category, users.last_login,count(*) as smapcount FROM users, objects_complex WHERE users.id =  objects_complex.creator GROUP BY users.name, users.email, users.id, users.category, users.last_login ORDER BY ".$sort. " LIMIT ".$limit. " OFFSET ".$offset);

	
		$userlist = $users->result();
				
		return $userlist;
	}

	
	/**
	 * Checks if username is available
	 * 
	 * @param integer $id
	 * @return boolean 
	 */
	function checkUserName($name)
	{	
		$this->db->select('*');				
		$this->db->from('users');		
		$this->db->where('name', $name);
    
		$query = $this->db->get();
		
		if($query->num_rows() > 0) { return false; }
		else { return true; }
	}
	
	function checkUserMail($email)
	{	
		$this->db->select('*');				
		$this->db->from('users');		
		$this->db->where('email', $email);
    
		$query = $this->db->get();
		
		if($query->num_rows() > 0) { return false; }
		else { return true; }
	}

	function setUserName($name) {
	  $test = $this->doesUserExist($name);
	  $nameCount = 0;
	  while ($test) {
	    $nameCount++;
	    $test = $this->doesUserExist($name."-".$nameCount);
	  }
	  if ($nameCount > 0) { $name = $name."-".$nameCount; }
	  
	  return $name;
	}
	
	function doesUserExist($name) {
	  $this->db->select("*");
	  $this->db->from('users');
	  $this->db->where('name', $name);
	  $result = $this->db->get()->result();
	  return (isset($result) && sizeof($result) > 0);

	}	
	
	
	function openidusers($identifier, $last_id){
	  $data = array(
			'identifier' =>  $identifier,
			'user_id' => $last_id
			);
	  $this->db->insert('openidusers', $data);
	  
	}	

	
	/**
	 * Gets the user id from a user name
	 * 
	 * @param string $name
	 * @return integer
	 */
	function getUserId($name)
	{	
		$this->db->select('id');	
		$this->db->from('users');
		$this->db->where('name', "$name");
    
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			$query = array_pop($query->result());
			return $query->id;
		}
		
		return 0;
	}

	/**
	 * Returns the top users based on number of objects they have created
	 * @limit int 10
	 * @order count of objects in descending order
	 */
	
	function getTopUsers($limit=10){
		$this->db->select("users.*, objects.count", false);
		$this->db->from("users");
		$this->db->join("(select creator,count(*) as count, visibility, flag, name from objects_complex group by creator, visibility, flag, name) as objects", "objects.creator = users.id", "left outer");
		$this->db->where('objects.visibility !=', 'private');
		$this->db->where('objects.category !=', 'archived');
		$this->db->where('objects.flag !=', 'temporary');		
		$this->db->where('objects.name !=', '');		
		$this->db->order_by("objects.count desc");
		
		$this->db->limit($limit);
		
		$users = $this->db->get();
		$users = $users->result();
		return $users;
	}

	/**
	 * Gets the password 
	 * @param int $user_id
	 * @return string pass
	 */
	
	function getPasswordByUserId($user_id) {
	  $this->db->select('pass');
	  $this->db->where('id', $user_id);
	  $this->db->from('users');

	  $query = $this->db->get();

	  $query = array_pop($query->result());
	  return $query->pass;
	}

	/**
	 * Returns the total size of the users table.
	 * @return int Number of users.
	 */
	function getUsersCount(){
		return $this->db->count_all_results('users');
	}

	/**
	 * Gets the count of users 
	 * @param string $searchTerm
	 * @return count of all the users
	 */
	
	function getUserSearchCount($searchTerm){
		$this->db->like('name', $searchTerm);
		$this->db->or_like('description', $searchTerm);
		
		return $this->db->count_all_results('users');
	}

	/**
	 * Gets all the users 
	 * @param string $searchTerm
	 * @limit int  $limit null by default
	 * @sort name in ascending 
	 * @offset int $offset
	 */
	function searchUsers($searchTerm, $limit = null, $offset = null, $sort = 'name ASC'){
		$this->db->select('*');		
		$this->db->from('users');
		$this->db->order_by($sort);		
		
		$this->db->like('name', $searchTerm);
		$this->db->or_like('description', $searchTerm);
		
		$this->db->limit($limit, $offset);
		
		$query = $this->db->get();
		return  $query->result();
	}

	
	/**
	 * Updates the description column in the users table
	 * @param $profile 
	 */
	
	function updateUserProfile($profile) {
		$data = array(
			'description' => $profile->description			
		);

		$this->db->where('id',$profile->id);
		$this->db->update('users', $data);
	}

	
	/*
	 * Get the id and password from the users table
	 * @param $name
	 * @return string $object
	 */

		function getUserIdByName($name) {
		$this->db->select('id','password');
		$this->db->from('users');
		$this->db->where('name', $name);
		$query = $this->db->get();
		$object= array_pop($query->result());
		return $object;
	}
       
}
