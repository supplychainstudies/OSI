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
		<?		
			if (isset($user_data) == true) {
				foreach ($user_data as $key => $value) {
					echo $key . ": " . $value . "<br />";
				}
			}	
 			 ?>
	</div>	

	<?=$footerDisplay;?>
	</div>
	
	<?=$scripts;?>	
</body>
</html>