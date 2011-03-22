<?

if ($this->session->userdata('id') == false) {
	include_once('header_login.php'); 
} else {
	include_once('header_loggedin.php'); 
}

?>