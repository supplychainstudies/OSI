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
				echo "<h1 class='grande'>Welcome, ".$user_data["user_name"]."</h1>";
				echo "<p class='piccolo'><b>Your email: </b>".$user_data["user_email"]."</p>";
 		} 
		?>
		<br/>
		<div id="about">
			<p><a href='/lca/featured'>Start by exploring the existing Footprints!</a></p>
			<? 
			if ($user_activity == true) {
			echo "<p>Your Footprints</p>";
			foreach ($user_activity as $fp) {
				
				echo "<a href='".$fp["uri"]."'/><p>".$fp["title"]."</p></a>>";
				
			} 
			}else{
				 echo "<p>You don't have any Footprint yet. <a href='/create/start'>Contribute a new footprint</a></p>";
			}
			?>
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

		<div id="columnright">
			<div class="menuabout"><h2><a href="http://footprinted.org/lca/featured">Browse the data</a></h2></div>
			<div class="menuabout"><h2><a href="http://footprinted.org/create/start">Contribute new data</a></h2></div>
			<div class="menuabout"><h2><a href="http://footprinted.org/users/getAPIkey">API key</a></h2></div>
				<div class="menuabout"><h2><a href="/users/logout">Logout</a></h2></div>
				<? 
					if($this->session->userdata('id') == ("bianca.sayan" || "zapico" || "leo"))  {
					echo '<div class="menuabout"><h2><a href="http://footprinted.org/admin/texts">Admin texts</a></h2></div>';
					}
				?>
				
		</div>
	</div>	

	<?=$footerDisplay;?>
	</div>
</body>
</html>