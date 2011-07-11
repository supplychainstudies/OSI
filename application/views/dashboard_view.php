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
		
		<?	
			if ($set[0]->firstname != ""){
				echo "<br/><h1 class='grande'>Welcome, ".$set[0]->firstname. " " .  $set[0]->surname."</h1>";
			}else{
				echo "<br/><h1 class='grande'>Welcome, ".$user_data["user_name"]."</h1>";
		}
		?>
		<br/>
<div id="about">
			<? 
			if ($published == true) {
			echo "<p>Your Footprints</p>";
			foreach ($published as $fp) {
				
				echo "<a href='".$fp["uri"]."'/><p>".$fp["title"]."</p></a>>";
				
			} 
			}else{
				 echo "<p>1. <a href='/search'>Start by browsing our Footprints!</a></p>";
				 echo "<p>2. You haven't created any Footprints yet. <a href='/create/start'>Click here to contribute</a></p>";
			}
			?>
			<p class='piccolo'>3. Question, comments, ideas? <a href='mailto:info@footprinted.org'>contact us</a>.</p>
			<p class='piccolo'>4. Share Footprinted:
						<a href='http://facebook.com/sharer.php?u=http://footprinted.org&t=Check+out+footprinted.org' target="_blank">Facebook</a>
						| <a href="http://twitter.com/home?status=Check+out+footprinted.org+at+http://footprinted.org" target="_blank">Twitter</a>
						| <a href="http://delicious.com/save" onclick="window.open('http://delicious.com/save?v=5&noui&jump=close&url=http://footprinted.org'&title='+encodeURIComponent(document.title), 'delicious','toolbar=no,width=550,height=550'); return false;">Delicious</a></p>
		</div>

		<div id="columnright">
			<div class="menuabout"><h2><a href="http://footprinted.org/lca/featured">Browse the data</a></h2></div>
			<div class="menuabout"><h2><a href="http://footprinted.org/create/start">Contribute new data</a></h2></div>
			<div class="menuabout"><h2><a href="http://footprinted.org/users/getAPIkey">API key</a></h2></div>
			<div class="menuabout"><h2><a href="http://footprinted.org/users/allusers">Community</a></h2></div>
			<div class="menuabout"><h2><a href="http://footprinted.org/users/editprofile">Edit your profile</a></h2></div>
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