<?php
/**
 * Controller for dealing with processes
 * 
 * @version 0.8.0
 * @author info@footprinted.org
 * @package opensustainability
 * @subpackage controllers
 * @uses 
 */
class JsonModel extends Model{

	function JsonModel(){
		parent::__construct();
	}
	function getJSONasArray($name) 
	{
		$file_name = "application/models/json/".$name.".js";
		$handle = fopen($file_name, "r");
		$contents = fread($handle, filesize($file_name));
		fclose($handle);
		$json_array = json_decode($contents, true);
		return $json_array;
	}

}

?>