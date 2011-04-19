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
	<?= $navigationDisplay;?>
	
	<div id="columnleft">
		<div id="all_resources">
				<?
					foreach ($set as $row) {
						// Remove the opensustainability part of the url
						$myString = str_replace ("http://db.opensustainability.info/rdfspace/lca/", "", $row['link']);
						echo '<div class="resource"><p><a href="lca/view/'.$myString.'">'.$row['name'].'</a><p/></div>';
					}
				?>
		</div>
	</div>


<div id="columnright">
	<h1>Welcome to footprinted.org we work for sustainability information to be free, open and easy to use.</h1>
	<br/><br/>
	<p><a href="/create/start">Create new resource</a>
	</div>
	<?=$footerDisplay;?>
</div>

<?=$scripts;?>	
</body>
</html>