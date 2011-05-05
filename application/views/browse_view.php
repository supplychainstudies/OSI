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
						// Remove the opensustainability part of the url
						$myString = str_replace ("http://footprinted.org/rdfspace/lca/", "", $row['link']);
						echo '<div class="small blue square" id="'.$myString.'"><p>'.$row['name'].'<p/></div>';
					}
				?>
	</div>


<div id="columnright">
	<div class="wide aoi square"><p>We work for sustainability information to be free, open and easy to use.</p></div>
	<div class="medium grey square">
		<h1 class="bignr">456</h1>
		<p>Footprints available</p>
	</div>
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


	</script>
</body>
</html>