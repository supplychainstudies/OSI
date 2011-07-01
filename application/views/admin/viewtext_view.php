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
			<p>Admin texts:</p>
			<?
			foreach($set->result() as $s){
				echo "<p>".$s->title."</p>";
				echo "<p>".$s->text."</p>";
			}
			?>
	</div></div>

	<?=$footerDisplay;?>
	</div>

</body>
</html>