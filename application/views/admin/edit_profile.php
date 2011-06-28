<!DOCTYPE html>
<?=$metaDisplay;?>
<html>
<head>
	<title><?=$title?></title>
	
	<?=$styles;?>
	<?=$headerDisplay;?>
	
	<script type="text/javascript" src="http://footprinted.org/assets/scripts/tinymce/tiny_mce.js"></script>

	<script type="text/javascript">
	//tinyMCE.init({
	  //      mode : "textareas"
	//});
	</script>
	
	
</head>

<body id="home">
	<div id ="contentwrap">	
	<?= $navigationDisplay;?>
	
	<div id="columnleft">
		<div id="about">
			<div id="editadmins">
			<p>Edit your profile</p>
			<?
			echo form_open('users/saveprofile');
			?>
			<fieldset>
			<? echo '<input type="hidden" name="id" readonly="true" value="'.$set[0]->user_id.'"/>';?>
			<p>Email</p>
			<? echo '<input type="text" name="user_email" value="'.$set[0]->user_email.'"/>';?>
			<p>Your Real Name</p>
			<? echo '<input type="text" name="firstname" value="'.$set[0]->firstname.'"/>';?>
			<? echo '<input type="text" name="surname" value="'.$set[0]->surname.'"/>';?>
			<p>Your Bio</p>
			<textarea id="bio" name="bio" rows="15" cols="40"><?php echo $set[0]->bio;?></textarea>
			<br/><br/>
			<input type="submit" value="Save" />
			</fieldset>
			
	</div></div></div>

	<?=$footerDisplay;?>
	</div>

</body>


</html>