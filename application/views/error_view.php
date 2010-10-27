<!DOCTYPE html>
<?=$metaDisplay;?>
<html>
<head>
	<title><?=$title?></title>
	
	<?=$styles;?>
	<?=$headerDisplay;?>
</head>

<body id="home">	
	<?= $navigationDisplay;?>
	<h1 id="title">Sourcemachine</h1>
	<div id="wrapper" class="shadow round">
		<div id="content">
			<h1>The page (<strong><?=$url;?></strong>) was not found.</h1>	
		</div>
		<?=$sidebarDisplay;?>
		<div class="clear"></div>
	</div>
	<?=$footerDisplay;?>
	<?=$scripts;?>	

</body>
</html>