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
		<div id="all_resources">
				<?
					foreach ($set as $row) {
						// Remove the opensustainability part of the url
						$myString = str_replace ("http://opensustainability.info/", "", $row['link']);
						echo '<div class="resource"><p><a href="info/showJSON/'.$myString.'">'.$row['processName'].'</a><p/></div>';
						/*echo '<a href="/info/showRDF/'.$row['link'].'">RDF</a>';
						echo '<a href="/info/showJSON/'.$row['link'].'">JSON</a>';*/
					}
				?>
		</div>
	</div>


<div id="columnright">
	<h1 class="hand">We want sustainability information to be free, open and easy to use.</h1>
	<br/><br/>
	<p>Login</p>
	<p>Register</p>
	<p><a href="/info/create">Create new</a></p>
	</div>
	<?=$footerDisplay;?>
</div>

<?=$scripts;?>	
</body>
</html>