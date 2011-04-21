<? $this->load->helper("linkeddata_helper"); ?>
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
	
	<div id="columnleft">
		<div class="big grey square"><p>
			<h2>Footprint of one kilogram of Aluminum</h2>
			<? foreach ($feature_info['impactAssessments'] as $impactAssessment) {
				
				
				switch ($impactAssessment['impactCategoryIndicator']) {
				    case 'ossia:waste':
				        $color = "#C5E9FF";
						$max = 10;
						break;
				    case 'ossia:CO2e':
				        $color = "#5AC0FF";
						$max = 10;
						break;
				    case "ossia:energy":
				        $color = "#227CAF";
						$max = 500;
						break;
					case "ossia:water":
					    $color = "#45A3D8";
						$max = 10;
						break;
					default:
						$color = "#45A3D8";
						$max = 50;
				}
				$size = 2* round(50*$impactAssessment['amount']/$max);
				if ($size > 80) { $size = 80; }
				$margin = (100-$size)/2;
				$margintop = (100-$size)/6;

				echo '<div class="impact"><div class="circle"><div style="width:'.$size.'px; height:'.$size.'px;margin-left:'.$margin.'px;margin-top:'.$margintop.'px; background:'.$color.'; -moz-border-radius: 40px; -webkit-border-radius:40px;"></div></div>';
				echo '<div class="nr"><h1 class="nr">' . round($impactAssessment['amount'],2) . '</h1></div>';
				echo '<div class="meta"><p class="unit">'. $impactAssessment['unit'] .'</p><p class="category">';
				echo  $impactAssessment['impactCategoryIndicator'];
				echo "<p/></div></div>"; 
				
			}?>
		</p></div>
		<div class="medium aoi square">
			<h1 class="bignr">456</h1>
			<p>Footprints available</p>
		</div>
		<div id="all_resources">
				<?
					foreach ($set as $row) {
						// Remove the opensustainability part of the url
						$myString = str_replace ("http://db.opensustainability.info/rdfspace/lca/", "", $row['link']);
						echo '<div class="small blue square"><p><a href="lca/view/'.$myString.'">'.$row['name'].'</a><p/></div>';
					}
				?>
		</div>
	</div>


<div id="columnright">
	<div class="wide aoi square"><p>We work for sustainability information to be free, open and easy to use.</p></div>
	<div class="wide aoi square"><h2><a href="/about">About Footprinted.org</a></h2></div>
	<div class="wide aoi square"><h2><a href="/create/start">Create new data</a></h2></div>
	<div class="wide aoi square"><h2>Latest news:</h2></div>
	 <? foreach ($twitter as $tweet) {
		echo '<div class="wide aoi square"><p>';
		echo $tweet['title'];
		echo '</p></div>';
	  }
	?>
	<div class="wide aoi square"><p><a href="http://twitter.com/footprinted">Follow us in twitter</a></p></div>
</div>
	<?=$footerDisplay;?>
</div>

<?=$scripts;?>	
</body>
</html>