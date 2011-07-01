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
		
		<div id="about">
			<p>Your API key is: <b> <?= $key ?></b></p>
			<p><a href="http://footprinted.org/users/dashboard">Back to dashboard</a></p>
			<p><a href="http://footprinted.org/about/api">Learn more about our API</a></p>	
		</div>
	</div>	

	<?=$footerDisplay;?>
	</div>
</body>
</html>