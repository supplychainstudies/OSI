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



class Comments extends FT_Controller {
	public function Comments() {
		parent::SM_Controller();
		$this->load->model(Array('arcmodel'));
		$this->load->library(Array('name_conversion'));
	}
	
	public function post() {
		$post = $this->name_conversion->toBNode('post');
		$account = $this->name_conversion->toBNode('account');
		$post_date = date("H:i:s-n:j:Y");
		$triples = array( 
			array(
				"s" => $_REQUEST['uri'],
				"p" => 'sioc:post',
				"o" => $post
			),	
	 		array(
				"s" => $post,
				"p" => 'dcterms:title',
				"o" => $_REQUEST['title']
			),

			array(
				"s" => $post,
				"p" => 'dcterms:created',
				"o" => $post_date
			),
			array(
				"s" => $post,
				"p" => 'sioc:content',
				"o" => $_REQUEST['comment']
			),
			array(
				"s" => $post,
				"p" => 'sioc:hasCreator',
				"o" => $account
			),
			array(
				"s" => $account,
				"p" => 'sioc:userAccount',
				"o" => $_REQUEST['author']
			)
		);
		@$records = $this->arcmodel->addTriples($triples);	
		echo json_encode(array("post" => $post, "date" => $post_date));
	}
}