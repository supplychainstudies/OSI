<? $this->load->helper("linkeddata_helper"); ?>
<? $this->load->helper("impact_helper"); ?>
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
		<div class="medium aoi square"><h2><nrwhite>456</nrwhite></h2><h2>Footprints available</h2></div>
				<?
					foreach ($set as $row) {
						// Remove the footprinted part of the url
						$myString = str_replace ("http://footprinted.org/rdfspace/lca/", "", $row['link']);
						echo '<div class="small blue square" id="'.$myString.'"><p>'.$row['name'].'<p/></div>';
					}
				?>
	<div class="medium aoi square"><p>We work for sustainability information to be free, open and easy to use.</p><p> <a href="/about">Read more about Footprinted.</a></p></div>
	
	
	<div class="medium grey square">
	<h2><a href="/create/start">Create new data</a></h2>
	</div>
	<div class="small grey square"><p><a href="http://twitter.com/footprinted">Follow us in twitter</a></p></div>
	</div>
	<?=$footerDisplay;?>
</div>

	<script src="http://footprinted.org/assets/scripts/jquery/jquery-1.5.1.min.js"></script>
	<script src="http://footprinted.org/assets/scripts/jquery/jquery.masonry.min.js"></script>
	<script src="http://footprinted.org/assets/scripts/jquery/jquery-ui-1.7.2.min.js" type="text/javascript"></script>
	<script src="http://footprinted.org/assets/scripts/jquery/jquery-ui-1.8.11.custom.min.js" type="text/javascript"></script>
	
	<script>
	
	$(function() {
		$( ".blue" ).click(function(){
			if (typeof opensquare!="undefined"){
				opensquare.load('/lca/getName/'+opensquare.attr('id'));
				opensquare.removeClass("medium");
				opensquare.removeClass("grey");
                opensquare.addClass("small");
				opensquare.addClass("blue");
			}
			opensquare = $(this);
            $(this).removeClass("small");
			$(this).removeClass("blue");
            $(this).addClass("medium");
			$(this).addClass("grey");
			$('#columnwide').masonry({	  
				  itemSelector:'.square', columnWidth:10, });
			$(this).load('/lca/getImpacts/'+$(this).attr('id'));	
		});
		
	});	

	/*$(function() {
	    //Get Divs
	    //$('#leftcolumn > [square]').each(function(i) {
			// Get CO2
	//		co2 = ('/lca/getCO2/'+opensquare.attr('id'));
			// Scale
	//		side = Math.sqrt(co2*2500);
	//		$(this).width(side);
	//		$(this).height(side);
	//    });
	// Do Masonry
//	$('#columnleft').masonry({	  
		  itemSelector: '.square', columnWidth:10, });
//	});
	
	$( ".aaa" ).click(function(){
			thisone = $(this);
			$.getJSON('/lca/getCO2/'+$(this).attr('id'), function(data) {;
				side = Math.sqrt(data.CO2*2500);
				side = Math.round(side);
				if(side > 400){ side = 400; }
				thisone.width(side);
				thisone.height(side);

	// Do Masonry
	$('#columnleft').masonry({	  
		  itemSelector: '.square', columnWidth:10, });
		});
	});	});*/
	</script>
</body>
</html>