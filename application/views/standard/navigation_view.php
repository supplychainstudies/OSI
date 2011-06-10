<div id="header">
	<?
	if ($this->session->userdata('id') == false) {
		echo '<p class="imge"><a href="/"><img src="/assets/images/footprinted.png" alt="footprinted"></a></p>' ;
	} else {
		echo '<p class="imge"><a href="/lca/featured"><img src="/assets/images/footprinted.png" alt="footprinted"></a></p>';
	}

	?>
	
</div>
<div id="menu">
	<p class="menu">
	<a href="/about">About </a>| 
	
<?
if ($this->session->userdata('id') == false) {
	echo "<a id='opendialog'>Login </a>" ;
} else {
	echo '<a href="/search">Search </a> | ';
	echo '<a href="/lca/create">Contribute </a> | '; 
	echo "<a href='/users/dashboard'>Your dashboard (". $this->session->userdata('id') .")</a>";
}
	
?>
	</p>
</div>

<div id="logindialog" title="Basic dialog" style="display:none">
	<div id="login">
		<form method="post" action="/users/">
				<? if ($this->session->userdata('loginfail') == true) { echo "Your login failed. Try Again?"; } ?>
					<label>User Name </label>
						<input id="user_name" name="user_name" type="text" /> 
							<label>Password</label>
							<input id="password" name="password" type="password" />
						<div id="loginsubmit"><input type="submit" value="Login" /></div>				
		</form>
	</div>
	<div id="openid"><p> <a href="/users/register">Register</a>
	</p></div>
</div>

<script>
	$(function() {
		$( "#logindialog" ).dialog({
			autoOpen: false, resizable: false, draggable: false, width: 400, height: 300, title: "Login", modal: true
		});
	});	
	$(function() {
		$( "#opendialog" ).click(function(){
			$("#logindialog").dialog('open');
			return false;
		});
	});
	
</script>