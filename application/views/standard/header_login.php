<? ?>


<div id="login">
	<form method="post" action="/users/">
		<div class="level0">
			<? if ($this->session->userdata('loginfail') == true) { echo "Your login failed. Try Again?"; } ?>
			<ul class="layout">
					<li>

					<li>
						<label>User Name </label>
						<input id="user_name" name="user_name" type="text" /> 
					</li>
					<br/>
					<li>
						<label>Password</label>
						<input id="password" name="password" type="password" />
					</li>
				<br/>			
				<li>
					<input type="submit" value="Login" />
					<p> <a class="rpxnow" onclick="return false;"
					href="https://opensustainability.rpxnow.com/openid/v2/signin?token_url=http%3A%2F%2Fdb.opensustainability.info%2Fusers%2F">Use an Open ID login &rsaquo; &rsaquo;</a> </p>
				</li>						
			</ul>
		</div>
	</form>
</div>



<? ?>