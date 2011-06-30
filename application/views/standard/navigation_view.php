<div id="header">
	<p class="imge"><a href="/"><img src="http://footprinted.org/assets/images/logobeta.png" alt="footprinted"></a></p>
</div>
<div id="menu">
	<p class="menu">
	<a href="/about">About </a>| 
	<a href="/search">Search </a>| 
	
<?
if ($this->session->userdata('id') == false) {
	echo "<a id='opendialog'>Login </a>" ;
} else {
	echo '<a href="/lca/create">Contribute </a> | '; 
	echo "<a href='/users/dashboard'>Your dashboard (". $this->session->userdata('id') .")</a>";
}
	
?>
	</p>
</div>


