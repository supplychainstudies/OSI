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
		<h1>Existing resources<h1>
				<?
					foreach ($set as $row) {
						echo '<p>'.$row['processName'];
						echo '<a href="/info/showRDF/'.$row['link'].'">RDF</a>';
						echo '<a href="/info/showJSON/'.$row['link'].'">JSON</a><p/>';
					}
				?>

	</div>

	<?=$footerDisplay;?>
</div>

<?=$scripts;?>	
</body>
</html>