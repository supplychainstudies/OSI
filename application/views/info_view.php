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
<p>We want sustainability information to be open, free and easy to use.</p>
<p>Footprinted is a linked database containing environmental impact information for resources, processes and products. The information is stored as <a>linked data</a> and compatible with existing semantic standards for sustainability accounting. Footprinted is accessible remotely through an <a>API</a>, published as Open Data, and it is meant to be accessed, remixed and mashed-up. </p>
<p>We want to create a community of environmental experts that create, discuss and evaluate the data. Giving them the technological affordances and the motivation to open up their data and share. Our goal is to ensure sustainability and environmental related data should be open, transparent, community driven and free to use. </p>
	</div>
	</div>
	
	<div id="columnright">
		<?$this->load->view('/standard/aboutmenu_view.php');?>
	</div>
	
</p>
<?=$footerDisplay;?>
</body>
</html>
