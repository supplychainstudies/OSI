

<!DOCTYPE html>
<html>
<head>
	<?=$scripts;?>
	<?=$styles;?>
</head>

<body id="home">	
	<div style="clear: both;">
	<? 
		if ($header == "login") {
			include_once('parts/header_login.php'); 
		} else {
			include_once('parts/header_loggedin.php'); 
		}
	?>
	</div>
		<div id="content">
			<? echo $view_string; ?>
		</div>
	</div>
	<?
		if(isset($comments) == true) {
			include_once('parts/comments.php');
		}
	?>
</body>
</html>