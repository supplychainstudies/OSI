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
	<h1 class="title">Create</h1>		
		<? echo $form_string; ?>
	</div>	

	<?=$footerDisplay;?>
	</div>
	
	<?=$scripts;?>	
</body>
</html>