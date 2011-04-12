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

	<div id="lca_background">
		
		<h1><?=$parts['quantitativeReference']['name'] ?></h1>
		<p>Model of the production <? if(isset($parts['modeled']['process'])==true) { echo "(" . $parts['modeled']['process'] . ")" ; } ?> <? if(isset($parts['modeled']['product'])==true) { echo " of " . $parts['modeled']['product'] ; } ?></p>	
		<p>Unit: <?=$parts['quantitativeReference']['amount'] ?> <?=$parts['quantitativeReference']['unit'] ?></p>		
				
			<div id="lca_flows">
			<h2>Flows</h2>
			<? foreach ($parts['exchanges'] as $exchanges) {
				echo $exchanges['direction'] . " - " . $exchanges['name'] . " - " . $exchanges['amount'] . " - " . $exchanges['unit'] . "<br/>\n"; 
				
			}?>
			</div>			
			
			<div id="lca_impact">
			<h2><span>Impact Assessment</h2>	
			<? foreach ($parts['impactAssessments'] as $impactAssessment) {
				echo '<div class="nr"><h1 class="nr">' . $impactAssessment['amount'] . '</h1></div>';
				echo '<div class="meta"><p class="unit">'. $impactAssessment['unit'] .'</p><p class="category">';
				echo  $impactAssessment['impactCategory'] . " - " . $impactAssessment['impactCategoryIndicator'];
				echo "<p/></div>"; 
				
			}?>
			</div>

			<div id="lca_meta">
			<h2>Metadata</h2>
			<?
				foreach ($parts['bibliography'] as $record) {
					echo "<a href=\"" . $record['uri'] . "\">";
					foreach ($record['authors'] as $author) {
						echo $author['lastName'] . ", " .$author['firstName'] . ";";
					}
					echo " ; " . $record["title"];
					echo "</a>";
				}
			?>
			
			<p><?

				if(isset($links) == true) {

					echo $links;

				}

			?></p>
			<p><a href="/">Browse resources</a></p>
			<p><a href="/create/start">Create new resource</a></p>
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