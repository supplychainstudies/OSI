<!DOCTYPE html>
<?=$metaDisplay;?>
<html>
<head>
	<title><?=$title?></title>
	
	<?=$styles;?>
	<?=$headerDisplay;?>
</head>

<body id="home">
	<div id ="contentwrap">	
		
	<?= $navigationDisplay;?>

	<div id="columnleft">
		<div id="content">
			
			<div id="quantitative_reference">
				<div id="qr_amount"><?=$parts['quantitativeReference']['amount'] ?></div>
				<div id="qr_unit"><?=$parts['quantitativeReference']['unit'] ?></div>
				<div id="qr_name"><?=$parts['quantitativeReference']['name'] ?></div>
			</div>			
			<div>Description</div><div>
				"This is a model of the production <? if(isset($parts['modeled']['process'])==true) { echo "(" . $parts['modeled']['process'] . ")" ; } ?> <? if(isset($parts['modeled']['product'])==true) { echo " of " . $parts['modeled']['product'] ; } ?>
			</div>

			<div>Flows</div>
			<div id="flows">
			<? foreach ($parts['exchanges'] as $exchanges) {
				echo $exchanges['direction'] . " - " . $exchanges['name'] . " - " . $exchanges['amount'] . " - " . $exchanges['unit'] . "<br/>\n"; 
				
			}?></div>			
			
			<div>Impact Assessment</div>
			<div id="impactAssessment">
			<? foreach ($parts['impactAssessments'] as $impactAssessment) {
				echo $impactAssessment['impactCategory'] . " - " . $impactAssessment['impactCategoryIndicator'] . " - " . $impactAssessment['amount'] . " - " . $impactAssessment['unit'] . "<br/>\n"; 
				
			}?></div>
			
			<div>Bibliography</div>
			<?
				foreach ($parts['bibliography'] as $record) {
					foreach ($record['authors'] as $author) {
						echo $author['lastName'] . ", " .$author['firstName'] . ";";
					}
					echo " ; " . $record["title"];
				}
			?>


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
			<h1 class="hand">Discussion</h1>
			<?

				if(isset($comment) == true) {

					include_once('parts/comments.php');

				}

			?>
	</div>
			<?=$footerDisplay;?>
		</div>

		<?=$scripts;?>	
		</body>
	</html>