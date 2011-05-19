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
<p>Footprinted Application Programming Interface</p>
	<p>&bull; <a>API/Search</a> Params: name</p>
	<p>&bull; <a>API/All</a> Params: name</p>
	<p>The results are send in JSON by default.</p>
<p>More detailed API documentation can be found <a>in the wiki.</a></p>
<p>Footprinted Linked Data endpoint is available <a href="http://footprinted.org/endpoint/i/">here</a></p>
	</div>
	</div>
	
	<div id="columnright">
		<?$this->load->view('/standard/aboutmenu_view.php');?>
	</div>
	
</p>
<?=$footerDisplay;?>
</body>
</html>
