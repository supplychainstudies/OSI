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
	
	<div id="columnleft">

				<?
					foreach ($set as $row) {
						// Remove the footprinted part of the url
						$myString = str_replace ("http://footprinted.org/rdfspace/lca/", "", $row['link']);
						echo '<div class="small blue square" id="'.$myString.'"><p>'.$row['name'].'<p/></div>';
					}
				?>
	</div>


<div id="columnright">
	<p>We work for sustainability information to be free, open and easy to use.</p><p> <a href="/about">Read more about Footprinted.</a></p>
	<h1 class="bignr">456 Footprints available</h1>
	<p></p>
	<h2><a href="/create/start">Create new data</a></h2>
	<h2>Latest news:</h2>
	 <? foreach ($twitter as $tweet) {
		echo '<p>';
		echo $tweet['title'];
		echo '</p>';
	  }
	?>
	<p><a href="http://twitter.com/footprinted">Follow us in twitter</a></p>
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
			$('#columnleft').masonry({	  
				  itemSelector: '.square', columnWidth:10, });
			$(this).load('/lca/getImpacts/'+$(this).attr('id'));
		});
		
	});	

	$(function() {
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
	});	});
	</script>
</body>
</html>