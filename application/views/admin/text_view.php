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
			<p>Administration for texts</p>
			<?
			foreach($set->result() as $s){
				echo "<p>".$s->title.":<a href='/admin/viewtext?id=".$s->id."'> View</a> | <a href='/admin/edittext?id=".$s->id."'> Edit </a></p>";
			}
			?>
	</div></div>

	<?=$footerDisplay;?>
	</div>

</body>
</html>