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

class Search extends FT_Controller {
	public function __construct() {
		parent::__construct();
		//$this->load->model(Array('lcamodel', 'geographymodel', 'bibliographymodel','peoplemodel','commentsmodel','ecomodel','opencycmodel'));	
		//$this->load->library(Array('form_extended', 'xml'));
		//$this->load->helper(Array('nameformat_helper','linkeddata_helper'));
		$obj =& get_instance();    
        $obj->load->library(array('xml'));
        $this->ci =& $obj;
	}

	
	public function index(){
		parse_str($_SERVER['QUERY_STRING'],$_GET);
		$search_terms = $_GET;
		if (isset($search_terms['category']) == true) {
			$this->db->like('category', $search_terms['category']);
			$this->data("category", $search_terms['category']);
			$this->db->order_by("name", "ASC"); 
			$rs = $this->db->get('footprints',100,0);
			$this->data("set", $rs->result());
		}
		if (isset($_POST["keyword"]) == true){
			$keyword = $_POST["keyword"];
		} else {
			$keyword = "";
		}
		if ($keyword != "") {
			$this->db->like('name', $keyword); 
			$this->db->order_by("name", "ASC"); 
			$rs = $this->db->get('footprints',100,0);
			$this->data("set", $rs->result());
		} 
		$categories = array(
			array("uri" => "chemical", "label" => "chemical compound"),
			array("uri"=>"transport", "label"=> "transportation"),
			array("uri"=>"textile","label"=> "textile"),
			array("uri"=>"building","label"=> "building material"),
			array("uri"=>"food",	"label"=> "food"),
			array("uri"=>"commodity","label"=> "commodity"));

		$this->data("search_term", $keyword);
		$this->data("menu", $categories);
		$this->display("Search","search_view");
	}
	
/*	
	public function keyword() {
		$keyword = $_POST["keyword"];
		if ($keyword != "") {
			$records = $this->lcamodel->simpleSearch($keyword, "100000", 0);
			$set = array();
			foreach ($records as $key => $record) {	
				// Go through each field
				foreach ($record as $_key => $field) {
					// if its a uri, get the label and store that instead 
					// rewrite this into a better function later
						$set[$key][$_key] = $field;					
				}
				
				// Get impact assessments
				$availableimpacts = "";
				$impacts = $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments($set[$key]['uri']));
				foreach ($impacts as $impactAssessment) {
					switch ($impactAssessment['impactCategoryIndicator']['label']) {
				    	case 'Waste': $availableimpacts .= " Waste"; break;
				    	case 'Carbon Dioxide Equivalent': $availableimpacts .= " CO<sub>2</sub> (eq)";break;
						case 'Carbon Dioxide': $availableimpacts .= " CO<sub>2</sub>";break;
				    	case "Energy": $availableimpacts .= " Energy";	break;
						case "Water": $availableimpacts .= " Water"; break;
						default: $availableimpacts .= $impactAssessment['impactCategoryIndicator']['label'];
					}
				}
				$set[$key]['impacts'] = $availableimpacts;
				
				// Get the country name
				$geo = $this->lcamodel->convertGeography($this->lcamodel->getGeography($set[$key]['uri']));
				if(isset($geo[0]['name']) == true){ $set[$key]['geo'] = $geo[0]['name']; } else { $set[$key]['geo'] = ""; }
				
				// Get the year
				$set[$key]['year'] = "";
				$bio = $this->bibliographymodel->convertBibliography($this->bibliographymodel->getBibliography($set[$key]['uri']));
				foreach ($bio as $b) { $set[$key]['year'] = substr_replace($b['date'], '', 4); }
			}
			$this->data("set", $set);
			$this->data("search_term", $keyword);			
		}
			$categories = array(
				array(
					"uri" => "Mx4rvUCoPtoTQdaZVdw2OtjsAg",
					"label" => "chemical compound"
				),
				array(
					"uri"=>"Mx4rv-6HepwpEbGdrcN5Y29ycA",
					"label"=> "transportation"
					),
				array(
					"uri"=>"Mx4rvVibU5wpEbGdrcN5Y29ycA",
					"label"=> "textile"
					),
				array(
					"uri"=>"Mx4rwQr0i5wpEbGdrcN5Y29ycA",
					"label"=> "building material"
				),
				array(
					"uri"=>"Mx4rvVi9A5wpEbGdrcN5Y29ycA",	
					"label"=> "food"
				),
				array(
					"uri"=>"Mx4rvViSe5wpEbGdrcN5Y29ycA",
					"label"=> "commodity"
				)		
			);
		$this->data("menu", $categories);
		$this->display("Search","search_view");
	}
	
	public function includes($io) {
		
	}
	
	public function geography($geo) {
		
	}
	public function index() {
			$categories = array(
				array(
					"uri" => "Mx4rvUCoPtoTQdaZVdw2OtjsAg",
					"label" => "chemical compound"
				),
				array(
					"uri"=>"Mx4rv-6HepwpEbGdrcN5Y29ycA",
					"label"=> "transportation"
					),
				array(
					"uri"=>"Mx4rvVibU5wpEbGdrcN5Y29ycA",
					"label"=> "textile"
					),
				array(
					"uri"=>"Mx4rwQr0i5wpEbGdrcN5Y29ycA",
					"label"=> "building material"
				),
				array(
					"uri"=>"Mx4rvVi9A5wpEbGdrcN5Y29ycA",	
					"label"=> "food"
				),
				array(
					"uri"=>"Mx4rvViSe5wpEbGdrcN5Y29ycA",
					"label"=> "commodity"
				)		
			);
		$this->data("menu", $categories);
		$this->display("Search","search_view");
	}
	
	public function category($uri= "") {		
		if ($uri == "") {
			$categories = array(
				array(
					"uri" => "Mx4rvUCoPtoTQdaZVdw2OtjsAg",
					"label" => "chemical compound"		parse_str($_SERVER['QUERY_STRING'],$_GET);
		$checked_URIs = array();
		$search_terms = $_GET;
		$limit = 20;
		$offset = 0;
		$value = null;
		$URIs = array();
		$rs = array();
		$category = null;
		$name = null;
		if (isset($search_terms['limit']) == true) {
			$limit = $search_terms['limit'];
		}
		if (isset($search_terms['offset']) == true) {
			$offset = $search_terms['offset'];
		}
		if (isset($search_terms['name']) == true) {
			$name = $search_terms['name'];
		}	
		if (isset($search_terms['encode']) == true) {
			$encode = $search_terms['encode'];
		}
		if (isset($search_terms['category']) == true) {
			$category = $search_terms['category'];
		}
		// Querying the database for the footprints		
		if($category){
			$this->db->like('category', $category); 
		}
		if($name){
			$this->db->like('name', $name); 
		}		
		$this->db->order_by("name", "ASC"); 
		$rs = $this->db->get('footprints', $limit, $offset);
				),
				array(
					"uri"=>"Mx4rv-6HepwpEbGdrcN5Y29ycA",
					"label"=> "transportation"
					),
				array(
					"uri"=>"Mx4rvVibU5wpEbGdrcN5Y29ycA",
					"label"=> "textile"
					),
				array(
					"uri"=>"Mx4rwQr0i5wpEbGdrcN5Y29ycA",
					"label"=> "building material"
				),
				array(
					"uri"=>"Mx4rvVi9A5wpEbGdrcN5Y29ycA",	
					"label"=> "food"
				),
				array(
					"uri"=>"Mx4rvViSe5wpEbGdrcN5Y29ycA",
					"label"=> "commodity"
				)		
			);
		} else {
			$search_term = $this->opencycmodel->getOpenCycLabel("http://sw.opencyc.org/concept/".$uri);
			$this->data("search_term", $search_term);
			//$categories = $this->opencycmodel->getOpenCycSearchCategories("http://sw.opencyc.org/concept/".$uri);
			$categories = array(
				array(
					"uri" => "Mx4rvUCoPtoTQdaZVdw2OtjsAg",
					"label" => "chemical compound"
				),
				array(
					"uri"=>"Mx4rv-6HepwpEbGdrcN5Y29ycA",
					"label"=> "transportation"
					),
				array(
					"uri"=>"Mx4rvVibU5wpEbGdrcN5Y29ycA",
					"label"=> "textile"
					),
				array(
					"uri"=>"Mx4rwQr0i5wpEbGdrcN5Y29ycA",
					"label"=> "building material"
				),
				array(
					"uri"=>"Mx4rvVi9A5wpEbGdrcN5Y29ycA",	
					"label"=> "food"
				),
				array(
					"uri"=>"Mx4rvViSe5wpEbGdrcN5Y29ycA",
					"label"=> "commodity"
				)		
			);
		}
		if ($uri != "") {
			$records = $this->lcamodel->getLCAsByCategory("http://sw.opencyc.org/concept/".$uri);
			$set = array();
			foreach ($records as $key => $record) {	
				// Go through each field
				foreach ($record as $_key => $field) {
					// if its a uri, get the label and store that instead 
					// rewrite this into a better function later
						$set[$key][$_key] = $field;
				}
				// Get impact assessments
				$availableimpacts = "";
				$impacts = $this->lcamodel->convertImpactAssessments($this->lcamodel->getImpactAssessments($set[$key]['uri']));
				foreach ($impacts as $impactAssessment) {
					switch ($impactAssessment['impactCategoryIndicator']['label']) {
				    	case 'Waste': $availableimpacts .= " Waste"; break;
				    	case 'Carbon Dioxide Equivalent': $availableimpacts .= " CO<sub>2</sub> (eq)";break;
						case 'Carbon Dioxide': $availableimpacts .= " CO<sub>2</sub>";break;
				    	case "Energy": $availableimpacts .= " Energy";	break;
						case "Water": $availableimpacts .= " Water"; break;
						default: $availableimpacts .= $impactAssessment['impactCategoryIndicator']['label'];
					}
				}	
				$set[$key]['impacts'] = $availableimpacts;
				
				// Get the country name
				$geo = $this->lcamodel->convertGeography($this->lcamodel->getGeography($set[$key]['uri']));
				if(isset($geo[0]['name']) == true){ $set[$key]['geo'] = $geo[0]['name']; } else { $set[$key]['geo'] = ""; }
				
				// Get the year
				$set[$key]['year'] = "";
				$bio = $this->bibliographymodel->convertBibliography($this->bibliographymodel->getBibliography($set[$key]['uri']));
				foreach ($bio as $b) { $set[$key]['year'] = substr_replace($b['date'], '', 4); }
			}
			$this->data("set", $set);			
		}
		$this->data("menu", $categories);
		$this->display("Search","search_view");
		
		/*
		//$rs = $this->opencycmodel->getOpenCycSearchCategories("http://sw.opencyc.org/concept/".$uri);
		foreach ($rs as $r) {
			$menu .= '<a href="/search/category/'.str_replace("http://sw.opencyc.org/concept/","",$r['uri']).'">'.$r['label'].'</a><br />';
		} 	
		
	} */
	
}