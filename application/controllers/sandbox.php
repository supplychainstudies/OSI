<?php

class Sandbox extends SM_Controller {
	
	function Sandbox() {
		parent::SM_Controller();
	}
	
	function index() {
		if(isset($_REQUEST['submit']) == true) {
			var_dump($_REQUEST);
		} else {

		$viewstuff =  "<form action=\"http://opensustainability/index.php/sandbox\" method=\"post\"><input type=\"text\" name=\"mo_o[0][0]\" tabindex=\"1\" value=\"\" /><br /><input type=\"text\" name=\"me_e[0][0]\" tabindex=\"1\" value=\"\" /><br /><br /><input type=\"text\" name=\"mo_o[0][1]\" tabindex=\"1\" value=\"\" /><br /><input type=\"text\" name=\"me_e[0][1]\" tabindex=\"1\" value=\"\" /><br /><div id=\"extra_moo\" /></div><input type=\"button\" onClick=\"deep()\" value=\"\" /><input type=\"submit\" name=\"submit\" value=\"\" /></form>";
			$this->script(Array('sandbox.js'));
			$this->data("viewstuff", $viewstuff);
			$this->display("Sandbox", "sandbox_view");	

		}
	}
	
	function deploy() {
		//set up variables
		$theData = '<?xml version="1.0"?> ' .
		 '<deployment>' .
		 '<branch>master</branch>' . 
		 '<revision>20</revision>' . 
		 '<servers>opensustainability.sourcemap.org</servers> ' . 
		'</deployment> ';
		$url = 'sourcemap.codebasehq.com/opensustainability/opensustainability/deployments';
		$credentials = 'bianca@sourcemap.codebasehq.com:lcaforall';
		$header_array = array('Expect' => '',
		                'From' => 'User A');
		$ssl_array = array('version' => 'SSL_VERSION_SSLv3');
		$options = array('headers' => $header_array,
		                'httpauth' => $credentials,
		                'httpauthtype' => 'HTTP_AUTH_BASIC',
		                'protocol' => 'HTTP_VERSION_1_1',
		                'ssl' => $ssl_array);

		//create the httprequest object               
		$httpRequest_OBJ = new httpRequest($url, 'HTTP_METH_POST', $options);
		//add the content type
		$httpRequest_OBJ->setContentType = 'Content-Type: text/xml';
		//add the raw post data
		$httpRequest_OBJ->setRawPostData ($theData);
		//send the http request
		$result = $httpRequest_OBJ->send();
		//print out the result
		echo "<pre>"; print_r($result); echo "</pre>";

	}
}
?>
