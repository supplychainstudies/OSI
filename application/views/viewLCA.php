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

	<div id="lca_background">
		
		<div id="lca_title"><h1><?=$parts['quantitativeReference']['name'] ?></h1></div>
		<? /* <p>Model of the production <? if(isset($parts['modeled']['process'])==true) { echo "(" . $parts['modeled']['process'] . ")" ; } ?> <? if(isset($parts['modeled']['product'])==true) { echo " of " . $parts['modeled']['product'] ; } ?></p> */?>	
		<div id="lca_unit"><?=$parts['quantitativeReference']['amount'] ?> <?=$parts['quantitativeReference']['unit'] ?></p></div>	
				
			<div id="lca_flows">
			<h2>Flows</h2>
			<? if ($totalinput != 0) { ?>
			<h3>Total input: <h1 class="nr"><?=round($totalinput,2); ?> kg</h1></h3>
			<? if($parts['quantitativeReference']['unit'] == "qudt:Kilogram") { 
					$ratio = ($totalinput/$parts['quantitativeReference']['amount']);
					echo "<h3>Ration input vs production<h1 class='nr'>".round($ratio).":1</h1></h3>";
				} ?>
			<h3>Material inputs breakdown:</h3>
			<? foreach ($parts['exchanges'] as $exchanges) {
				if ($exchanges['direction'] == 'Input') {
					$width = round( (100*$exchanges['amount'])/round($totalinput));
					if ($exchanges['unit'] == 'Kilogram'){
						echo "<div class='bar_background'>";
						echo '<div style="width:'.$width.'%; height:20px; background-color:#8CC63F; border-right:1px #FFF solid;"></div></div>';
					}
					echo "<p><amount>" . $exchanges['amount'] . "</amount> " . $exchanges['unit']; 
					echo "<b> ".$exchanges['name'] . "</b></p>";
				}
			}?>	
			<?}?>
			<? if ($totaloutput != 0) { ?>
			<h3>Total output: <h1 class="nr"><?=round($totaloutput,2); ?> kg</h1></h3>
			<h3>Material output breakdown:</h3>
			<? foreach ($parts['exchanges'] as $exchanges) {
				if ($exchanges['direction'] == 'Output') {
					$width = round( (100*$exchanges['amount'])/round($totaloutput) );
					if ($exchanges['unit'] == 'Kilogram'){
						echo "<div class='bar_background'>";	
						echo '<div style="width:'.round($width).'%; height:20px; background-color:#535B39; border-right:1px #FFF solid;"></div></div>';
					}
					echo "<p><amount>" . $exchanges['amount'] . "</amount> " . $exchanges['unit']; 
					echo "<b> ".$exchanges['name'] . "</b></p>";
				}
			}?>
			<?}?>
			</div>			
			
			<div id="lca_impact">
			<h2><span>Impact Assessment</h2>	
			<? foreach ($parts['impactAssessments'] as $impactAssessment) {
				echo '<div class="nr"><h1 class="nr">' . round($impactAssessment['amount'],2) . '</h1></div>';
				echo '<div class="meta"><p class="unit">'. $impactAssessment['unit'] .'</p><p class="category">';
				echo  $impactAssessment['impactCategory'] . " - " . $impactAssessment['impactCategoryIndicator'];
				echo "<p/></div>"; 
				
			}?>
			</div>

			<div id="lca_meta">
			<h2>Reference</h2>
			<?
				foreach ($parts['bibliography'] as $record) {
					echo "<a href=\"" . $record['uri'] . "\" target='_blank'>";
					foreach ($record['authors'] as $author) {
						echo $author['lastName'] . ", " .$author['firstName'] . ";";
					}
					echo " ; " . $record["title"];
					echo "</a>";
				}
			?>
			<br/><br/>
			<h2>Export</h2>
			<p><?

				if(isset($links) == true) {

					echo $links;

				}

			?></p>
			<h2>Share</h2>
			<?php
			# Get the actual URL
				$thispage = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];
			?>
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
				<li id="linkedin_share"><p>
					<?php
					echo '<a href="http://www.linkedin.com/shareArticle?mini=true&url=';
					echo $thispage;
					echo "&title=";
					echo $parts['quantitativeReference']['name'];
					echo '" target="_blank">Post this to Linkedin</a>';
					?></p></li>
				<li id="delicious_share"><p><a href="http://delicious.com/save" onclick="window.open('http://delicious.com/save?v=5&noui&jump=close&url='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title), 'delicious','toolbar=no,width=550,height=550'); return false;">Post this to Delicious</a></p></li>
			</ul>
			
			</div>
			
			<div id="lca_comments">
			<?

				if(isset($comment) == true) {

					include_once('parts/comments.php');

				}

			?>
			</div>
			
			

	</div>
			<?=$footerDisplay;?>
		</div>

		<?=$scripts;?>	
		</body>
	</html>