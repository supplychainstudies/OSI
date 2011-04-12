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
			<script>
				$(document).ready(function() {
				<? 
				$active_record = false; 
				foreach ($info as $key=>$record) { 
					if ($active_record == true) {
					?>
				 	$('#dataset_<?=$key; ?>').hide();
				<? }
					$active_record = true;
				 } ?>
				});	
				$(					
			</script>
			
			<? 
			foreach ($info as $key=>$record) { 
				?>
				<a href="#" id="tabthis"><?=$key;?></a>
			<? 
			 } ?>
			<? 
			
			function regurgitate($record, $multiple) {
				foreach ($record as $_key=>$section){
					if (is_array($section) == true) {
						echo "<h1>" . $_key . "</h1>";
						regurgitate($section, $multiple."[" . $_key . "]");
					} else {
						echo $_key . "<input type=\"text\" name=\"data". $multiple . "[" . $_key . "]\" value=\"" . $section . "\" /><br />\n";
					}
				}
			}
			
			
			foreach ($info as $key=>$record) { ?>
				
			<div id="dataset_<?=$key; ?>">
			<div>Description</div><div>
			</div>
			<?
			
			regurgitate($record , "");
			/*
			foreach ($record as $_key=>$section){
				echo "<h1>" . $_key . "</h1>";
				foreach ($section as $field_name=>$field_value) {
					echo $field_name . "<input type=\"text\" name=\"data[" . $key . "][" . $_key . "][" . $field_name . "]\" value=\"" . $field_value . "\" /><br />\n";
				}
			} */
			?>
			<?
			//<div>Inputs and Outputs</div>
			//<div id="flows">
			// foreach ($record['exchanges'] as $exchange) {
			//	echo $exchange['direction'] . " - " . $exchange['name'] . " - " . $exchange['meanValue'] . " - " . $exchange['unit'] . "<br/>\n"; 
			//	echo $exchange['comment'] . "<br /><br/>\n";				
			//}</div>								
			//<div id="Sources">
			// foreach ($record['sources'] as $source) {
			//	echo $exchange['title'] . "<br/>\n"; 				
			//}</div>
			?>	
					
			</div>
			
			<? } ?>
			
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