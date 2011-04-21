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
	
	
	<div id="columnleft">
		<?	if (isset($user_data) == true) { 
				echo "<h1 class='grande'>Welcome back, ".$user_data["user_name"]."</h1>";
				echo "<p><b>Your username: </b>".$user_data["user_name"]."</p>";
				echo "<p><b>Your email: </b>".$user_data["user_email"]."</p>";
 		} 
		?>
		<div class="dashboardcolumn">
			<h1>Your Footprints</h1>
			<p></p>
		</div>
		<div class="dashboardcolumn">
			<h1>Your Last Comments</h1>
			<p></p>
		</div>
		<div class="dashboardcolumn">
			<h1>Your Favorites</h1>
			<p></p>
		</div>
	</div>	
	<div id="columnright">
		<div class="wide aoi square"><h2><a href="/users/edit">Edit your info</a></h2></div>
		<div class="wide aoi square"><h2><a href="/create/start">Create new data</a></h2></div>
		<div class="wide aoi square"><h2><a href="/users/logout">Logout</a></h2></div>
	</div>
	<?=$footerDisplay;?>
	</div>
	
	<?=$scripts;?>	
</body>
</html>