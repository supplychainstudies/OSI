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
		<br/><h1 class='grande'>All users</h1>
		<? foreach($allusers as $user){	
			echo '<div class="medium grey square"><div class="squaretext"><p><a href="/users/showprofiles?id='.$user->user_name.'" title="'.$user->bio.'"><b>'.$user->firstname . " " . $user->surname.'</a></b></p></div></div>';
		}?>
	</div>
	<?=$footerDisplay;?>
	</div>

</body>


</html>