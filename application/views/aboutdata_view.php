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
<p>Footprinted combines different <a>ontologies</a> for structuring the data:</p>
<p>&bull; <a>Earthster</a> ontology is the base for describing the environmental impacts.</p>
<p>&bull; <a>Qudtu</a> ontology is used for describing units of measurement.</p>
<p>&bull; <a>OpenCyc</a> is used as a general concept ontology.</p>
<p>&bull; <a>Foaf</a> is used as for users and authors.</p>
<p>Footprinted contains aggregates information from some existing data sets, including:</p>
<p>&bull; Foodprint</p>
<p>&bull; Canadian Raw Material Database</p>
<p>&bull; Okala 2007</p>
	</div>
	</div>
	
	<div id="columnright">
		<?$this->load->view('/standard/aboutmenu_view.php');?>
	</div>
	
</p>
<?=$footerDisplay;?>
</body>
</html>
