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
		<h1>Name</h1>

		<p><?= Print_r($triples['http://opensustainability.info/vocab#processName']); ?></p>
		<p></p>
		<h1>Impact</h1>
		<p><?= Print_r($impacts); ?></p>
		
		</div>

		<?=$footerDisplay;?>
	</div>

	<?=$scripts;?>	
	</body>
</html>