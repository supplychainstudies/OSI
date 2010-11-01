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
		<h1><? ?></h1>
		<p><b>URI: </b><? echo 'http://opensustainability.info/'.$URI; ?></p>
		<h2>Emissions</h2>
		<?	foreach($set as $s){
			echo '<p>'.$s['impactCategory'].'<b> '.$s['impactCategoryValue'].'</b> '.$s['impactCategoryUnit'].'</p>';
			} ?>
		<p><?	echo '<a href="/info/showRDF/'.$URI.'">Show RDF</a>';?></p>
		<p><?	echo '<a href="/info/showJSON/'.$URI.'">Show JSON</a>';?></p>
		</div>

		<?=$footerDisplay;?>
	</div>

	<?=$scripts;?>	
	</body>
</html>