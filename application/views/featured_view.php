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
				<?
					
					foreach ($set as $row) {
						// Remove the footprinted part of the url
						$myString = str_replace ("http://footprinted.org/rdfspace/lca/", "", $row['uri']);
						switch ($row['categories'][0]['label']) {
						    case 'chemical compound': $color = "blue"; break;
						    case 'building material': $color = "aoi";	break;
						    case "type of food": $color = "brown"; break;
							default: $color = "orange";
						}
						echo '<div class="medium '.$color.' square resource" id="'.$myString.'" co2="'. round($row['co2']).'" water="'. round($row['water']).'"><p>One kg of '.$row['quantitativeReference']['name'].'</p>';
						echo '<div class="number"><h1><nrwhite>' . round($row['co2'],2).'</nrwhite></h1><p>kilogram CO<sub>2</sub></p></div>';
						echo "<div class='plus'><p><a href='/lca/view/".$myString."'>+ More</a></p></div>";
						echo "</div>";
					}
				?>
	<div class="small grey square"><p>456 Footprints available</p></div>
	<div class="small grey square"><p><a href="/create/start">Create new data</a></p></div>
	<div class="small grey square"><p><a href="http://twitter.com/footprinted">Food</a></p></div>
	<div class="small grey square"><p><a href="http://twitter.com/footprinted">Construction materials</a></p></div>
	<div class="small grey square"><p><a href="http://twitter.com/footprinted">Chemical compounds</a></p></div>
	<div class="small grey square"><p><a href="http://twitter.com/footprinted">All</a></p></div>
	<div class="small grey square water"><p><a>See water footprint</a></p></div>
	<div class="small grey square carbon"><p><a>See carbon footprint</a></p></div>
	</div>
	<?=$footerDisplay;?>
</div>

	<script src="http://footprinted.org/assets/scripts/jquery/jquery-1.5.1.min.js"></script>
	<script src="http://footprinted.org/assets/scripts/jquery/jquery.masonry.min.js"></script>
	<script src="http://footprinted.org/assets/scripts/jquery/jquery-ui-1.7.2.min.js" type="text/javascript"></script>
	<script src="http://footprinted.org/assets/scripts/jquery/jquery-ui-1.8.11.custom.min.js" type="text/javascript"></script>
	
	<script>
	$('#columnwide').masonry({	  
		  itemSelector:'.square', columnWidth:10, });
	
	$(function() {
		$( ".aa" ).click(function(){
			if (typeof opensquare!="undefined"){
				opensquare.load('/lca/getName/'+opensquare.attr('id'));
				opensquare.removeClass("medium");
				opensquare.removeClass("grey");
                
				opensquare.addClass("blue");
			}
			opensquare = $(this);
            
			$(this).removeClass("blue");
            $(this).addClass("medium");
			$(this).addClass("grey");
			$('#columnwide').masonry({	  
				  itemSelector:'.square', columnWidth:10, });
			$(this).load('/lca/getImpacts/'+$(this).attr('id'));	
		});
		
	});	
	
	
	$(function() {
		$( ".water" ).click(function(){
			 $('.resource').each(function() {
				water = $(this).attr('water');
				if (water == 0 ){
					$(this).removeClass("medium");
					$(this).addClass("small");
					$(this).find(".number").html("");
				}else{
					$(this).find(".number").html('<h1><nrwhite>' + water + '</nrwhite></h1><p>liters of water</p>');
					$(this).addClass("medium");
					$(this).removeClass("small");
				}
				});
			$('#columnwide').masonry({	  
				itemSelector:'.square', columnWidth:10, });
		});
	});
	$(function() {
		$( ".carbon" ).click(function(){
			 $('.resource').each(function() {
				carbon = $(this).attr('co2');
				if (carbon == 0 ){
					$(this).removeClass("medium");
					$(this).addClass("small");
					$(this).find(".number").html("");
				}else{
					$(this).find(".number").html('<h1><nrwhite>' + carbon + '</nrwhite></h1><p>kilograms of CO2</p>');
					$(this).addClass("medium");
					$(this).removeClass("small");
				}
				});
			$('#columnwide').masonry({	  
				itemSelector:'.square', columnWidth:10, });
		});
	});
	
	</script>
</body>
</html>