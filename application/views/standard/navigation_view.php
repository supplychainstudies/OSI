<div id="header">
	<p class="imge"><a href="/"><img src="<?=base_url()?>assets/images/footprinted.png" alt="footprinted"></a></p>
</div>
<div id="menu">
	<p class="menu">
	<a href="/about">About </a>| 
	<a href="/browse">Browse </a>| 
<?

if ($this->session->userdata('id') == false) {
	include_once('header_login.php'); 
} else {
	include_once('header_loggedin.php'); 
}

?>
	</p>
</div>