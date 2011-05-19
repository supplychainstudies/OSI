<? $this->load->helper("linkeddata_helper"); ?>
<? $this->load->helper("impact_helper"); ?>
<!DOCTYPE html>
<?=$metaDisplay;?>
<html>
<head>
	<title>Footprinted.org</title>
	
	<?=$styles;?>
	<?=$headerDisplay;?>



</head>
<body id="home">
	<div id ="contentwrap">	
	<h1>Admin featured</h1>
	
	<div id="columnwide">
				<?
					
					foreach ($set as $row) {
						// Remove the footprinted part of the url
						$myString = str_replace ("http://footprinted.org/rdfspace/lca/", "", $row['uri']);
						echo '<div class="small blue square"><p><a href="/admin/addAsFeatured/?URI='.$myString.'" target="_blank">'.$row['label'].'</a><p/></div>';
					}
				?>
	</div>
	<?=$footerDisplay;?>
</div>

</body>
</html>