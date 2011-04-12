<!DOCTYPE html>
<?=$metaDisplay;?>
<html>
<head>
	<title><?=$title?></title>
	<?=$scripts;?>
	<?=$styles;?>
	<?=$headerDisplay;?>
</head>

<body id="home">
	<div id ="contentwrap">	
		
	<?= $navigationDisplay;?>

	<div id="columnleft">
		<div id="content">
			
			
			<?=$view_string;?>
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
			<p><a href="/">Browse resources</a></p>
			<p><a href="/create/start">Create new resource</a></p>
			<br/>
	</div>
			<?=$footerDisplay;?>
		</div>

		<?=$scripts;?>	
		</body>
	</html>