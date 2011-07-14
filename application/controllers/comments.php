<?php
/**
 * Controller for comments (Protected only for logged in users)
 * 
 * @version 0.8.0
 * @author info@footprinted.org
 * @package opensustainability
 * @subpackage controllers
 * @uses 
 */



class Comments extends FT_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model(Array('commentsmodel'));
		$this->load->helper(Array('nameformat_helper'));	
	}
	
	public function post() {
		$this->check_if_logged_in();
		$post = toBNode('post');
		$account = toBNode('account');
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
		@$records = $this->commentsmodel->addTriples($triples);	
		echo json_encode(array("post" => $post, "date" => $post_date));
	}
}