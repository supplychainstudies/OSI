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
		<div id="content">

			<? echo $view_string; ?>
			<?

				if(isset($comments) == true) {

					include_once('parts/comments.php');

				}

			?>
		</div>

	</div>


	<div id="columnright">
	<? /*

		if ($header == "login") {

			include_once('parts/header_login.php'); 

		} else {

			include_once('parts/header_loggedin.php'); 

		}*/

	?>
	</div>
			<?=$footerDisplay;?>
		</div>

		<?=$scripts;?>	
		</body>
	</html>