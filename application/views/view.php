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
			<h1 class="hand">We want sustainability information to be free, open and easy to use.</h1>
			<br/><br/>
			<p><?

				if(isset($links) == true) {

					echo $links;

				}

			?></p>
			<p><a href="/info/browse">Browse resource</a></p>
			<p><a href="/info/create">Create new resource</a></p>
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