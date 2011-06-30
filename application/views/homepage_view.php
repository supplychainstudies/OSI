	<!DOCTYPE html>
<?=$metaDisplay;?>
<html>
<head>
<title>Footprinted: Free and open environmental impact data.</title>
	
	<?=$styles;?>
	<?=$headerDisplay;?>



</head>
<body id="home">
	<div id ="contentwrap">	
	<?= $navigationDisplay;?>
	
	<div id="columnwide">
				<div id="about">
				<p>Free and open environmental impact information. <a href="/search">Search</a> the <?= $nr?> footprints available or explore some of the featured ones below:</p>
				<br/>
				</div>
				<?	foreach ($set as $parts) { 
					$parts = $parts[0]; ?>
						
						<div id="lca_title_lite">
								<h1>
								1 
								<?= $parts->unit ?> of
								<?	echo '<a href="/'.$parts->uri.'">'; ?>
								<?= $parts->name ?></a></h1>
								<?
									if (isset($parts->category) == true) {
											$cat = explode(";",$parts->category); 
											echo "<p>A <a href='/search?category=" . $cat[0] . "'>";
											echo $cat[0];
											echo "</a> ";
									}
									
									if (isset($parts->ref) == true) {
										echo " from <a href='/search?ref=".$parts->ref."'>".$parts->ref."</a></p> ";
									}
									
								?>


							</div>

							<div id="lca_lite">
								<div id="lca_impact_lite">	
									<div id="tabs">
									<?
									$impacts = array(
										array("value" => $parts->co2e, "color" => "#FF7C00", "unit" => "kg", "max" => 1800, "impacttext" => "CO2e"),
										array("value"=> $parts->water, "color" => "#45A3D8", "unit" => "L", "max" => 1800, "impacttext" => "Water"),
										array("value"=> $parts->waste, "color" => "#6B5344", "unit" => "kg", "max" => 1800, "impacttext" => "Waste"),
										array("value"=> $parts->energy, "color" => "#E8BF56","unit" => "MJ", "max" => 180, "impacttext" => "Energy"));
									
									foreach ($impacts  as $impact) {
										if($impact['value'] != 0){
										$size = round(sqrt($impact['max']*$impact['value']/pi()));
										if ($size > 75) { $size = 75;}
										if ($size < 10) { $size = 10;}
										$marginright = (120-$size)/2;
										$margintop = (100-$size)/2;
										// Create a circle
										echo '<div class="tab_impact"><div class="tab_circle"><div style="width:'.$size.'px; height:'.$size.'px;margin-left:'.$marginright.'px;margin-top:'.$margintop.'px; background:'.$impact['color'].'; -moz-border-radius: 40px; -webkit-border-radius:40px;"></div></div>';
										echo '<div class="tab_nr"><p><nrwhite>' . round($impact['value'],2) . "</nrwhite> ". $impact['unit'] . "<p/></div>";
										echo  "<div class='tab_meta'><p>".$impact['impacttext']."</p></div>";
										echo "</div>";
										}else{
											echo '<div class="tab_impact"><div class="tab_circle"></div>';
											echo '<div class="tab_nr"><p>-<br/><p/></div>';
											echo  "<div class='tab_meta'><p>".$impact['impacttext']."</p></div>";
											echo "</div>";
										}							
									}
									?>
									</div>
								</div>	

								<? if (isset($parts->geography) == true ) {
									echo '<div id="maplite">';		
												$map = "http://maps.google.com/maps/api/staticmap?sensor=false&size=200x155&zoom=1&style=feature:road.local%7Celement:geometry%7Chue:0x00ff00%7Csaturation:100&style=feature:landscape%7Celement:geometry%7Clightness:-100&style=feature:poi.park%7Celement:geometry%7Clightness:-100&markers=size:big%7Ccolor:blue%7C".$parts->geography.'"';
									echo '<img src="'.$map.'" alt="'.$parts->geography.'"/>';
									echo '<div id="infomap"></div>';
									echo "</div>";
								} ?>	
								<div class="ref_lite">
									<p>Year: <b><?= $parts->year ?></b></p>
								</div>
								<div class="ref_lite">
									<p>Location: <b><?= $parts->geography?></b></p>
								</div>
								<div class="ref_lite">
									<p><? echo "<a href='/".$parts->uri. "'>";?> More information</a></p>
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