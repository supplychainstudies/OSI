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

	<div id="columnwide">
		<div id="content">

			<? echo $view_string; ?>
			<?

				if(isset($comment) == true) {

					include_once('parts/comments.php');

				}

			?>
		</div>

	</div>


			<?=$footerDisplay;?>
		</div>

		<?=$scripts;?>	
		</body>
	</html>