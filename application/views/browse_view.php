<!DOCTYPE html>
<?=$metaDisplay;?>
<html>
<head>
	<title>Footprinted.org</title>
	
	<?=$styles;?>
	<?=$headerDisplay;?>
</head>

<body id="home">
	<div id ="contentwrap">	
	<?= $navigationDisplay;?>
	
	<div id="columnleft">
		<div class="big grey square"><p>
			<?
			foreach ($featured as $info) {
				echo $info;
			}
			?>
		</p></div>
		<div id="all_resources">
				<?
					foreach ($set as $row) {
						// Remove the opensustainability part of the url
						$myString = str_replace ("http://db.opensustainability.info/rdfspace/lca/", "", $row['link']);
						echo '<div class="small blue square"><p><a href="lca/view/'.$myString.'">'.$row['name'].'</a><p/></div>';
					}
				?>
		</div>
	</div>


<div id="columnright">
	<div class="wide aoi square"><p>We work for sustainability information to be free, open and easy to use.</p></div>
	<div class="wide aoi square"><h2><a href="/about">About Footprinted.org</a></h2></div>
	<div class="wide aoi square"><h2><a href="/create/start">Create new data</a></h2></div>
	<div class="wide aoi square"><h2>Latest news:</h2></div>
	 <? foreach ($twitter as $tweet) {
		echo '<div class="wide aoi square"><p>';
		echo $tweet['title'];
		echo '</p></div>';
	  }
	?>
	<div class="wide aoi square"><p><a href="http://twitter.com/footprinted">Follow us in twitter</a></p></div>
</div>
	<?=$footerDisplay;?>
</div>

<?=$scripts;?>	
</body>
</html>