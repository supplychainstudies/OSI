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

	<div id="columnwide">
		<div id="about">	
			<p>Import EcoSpold files to Footprinted:</p>
			<br/>
			<form enctype="multipart/form-data" action="/converter" method="POST">
				Choose format:
				<select name="format">
				  <option value="eco1">Ecospold1</option>
				  <option value="eco2">Ecospold2</option>
				  <option value="ilcd">ILCD</option>
				</select>
				<br/>
			<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
			Choose a file to upload: <br/><input name="uploadedfile" type="file" /><br />

			<br/>
			<input type="submit" value="Upload File" />
			</form>
			<p><a href="/">Cancel</a></p>
			<p><a href="/lca/create">Create a footprint by hand</a></p>
			<br/>
		</div>
	</div>
			<?=$footerDisplay;?>
		</div>

		<?=$scripts;?>	
		</body>
	</html>