<!DOCTYPE html>
<?=$metaDisplay;?>
<html>
<head>
	<title>Footprinted.org</title>
	
	<?=$styles;?>
	<?=$headerDisplay;?>



</head>
<body id="home">
	<div id ="contentwrap">	
	<?= $navigationDisplay;?>
	
	<div id="columnwide">
				<div id="about">
				<p>We want sustainability information to be open, free and easy to use.</p>
				<p><a>Search</a> in the 500+ footprints available or explore some of the featured ones:</p>
				</div>
					
				<?	foreach ($set as $parts) { ?>
						
						<div id="lca_title_lite">
								<h1>
								<?=$parts['quantitativeReference']['amount'] ?> 
								<?= $parts['quantitativeReference']['unit']["label"] ?> of
								<?	if (isset($parts['sameAs']) == true) {
										foreach ($parts['sameAs'] as $record) {
											if (isset($record['dbpedia']) == true) {
												echo '<a title="'.$record['description'].'">';
											}
										}
									}
								?>
								<?=$parts['quantitativeReference']['name'] ?></a></h1>
								<?
									if (isset($parts['bibliography']) == true) {
									echo "<p>From: ";
										foreach ($parts['bibliography'] as $record) {
											if (isset($record['uri']) == true) {
												echo "<a href=\"" . $record['uri'] . "\" target='_blank'>";
											}
											if (isset($record['authors']) == true) {
												foreach ($record['authors'] as $author) {
													echo $author['lastName'] . ", " .$author['firstName'] . "; ";
												}
											}
											if (isset($record["title"]) == true) {
												echo $record["title"];
											}
											if (isset($record['uri']) == true) {
												echo "</a></p>";
											}
										}
									}
								?>


							</div>

							<div id="lca_lite">
								<div id="lca_impact_lite">	
									<div id="tabs">
									<?
									if (isset($parts['impactAssessments']) == true) {
									 foreach ($parts['impactAssessments'] as $impactAssessment) {
										// Change color of the circle depending on the impact category	
										switch ($impactAssessment['impactCategoryIndicator']['label']) {
										    case 'Waste': $color = "#6B5344"; $max = 2000; $impacttext = "Waste";break;
										    case 'Carbon Dioxide Equivalent': $color = "#FF7C00";	$max = 2000; $impacttext = "CO<sub>2</sub> (eq)";break;
											case 'Carbon Dioxide': $color = "#FF7C00";	$max = 2000; $impacttext = "CO<sub>2</sub>";break;
										    case "Energy": $color = "#E8BF56"; $max = 20; $impacttext = "Energy";	break;
											case "Water":$color = "#45A3D8"; $max = 2000; $impacttext = "Water"; break;
											default: $color = "#45A3D8"; $max = 2000; $impacttext = $impactAssessment['impactCategoryIndicator']['label'];
										}
										// Change the size depending on the relative max
										$size = round(sqrt($max*$impactAssessment['amount']/pi()));
										if ($size > 82) { $size = 82;}
										if ($size < 20) { $size = 20;}
										$margin = (120-$size)/2;
										$unit = $impactAssessment['unit']['l'];
										if ($unit == "Kilogram") { $unit = "Kg";}
										if ($unit == "Megajoules") { $unit = "MJ";}
										// Create a circle
										echo '<div class="tab_impact"><div class="tab_circle"><div style="width:'.$size.'px; height:'.$size.'px;margin-left:'.$margin.'px;margin-top:'.$margin.'px; background:'.$color.'; -moz-border-radius: 40px; -webkit-border-radius:40px;"></div></div>';
											echo '<div class="tab_nr"><p><nrwhite>' . round($impactAssessment['amount'],2) . "</nrwhite> ". $unit . "<p/></div>";
										echo  "<div class='tab_meta'><p>".$impacttext."</p></div>";
										echo "</div>"; 

									} }?>
									</div>
								</div>	

								<? if (isset($parts['geography']) == true ) {
									echo '<div id="maplite">';		
									foreach ($parts['geography'] as $geo) {
											$map = "http://maps.google.com/maps/api/staticmap?sensor=false&size=200x160&center=".$geo['lat'].','.$geo['long']."&zoom=2&style=feature:road.local%7Celement:geometry%7Chue:0x00ff00%7Csaturation:100&style=feature:landscape%7Celement:geometry%7Clightness:-100&style=feature:poi.park%7Celement:geometry%7Clightness:-100";
												echo '<img src="'.$map.'" alt="'.$geo['name'].'"/>';
												echo '<div id="infomap"><p>From: <b>'.$geo['name'].'</b></p></div>';
									}
									echo "</div>";
								} ?>	
								<div class="ref_lite">
									<p>Data from: 2011<?/* $parts['bibliography']['year'] */?></p>
								</div>
								<div class="ref_lite">
									<p>Category: 
										<? if (isset($parts['categoryOf']) == true) {
											echo "<a href='" . $parts['categoryOf'][0]['uri'] . "' target='_blank'>";
											echo $parts['categoryOf'][0]['label'];
											echo "</a>";
									}?></p>
								</div>
								<div class="ref_lite">
									<p>Same as: <?/*	if (isset($parts['sameAs']) == true) {
													echo '<a href="'.$record['description'].'">';
										}
									*/?></p>
								</div>
								<div class="ref_lite">
									<p><? echo "<a href='/lca/view/".$parts['uri']. "'>";?> >More information +</a></p>
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