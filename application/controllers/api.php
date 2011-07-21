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


class API extends FT_Controller {
	public function __construct() {
		parent::__construct();
	}
	public $URI;
	public $data;
	public $post_data;

	//General search 
	public function search($encode = "json") {
		parse_str($_SERVER['QUERY_STRING'],$_GET);
		$checked_URIs = array();
		$search_terms = $_GET;
		$limit = 20;
		$offset = 0;
		$value = null;
		$URIs = array();
		$rs = array();
		$category = null;
		$name = null;
		// Check API key
		$key = "";

		if (isset($search_terms['key']) == true) { $key = $search_terms['key'];}
		if ($_SERVER['HTTP_REFERER'] != "http://sourcemap.com") {
			$this->checkApiKey($key);
		}
		if (isset($search_terms['limit']) == true) { $limit = $search_terms['limit'];}
		if (isset($search_terms['offset']) == true) { $offset = $search_terms['offset'];	}
		if (isset($search_terms['name']) == true) {	$name = $search_terms['name'];}	
		if (isset($search_terms['encode']) == true) {$encode = $search_terms['encode'];}
		if (isset($search_terms['category']) == true) {$category = $search_terms['category'];}
		
		// Querying the database for the footprints		
		if($category){ $this->db->like('category', $category); }
		if($name){$this->db->like('name', $name); }		
		$this->db->order_by("name", "ASC");
		$this->db->where("public",true); 
		$rs = $this->db->get('footprints', $limit, $offset);
		if ($encode == 'json') {
			header('Content-type: application/json');
			echo json_encode($rs->result());
		} else if ($encode == 'rdf') {
			header('Content-type: text/xml');
			echo $rs->result();			
		} else if ($encode == 'xml') {
			header('Content-type: text/xml');
			echo $this->assocArrayToXML("results",$rs->result());
		} else if ($encode == 'html') {
			header('Content-type: text/html');
			var_dump($rs->result());			
		}
	}
	
	private function checkApiKey($key) {
     	if ( $key == "") {
			echo "<p>Sorry, you have to have an API key, see instructions below</p>";
			redirect('/about/api');
		}else{
			$this->db->where('key',$key);
			$user = $this->db->get('users');
			if (count($user->result()) == 0) {
				echo "<p>Sorry, your API key was wrong,see instructions below</p>";
				redirect('/about/api');}
   			}
		}
}