<!DOCTYPE html>
<?=$metaDisplay;?>
<html>
<head>
	<head>
		<title><?=$title?></title>

		<?=$styles;?>
		<?=$headerDisplay;?>
	</head>
</head>
<body id="home">
	<div id ="contentwrap">	
	<?= $navigationDisplay;?>

	<div id="columnleft">
		<div id="about">
			<?= $text[0]->text ?>
	</div>
	</div>
	
	<div id="columnright">
		<?$this->load->view('/standard/aboutmenu_view.php');?>
	</div>
	
</p>
<?=$footerDisplay;?>
</body>
</html>
