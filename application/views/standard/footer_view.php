<div class="clear"></div>

<div id="footer">
	<div class="footer-list">
	<ul>
	<li><h2>Footprinted.org</h2></li>
	<li><p>Free and open environmental impact data<br/><br/>
		Footprinted is in beta.</p></li>
	</ul>	
	</div>
	<div class="footer-list">
	<ul>
	<li><p>More information</p></li>
	<li><p><a href="/about">About</a></li>
	<li><p><a href="/about/team">Team</a></p></li>
	<li><p><a href="http://twitter.com/footprinted" target="_blank">News</a></p></li>
	<li><p><a href="mailto:info@footprinted.org">Contact</a></p></li>
	</ul>	
	</div>
	<div class="footer-list">
	<ul>
	<li><p>Documentation</p></li>
	<li><p><a href="/documentation/data">Data</a></p></li>
	<li><p><a href="/documentation/API">API</a></p></li>
	<li><p><a href="/documentation/endpoint">SPARQL Endpoint</a></p></li>	
	<li><p><a href="https://github.com/Sourcemap/Footprinted">Source Code@Github</a></p></li>
	</ul>	
	</div>
	<div class="footer-list">
	<ul>
	<li><p>A project of</p></li>
	<li><p><a href="http://sourcemap.com" target="_blank">Sourcemap</a></p></li>
	<li><p><a href="http://cesc.kth.se" target="_blank">CESC KTH</a></p></li>
	<li><p><a href="http://media.mit.edu" target="_blank">MIT Media Lab</a></p></li>
	<li><p><a href="http://uwaterloo.ca" target="_blank">U. Waterloo</a></p></li>
	</ul>	
	</div>
</div>
<div id="logindialog" title="Basic dialog" style="display:none">
	<div id="login">
		<form method="post" action="/users/">
					<label>User Name </label>
						<input id="user_name" name="user_name" type="text" tabindex="1" onblur="javascript:document.getElementById('password').focus()" /> 
					<label>Password</label>
						<input id="password" name="password" type="password" onblur="javascript:document.getElementById('submit').focus()" />
					<div id="loginsubmit"><input type="submit" value="Login" onblur="javascript:document.getElementById('register').focus()" /></div>	
					<br />				
		</form>
	</div>
	<div id="openid"><p><a id="register" href="/users/register" onblur="javascript:document.getElementById('user_name').focus()">Register</a></p></div>		
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

