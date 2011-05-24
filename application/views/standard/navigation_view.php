<div id="header">
	<p class="imge"><a href="/"><img src="<?=base_url()?>assets/images/footprinted.png" alt="footprinted"></a></p>
</div>
<div id="menu">
	<p class="menu">
	<a href="/about">About </a>| 
	<a href="/">Browse </a>| 
<?
if ($this->session->userdata('id') == false) {
	echo "<a id='opendialog'>Login </a>" ;
} else {
	echo $this->session->userdata('id') . " | <a href='/users/logout'>Logout</a>";
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
	<div id="openid"><p> <a class="rpxnow" onclick="return false;"
	href="https://opensustainability.rpxnow.com/openid/v2/signin?token_url=http%3A%2F%2Fosi%2Fusers%2F">Use an Open ID login or Register</a>
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