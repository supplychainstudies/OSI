<? $this->load->helper("linkeddata_helper"); ?>
<!DOCTYPE html>
<?=$metaDisplay;?>
<html>
<head>
	<title><?=$parts['quantitativeReference']['name'] ?> Footprinted</title>
	<?=$scripts;?>	
	<?=$styles;?>
	<?=$headerDisplay;?>
</head>

<body id="home">
	
	<div id ="contentwrap">	
		
	<?= $navigationDisplay;?>

	<div id="lca_header">
		<div id="lca_title"><h1><?=$parts['title'] ?></h1></div>
		
		<? /*<p>Model of the production <? if(isset($parts['modeled']['process'])==true) { echo "(" . $parts['modeled']['process'] . ")" ; } ?> <? if(isset($parts['modeled']['product'])==true) { echo " of " . $parts['modeled']['product'] ; } ?></p> */?>	
		<div id="lca_unit"><h1><nr><?=$parts['quantitativeReference']['amount'] ?> <?= $parts['quantitativeReference']['unit']["abbr"] ?></nr></h1></div>
	</div>
	<div id="lca_background">	
		<div id="lca_impact" class="lca">
			<h2><span>Impact Assessment</h2>	
			<?
			if (isset($parts['impactAssessments']) == true) {
			 foreach ($parts['impactAssessments'] as $impactAssessment) {
				// Change color of the circle depending on the impact category	
				switch ($impactAssessment['impactCategoryIndicator']['label']) {
				    case 'Waste': $color = "#6B5344"; $max = 2000; $impacttext = "Waste";break;
				    case 'Carbon Dioxide Equivalent': $color = "#FF7C00";	$max = 2000; $impacttext = "CO<sub>2</sub>e";break;
					case 'Carbon Dioxide': $color = "#FF7C00";	$max = 2000; $impacttext = "CO<sub>2</sub>";break;
				    case "Energy": $color = "#E8BF56"; $max = 20; $impacttext = "Energy";	break;
					case "Water":$color = "#45A3D8"; $max = 2000; $impacttext = "Water"; break;
					default: $color = "#45A3D8"; $max = 2000; $impacttext = $impactAssessment['impactCategoryIndicator']['label'];
				}
				// Change the size depending on the relative max
				$size = round(sqrt($max*$impactAssessment['amount']/pi()));
				if ($size > 82) { $size = 82;}
				if ($size < 20) { $size = 20;}
				$margin = (100-$size)/2;
				$margintop = (100-$size)/3;
				// Create a circle
				echo '<div class="circle"><div style="width:'.$size.'px; height:'.$size.'px;margin-left:'.$margin.'px;margin-top:'.$margintop.'px; background:'.$color.'; -moz-border-radius: 40px; -webkit-border-radius:40px;"></div></div>';
				echo '<div class="nr"><h1 class="nr">' . round($impactAssessment['amount'],2) .' '. $impactAssessment['unit']["abbr"] .'</h1></div>';
				echo '<div class="meta"><p class="category">Category: <b>'. $impactAssessment['impactCategory']['label'] . "</b><br/>";
				echo 'Indicator: <b>'. $impactAssessment['impactCategoryIndicator']['label'] . "</b></p></div>"; 				
			} } ?>
			</div>
			
			<? if(isset($parts['exchanges']  ) == true) { ?>
			<div id="lca_flows" class="lca">
			<h2>Flows</h2>
			<? if (isset($parts['Input']) == true) { ?>
				<? if ($totalinput != 0) { ?>	
					<h3>Total material input:</h3> <h1 class="nr"><?=round($totalinput,2); ?> kg </h1>
					<? if($parts['quantitativeReference']['unit']['label'] == "Kilogram") { 
						$ratio = ($totalinput/$parts['quantitativeReference']['amount']);
						echo "<h3>Ratio input vs production</h3><h1 class='nr'>".round($ratio).":1</h1>";
						} ?>
				<? } ?>
			<? if ($totalinputliter != 0) { ?>
					<h3>Total water input:</h3> <h1 class="nr"><?=round($totalinputliter,2); ?> liters </h1>
			<? } ?>
			<h3>Material inputs breakdown:</h3>
			<? 
			if (isset($parts['Input']["Mass"]) == true) { 
			$color_input = array('8CC63F','85C61E','70A024','7FAA3A','8EB256','88D600','98EF00','8DEF00','8ECE20','72CC23','55CC23'); $i=0;
			foreach ($parts['Input']["Mass"] as $mass) {
					$width = round(100*$mass['amount']/$totalinput);
					if ($width == 0) { $width = 1; }
					echo '<div class="bar_background"><div style="height:20px;width:'.$width.'%;background-color:#'.$color_input[$i].';"></div></div>';
					echo "<div class='flow_text'><p><amount>" . $mass['amount'] . "</amount> " . $mass['unit']["abbr"]; 
					echo "<b> ".$mass['name'] . "</b></p></div>";
					$i++; if ($i >10){ $i = 0;}
			}}?>
			<? 
			if (isset($parts['Input']["Liquid Volume"]) == true) { 
			$color_liquid = array('1a6eff','1B64CE','1753AA','133E7C','2C4C7C');$i = 0;	
			foreach ($parts['Input']["Liquid Volume"] as $volume) {
					$width = round(100*$volume['amount']/$totalinputliter);
					if ($width == 0) { $width = 1; }
					echo '<div class="bar_background"><div style="height:20px;width:'.$width.'%;background-color:#'.$color_liquid[$i].';"></div></div>';
					echo "<div class='flow_text'><p><amount>" . $volume['amount'] . "</amount> " . $volume['unit']["label"]; 
					echo "<b> ".$volume['name'] . "</b></p></div>";
					$i++; if ($i >4){ $i = 0;}
			}}?>
			<? 
			if (isset($parts['Input']["Area"]) == true) { 
				foreach ($parts['Input']["Area"] as $land) {
					$width = round(100*$land['amount']/$totalinputland);
					if ($width == 0) { $width = 1; }
					echo '<div class="bar_background"><div style="height:20px;width:'.$width.'%;background-color:#381100;"></div></div>';
					echo "<div class='flow_text'><p><amount>" . $land['amount'] . "</amount> " . $land['unit']["label"]; 
					echo "<b> ".$land['name'] . "</b></p></div>";
			}}?>
			<? 
			if (isset($parts['Input']['Misc']) == true) {
				foreach ($parts['Input']['Misc'] as $misc) {
					$width = round(100*$misc['amount']/$misctotal);
					if ($width == 0) { $width = 1; }
					echo '<div class="bar_background"><div style="height:20px;width:'.$width.'%;background-color:#ffcc00;"></div></div>';
					echo "<div class='flow_text'><p><amount>" . $misc['amount'] . "</amount> " . $misc['unit']["label"]; 
					echo "<b> ".$misc['name'] . "</b></p></div>";
				}
			} 
			?>
			<?}?>
			
			<? if ($totaloutput != 0) { ?>	
			<h3>Total output: <h1 class="nr"><?=round($totaloutput,2); ?> kg</h1></h3>
			<? if($parts['quantitativeReference']['unit']['label'] == "Kilogram") { 
					$ratio = ($totaloutput/$parts['quantitativeReference']['amount']);
					echo "<h3>Ratio output vs production</h3><h1 class='nr'>".round($ratio).":1</h1>";
				} ?>
			<? } ?>
			<h3>Material output breakdown:</h3>
			
			<?
			$color_mass = array('535B39','576033','576325','5D6325','4D512A','464925','3D3F26','4A4C32','4A513A','444F2D','3E4C20','525933','3A4229');
			$i = 0;
			if(isset($parts['Output']["Mass"]) == true) {
			foreach ($parts['Output']["Mass"] as $mass) {
					$width = round(100*$mass['amount']/$totaloutput);
					if ($width == 0) { $width = 1; }
					echo '<div class="bar_background"><div style="height:20px; width:'.$width.'%;background-color:#'.$color_mass[$i].';"></div></div>';
					echo "<div class='flow_text'><p><amount>" . round($mass['amount'],6) . "</amount> " . $mass['unit']["abbr"]; 
					echo "<b> ".$mass['name'] . "</b></p></div>";
					$i++;
					if ($i >12){ $i = 0;}
			}}?>

			<? 
			if (isset($parts['Output']["Liquid Volume"]) == true) {
				foreach ($parts['Output']["Liquid Volume"] as $volume) {
					$width = round(100*$volume['amount']/1);
					if ($width == 0) { $width = 1; }
					echo '<div class="bar_background"><div style="height:20px;width:'.$width.'%;background-color:#002caa;"></div></div>';
					echo "<div class='flow_text'><p><amount>" . $volume['amount'] . "</amount> " . $volume['unit']["label"]; 
					echo "<b> ".$volume['name'] . "</b></p></div>";
				}
			} 
			?>
			<? 
			if (isset($parts['Output']["Energy and Work"]) == true) {
				foreach ($parts['Output']["Energy and Work"] as $energy) {
					$width = round(100*$energy['amount']/1);
					if ($width == 0) { $width = 1; }
					echo '<div class="bar_background"><div style="height:20px;width:'.$width.'%;background-color:#ffcc00;"></div></div>';
					echo "<div class='flow_text'><p><amount>" . $energy['amount'] . "</amount> " . $energy['unit']["label"]; 
					echo "<b> ".$energy['name'] . "</b></p></div>";
				}
			} 
			?>
			</div>
			<?}?>				
			<? 
				// Shows the geography (map) and date
				echo '<div id="map" class="lca"><h2>Applies to</h2>';
				if($parts['year'] != 0){
					echo '<p>Year: <b>'.$parts['year'].'</b></p>';
				}else{
					echo '<p>Year: <b>not available</b></p>';
				}
				if (isset($parts['geography']) == true ) {
					foreach ($parts['geography'] as $geo) {
						echo '<p>Geography: <b>'.$geo['name'].'</b></p>';
						$map = "http://maps.google.com/maps/api/staticmap?sensor=false&size=400x370&zoom=2&markers=size:big%7Ccolor:blue%7C".$geo['name']."&style=feature:road.local%7Celement:geometry%7Chue:0x00ff00%7Csaturation:100&style=feature:landscape%7Celement:geometry%7Clightness:-100&style=feature:poi.park%7Celement:geometry%7Clightness:-100";
						echo '<div id="gmap"><img src="'.$map.'" alt="'.$geo['name'].'"/></div>';
					}
				} else {
						echo '<p>Geography: <b>generic</b></p>';
				}
				echo "</div>";
			 ?>

			<div id="lca_meta"  class="lca">
			<h2>Reference</h2>
			<?
				if (isset($parts['bibliography']) == true) {
					echo "<p>";
					foreach ($parts['bibliography'] as $record) {
						if (isset($record['uri']) == true) {
							echo "<a href='/search?ref=" . $record['title'] . "' target='_blank'>";
						}
						$ref = "";
						if (isset($record['authors']) == true) {
							foreach ($record['authors'] as $author) {
								$ref .= $author['lastName'] . ", " .$author['firstName'] . ". ";
							}
						}
						if (isset($record["date"]) == true) 	{ 	$ref .= "(".substr_replace($record['date'],'', 4).") "; }
						if (isset($record["title"]) == true) 	{	$ref .= $record["title"];	}
						if (isset($record['uri']) == true) 		{	echo $ref."</a>"; }
					}
					echo "</p>";
				}
			?>
			</div>
			<? if (isset($parts['sameAs']) == true || isset($parts['categoryOf']) == true) { ?>
			<div id="lca_same" class="lca">
			<h2>More info</h2>
			<p>Reference flow: <?=$parts['quantitativeReference']['name'] ?></p>
			<?
				if (isset($parts['sameAs']) == true) {
					foreach ($parts['sameAs'] as $record) {
					
						if (isset($record['dbpedia']) == true) {
							//echo '<p><img src="'.$record['img'].'" width="200 px" /></p>';
							echo '<p>'.$record['description'].'</p>';
							echo "<p><a href='". $record['dbpedia']. "' target='_blank'>More info at Dbpedia:". $record['dbpedia'].'</p></a>';
						}
						echo "<p><a href='" . $record['uri'] . "' target='_blank'>";
						echo "Same as: ". $record['title'];
						echo "</a></p>";
					}
				}
				if (isset($parts['categoryOf']) == true) {
					foreach ($parts['categoryOf'] as $record) {
						echo "<p><a href='/search/?category=" .  $record['label'] . "' target='_blank'>";
						echo "Belongs to Category: ". $record['label'];
						echo "</a></p>";
					}
				}
			?>
			</div>
			<? } ?>
			<div id="lca_export" class="lca">
			<h2>Export</h2>
			<p><?
					echo "<p><a href='/".$URI.".rdf'>Export in RDF</a></p>";
					echo "<p><a href='/".$URI.".json'>Export in JSON</a></p>";		
				?>
				</p>
				<p><a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/">
					<img alt="Creative Commons License" style="border-width:0" src="http://mirrors.creativecommons.org/presskit/icons/cc.svg" height='50px' />
					<img alt="Creative Commons License" style="border-width:0" src="http://mirrors.creativecommons.org/presskit/icons/by.svg" height='50px' /> <img alt="Creative Commons License" style="border-width:0" src="http://mirrors.creativecommons.org/presskit/icons/sa.svg" height='50px'/></a><br />This <span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/Dataset" rel="dct:type">work</span> is licensed under:<br/><a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>.</p>
					
					<?php
					# Get the actual URL
						$thispage = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];
					?>
					<p>Attribute to:<br/>
					<?=$ref?> <i>via</i> <? echo "<a href='".$thispage."'>"; ?> <?=$thispage?></a></p>
			
			</div>
			<div id="lca_share" class="lca">
			<h2>Share</h2>

			<ul id="share-options">			
				<li id="facebook_share"><p>
					<?php 
					echo "<a href='http://facebook.com/sharer.php?u=";
					echo $thispage;
					echo "&t=Check+out+the+footprint+of+";
					echo $parts['quantitativeReference']['name'];
					echo "'";
					echo 'target="_blank">Post this to Facebook</a>';
					?></p></li>
				<li id="twitter_share"><p>
					<?php
					echo '<a href="http://twitter.com/home?status=Check+out+the+footprint+of+';
					echo $parts['quantitativeReference']['name'];
					echo "+at+";
					echo $thispage;
					echo '" target="_blank">Post this to Twitter</a>';
					?></p></li>
				<li id="delicious_share"><p><a href="http://delicious.com/save" onclick="window.open('http://delicious.com/save?v=5&noui&jump=close&url='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title), 'delicious','toolbar=no,width=550,height=550'); return false;">Post this to Delicious</a></p></li>
			</ul>
			
			</div>
			
			<div id="lca_comments" class="lca">
			<h2>Comments</h2>
			<?

				if(isset($comment) == true) {

					include_once('parts/comments.php');

				}

			?>
			</div>
			
			

	</div>
			<?=$footerDisplay;?>
		</div>

		<script>

		$(function(){
			$('#lca_background').masonry({
				singleMode: true,
		        itemSelector: '.lca'
			});	
		});
		</script>
		</body>
	</html>