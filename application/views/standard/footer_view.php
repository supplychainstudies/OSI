<div class="clear"></div>

<div id="footer">
	<div class="footer-list">
	<ul>
	<li><h2>Footprinted.org</h2></li>
	<li><p>Is an service for sharing and using environmental impact information in open formats using linked data.<br/><br/>
		Footprinted is still in beta version under development.</p></li>
	</ul>	
	</div>
	<div class="footer-list">
	<ul>
	<li><p>More information</p></li>
	<li><p><a href="/">Home</a></p></li>
	<li><p><a href="/about">About</a></li>
	<li><p><a href="/about/team">Team</a></p></li>
	<li><p><a href="http://twitter.com/footprinted" target="_blank">News</a></p></li>
	</ul>	
	</div>
	<div class="footer-list">
	<ul>
	<li><p>A project of</p></li>
	<li><p><a href="http://cesc.kth.se" target="_blank">CESC KTH</a></p></li>
	<li><p><a href="http://sourcemap.com" target="_blank">Sourcemap</a></p></li>
	<li><p><a href="http://media.mit.edu" target="_blank">MIT Media Lab</a></p></li>
	<li><p><a href="http://uwaterloo.ca" target="_blank">U. Waterloo</a></p></li>
	</ul>	
	</div>
	<div class="footer-list">
	<ul>
	<li><p>Do more</p></li>
	<li><p><a href="/search">Search</a></p></li>
	<li><p><a href="/about/API">API</a></p></li>
	
	<li><p><a href="">Source Code: coming soon</a></p></li>
		<li><p><a href="mailto:info@footprinted.org">Contact</a></p></li>
	</ul>	
	</div>
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
<script src="http://footprinted.org/assets/scripts/jquery/jquery-1.5.1.min.js" type="text/javascript"></script>
<script src="http://footprinted.org/assets/scripts/jquery/jquery-ui-1.8.11.custom.min.js" type="text/javascript" ></script>
<script src="http://footprinted.org/assets/scripts/jquery/jquery.masonry.min.js" type="text/javascript"></script>
<script>
	$("#logindialog").dialog({
			autoOpen: false, resizable: false, draggable: false, width: 400, height: 300, title: "Login", modal: true
		});
	$( "#opendialog" ).click(function() {
			$("#logindialog").dialog('open');
			return false;
		});
	
</script>

