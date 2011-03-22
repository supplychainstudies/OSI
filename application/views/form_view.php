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
			if (isset($pass_data) == true) {
				foreach ($pass_data as $key => $value) {
					echo "<input type=\"hidden\" name=\"pre_" . $key . "\" value=\"" . $value . "\" />";
				}
			}	
 			
			echo $form_string; ?>
	</div>	

	<?=$footerDisplay;?>
	</div>
	
	<?=$scripts;?>	
</body>
</html>