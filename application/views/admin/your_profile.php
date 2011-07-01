<!DOCTYPE html>
<?=$metaDisplay;?>
<html>
<head>
	<title><?=$title?></title>
	
	<?=$styles;?>
	<?=$headerDisplay;?>
	
	<script type="text/javascript" src="http://footprinted.org/assets/scripts/tinymce/tiny_mce.js"></script>

	<script type="text/javascript">
	tinyMCE.init({
	        mode : "textareas"
	});
	</script>
	
	
</head>

<body id="home">
	<div id ="contentwrap">	
	<?= $navigationDisplay;?>
	
	<div id="columnleft">
		<div id="about">
			<p><b><? echo $set[0]->firstname." ".$set[0]->surname ;?></b></p>
			<?
			//$graurl = md5( strtolower( trim( $set[0]->user_email) ) );
			//echo '<img src="http://www.gravatar.com/avatar/'.$graurl.'?s=200?d="/>';
			?>
			<p><b>Email: </b>
			<? echo $set[0]->user_email; ?></p>
			<p><b>Bio:</b>
			<? echo $set[0]->bio;?></p>
			<br/>
			<? foreach($published as $lca){
				var_dump($lca);
			}?>	
		</div>
	</div>
	<div id="columnright">
		<br/><br/>
		<div class="menuabout"><h2><a href="/users/allusers">Back to all users</a></h2></div>
	</div>
	<?=$footerDisplay;?>
	</div>

</body>


</html>