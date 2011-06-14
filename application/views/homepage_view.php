	<!DOCTYPE html>
<?=$metaDisplay;?>
<html>
<head>
<title>Footprinted: Free and open LCA environmental data.</title>
	
	<?=$styles;?>
	<?=$headerDisplay;?>



</head>
<body id="home">
	<div id ="contentwrap">	
	<?= $navigationDisplay;?>
	
	<div id="columnwide">
				<div id="about">
				<p>Open, free and easy to use environmental impact information. <a href="/search">Search</a> in the 500+ footprints available or explore some of the featured ones below:</p>
				<br/>
				</div>
					
				<?	foreach ($set as $parts) { 
					$parts = $parts[0]; ?>
						
						<div id="lca_title_lite">
								<h1>
								1 
								<?= $parts->unit ?> of
								<?	echo '<a href="/lca/view/'.$parts->uri.'">'; ?>
								<?= $parts->name ?></a></h1>
								<?
									if (isset($parts->ref) == true) {
									echo "<p>From: ".$parts->ref."</p>";
									}
								?>


							</div>

							<div id="lca_lite">
								<div id="lca_impact_lite">	
									<div id="tabs">
									<?
									$impacts = array();
									$impacts = array(
										array("value" => $parts->co2e, "color" => "#FF7C00", "unit" => "Kg", "max" => 2000, "impacttext" => "CO<sub>2</sub> (eq)"),
										array("value"=> $parts->water, "color" => "#45A3D8", "unit" => "L", "max" => 2000, "impacttext" => "Water"),
										array("value"=> $parts->waste, "color" => "#6B5344", "unit" => "Kg", "max" => 2000, "impacttext" => "Waste"),
										array("value"=> $parts->energy, "color" => "#E8BF56","unit" => "MJ", "max" => 20, "impacttext" => "Energy"));
										
									foreach ($impacts as $impact) {
										$size = round(sqrt($impact['max']*$impact['value']/pi()));
										if ($size > 82) { $size = 82;}
										if ($size < 20) { $size = 20;}
										$margin = (120-$size)/2;
										// Create a circle
										echo '<div class="tab_impact"><div class="tab_circle"><div style="width:'.$size.'px; height:'.$size.'px;margin-left:'.$margin.'px;margin-top:'.$margin.'px; background:'.$impact['color'].'; -moz-border-radius: 40px; -webkit-border-radius:40px;"></div></div>';
										echo '<div class="tab_nr"><p><nrwhite>' . round($impact['value'],2) . "</nrwhite> ". $impact['unit'] . "<p/></div>";
										echo  "<div class='tab_meta'><p>".$impact['impacttext']."</p></div>";
										echo "</div>";
									}							
									
									?>
									</div>
								</div>	

								<? if (isset($parts->country) == true ) {
									echo '<div id="maplite">';		
												$map = "http://maps.google.com/maps/api/staticmap?sensor=false&size=200x160&zoom=1&style=feature:road.local%7Celement:geometry%7Chue:0x00ff00%7Csaturation:100&style=feature:landscape%7Celement:geometry%7Clightness:-100&style=feature:poi.park%7Celement:geometry%7Clightness:-100&markers=size:big%7Ccolor:white%7C".$parts->country.'"';
									echo '<img src="'.$map.'" alt="'.$parts->country.'"/>';
									echo '<div id="infomap"><p>Location: <b>'.$parts->country.'</b></p></div>';
									echo "</div>";
								} ?>	
								<div class="ref_lite">
									<p>Year: <?= $parts->year ?></p>
								</div>
								<div class="ref_lite">
									<p>Category: 
										<? if (isset($parts->category) == true) {
											echo "<a href='/search?category=" . $parts->category . "'>";
											echo $parts->category;
											echo "</a>";
									}?></p>
								</div>
								<div class="ref_lite">
									<p><? echo "<a href='".$parts->uri. ".json'>";?>Export</a></p>
								</div>
								<div class="ref_lite">
									<p><? echo "<a href='/lca/view/".$parts->uri. "'>";?> More information</a></p>
								</div>
	
						</div>
						
					<? 
					// End for each
					} 
					?>

	</div>
	<?=$footerDisplay;?>
</div>
</body>
</html>