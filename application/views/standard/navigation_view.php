<div id="header">
	<p class="imge"><a href="/"><img src="<?=base_url()?>assets/images/footprinted.png" alt="footprinted"></a></p>
</div>
<?

if ($this->session->userdata('id') == false) {
	include_once('header_login.php'); 
} else {
	include_once('header_loggedin.php'); 
}

?>