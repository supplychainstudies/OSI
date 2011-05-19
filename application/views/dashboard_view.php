<!DOCTYPE html>
<?=$metaDisplay;?>
<html>
<head>
	<title><?=$title?></title>
	
	<?=$styles;?>
	<?=$headerDisplay;?>
</head>

<body id="home">
	<div id ="contentwrap">	
	<?= $navigationDisplay;?>
	
	
	<div id="columnwide">
		<?	if (isset($user_data) == true) { 
				echo "<h1 class='grande'>Welcome back, ".$user_data["user_name"]."</h1>";
				echo "<p class='piccolo'><b>Your username: </b>".$user_data["user_name"]."</p>";
				echo "<p class='piccolo'><b>Your email: </b>".$user_data["user_email"]."</p>";
 		} 
		?>
		<br/>
		<div class="dashboardcolumn">
			<h1><center>Your Footprints</center></h1><br/>
			<? 
			if (isset($user_activity) == true) {
			foreach ($user_activity as $fp) {
				echo "<div class='dashboardunit'><a href='".$fp["uri"]."'/><p>".$fp["title"]."</p></a></div>";
				
			} 
			}else{
				 echo "<h1>You don't have any Footprint yet. <a href='create/start'>Create a new footprint entry!</a></h1>";
			}
			?>
			<p></p>
		</div>
		<div class="dashboardcolumn">
			<h1><center>Your Last Comments</center></h1><br/>
			<h1>You have not commented any Footprint yet. <a href='/'>Explore the existing Footprints, you can post comments on them.</a></h1>
			<p></p>
		</div>
		<div class="dashboardcolumn">
			<h1><center>Your Favorites</center></h1><br/>
			<h1>Fav feature is coming soon.</h1>
			<p></p>
		</div>

		<div id="columnright">
			<div class="menuabout"><h2><a href="/users/edit">Edit your info</a></h2></div>
			<div class="menuabout"><h2><a href="/create/start">Create new data</a></h2></div>
			<div class="menuabout"><h2><a href="/users/logout">Logout</a></h2></div>
			<div class="menuabout">
				<p class='piccolo'>Share footprinted!</p>
				<ul id="share-options">			
						<li id="facebook_share"><p> 
							<a href='http://facebook.com/sharer.php?u=http://footprinted.org&t=Check+out+footprinted.org' target="_blank">Facebook</a>
							</p></li>
						<li id="twitter_share"><p>
							<a href="http://twitter.com/home?status=Check+out+footprinted.org+at+http://footprinted.org" target="_blank">Twitter</a>
							</p></li>
						<li id="delicious_share"><p><a href="http://delicious.com/save" onclick="window.open('http://delicious.com/save?v=5&noui&jump=close&url=http://footprinted.org'&title='+encodeURIComponent(document.title), 'delicious','toolbar=no,width=550,height=550'); return false;">Delicious</a></p></li>
					</ul>
				</div>	
		</div>
	</div>	

	<?=$footerDisplay;?>
	</div>
</body>
</html>