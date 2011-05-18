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
		<div id="about">
		<img src="http://www.opensource.org/files/osi_standard_logo.png" width="100px"/>			
		<p>Footprinted is <a href="http://www.opensource.org/" target="_blank">open source</a> and based on open source tools.</p>

		<p>You can find our code at GitHub under MIT license</p>
		<p>We are using the following open standards:</p>
		<p><a href="" target="_blank">PHP</a> | <a href="" target="_blank">HTML+CSS</a> | <a href="" target="_blank">SPARQL</a></p>
		<p>We are using the following open source projects:</p>
		<p><a href="" target="_blank">Codeigniter</a> | <a href="" target="_blank">ARC</a> | <a href="" target="_blank">JQuery</a> | <a href="" target="_blank">JQuery UI</a> | <a href="" target="_blank">Masonry</a></p>
	</div></div>
	<div id="columnright">
		<?$this->load->view('/standard/aboutmenu_view.php')?>;
	</div>
	<?=$footerDisplay;?>
	</div>
	
	<?=$scripts;?>	
</body>
</html>