<? ?>


<div>
	<form method="post" action="/users/login">
		<h1 class="level0">Login</h1><div class="level0">
			<? if ($this->session->userdata('loginfail') == true) { echo "Your login failed. Try Again?"; } ?>
			<ul class="layout">
				<div class="toggle">
					<li>
					<div class="toggler"> Use an Open ID login &rsaquo; &rsaquo; </div>
					<li>
						<label>User Name </label>
						<input id="user_name" name="user_name" type="text" /> 
					</li>
					<br/>
					<li>
						<label>Password</label>
						<input id="password" name="password" type="password" />
					</li>
				</div>
				<br/>
				<div class="toggle" style="display: none">
					<li>
						<div class="toggler"> Use your OSI login &rsaquo; &rsaquo; </div>
					</li>					
					<li>
						<label>Open ID </label>
						<input id="open_id" name="open_id" type="text" /> 
					</li>	
				</div>				
				<li>
					<input type="submit" />
				</li>						
			</ul>
		</div>
	</form>
</div>
<script>


$('.toggler').click(function() {

	$('.toggle').slideToggle('slow', function() {

	// Animation complete.

	});

});

</script>


<? ?>