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
	//        mode : "textareas"
	//});
	</script>
	
	
</head>

<body id="home">
	<div id ="contentwrap">	
	<?= $navigationDisplay;?>
	
	<div id="columnleft">
		<div id="about">
			<div id="editadmins">
			<p>Edit text for <?= $set[0]->title;?></p>
			<?
			echo form_open('admin/savetext');
			?>
			<fieldset>
			<? echo '<input type="hidden" name="id" readonly="true" value="'.$set[0]->id.'"/>';?>
			<textarea id="text" name="text" rows="15" cols="80"><?php echo $set[0]->text;?></textarea>
			<br/><br/>
			<input type="submit" value="Save" />
			</fieldset>
			
	</div></div></div>

	<?=$footerDisplay;?>
	</div>

</body>


</html>