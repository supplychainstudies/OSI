<?php

/**
 * This model handles mysql-based queries to the database 
 * 
 * @package opensustainability
 * @subpackage models
 */

class MysqlModel extends Model{
	
	/**
	 * @ignore
	 */
	function MysqlModel(){
		parent::Model();			
	}	
	

	public $server = "rhodium.media.mit.edu";
	public $user = "root";
	public $password = "suppl1ch41n";
	public $db = "opensustainability";
	
	
	/**
	 * This function is a generic call to the db.
	 * @return $results Array
	 * @param $q string - query string.
	 */	
	private function executeQuery($q) {
		$link = mysql_connect($this->server, $this->user, $this->password);
		mysql_select_db($this->db, $link);
		$results = mysql_query($q, $link);
		mysql_close($link);
		return $results;
	}


	/**
	 * Retrieves the object of a locally stored remote triple
	 * @return $row["object"]
	 * @param $subject string
	 * @param $predicate string
	 */		
	public function getCachedValue($subject, $predicate) {
		$results = $this->executeQuery("select object from cachedRDF where subject='".$subject."' and predicate='".$predicate."'");
		if (mysql_num_rows($results) > 0) {
			$row = mysql_fetch_assoc($results);
			return $row["object"];			
		} else {
			return false;
		}
	}


	/**
	 * Stores a remote triple as locally "cached" in the cachedRDF table
	 * @return Array of triples.
	 * @param $q string - query string.
	 */	
	public function addCachedValue($subject, $predicate, $object) {
		$results = $this->executeQuery("insert into cachedRDF (subject, predicate, object) VALUES ('".$subject."', '".$predicate."', '".$object."')");
	}	

}