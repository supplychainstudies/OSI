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
		<div id="about">
			
		<p>Footprinted is a project of Bianca and Jorge, two researchers with mixed backgrounds in computer and environmental sciences.</p>

		<div id="about-people">
		<p><b>Jorge Zapico</b></p>
		<img src="http://farm5.static.flickr.com/4026/4710985701_1175537b83.jpg" width="300px"/>
		<p><small>That's Jorge hugging a redwood</small></p>
		<p>Jorge has a bachelor in Computer Science and a master in Sustainability Technology and he is doing a PhD at KTH (Stockholm) at the Center for Sustainable Communications.</p>
		<p><a href="http://www.jorgezapico.com" target="_blank">More info</a> | <a href="http://www.jorgezapico.com" target="_blank">Publications</a> | <a  href="http://twitter.com/zapico" target="_blank">Twitter</a> | <a  href="mailto:jorge@footprinted.org" target="_blank">Email</a></p>
		</div>
		
		<div id="about-people">
		<p><b>Bianca Sayan</b></p>
		<img src="http://farm6.static.flickr.com/5304/5693557213_b904ea99e1.jpg" width="300px"/>
		<p><small>That's bianca at Walden Pond</small></p>
		<p>Bianca has a bachelor in Environment and Bussinnes and she is doing her masters in Environment and Resource studies at U. Waterloo.</p>
		<p><a href="mailto:bianca@footprinted.org" target="_blank">Email</a></p>
		</div>
	</div>
	<div id="about">
		<p>A project from:</p>
		<p><a href="http://cesc.se" target="_blank">The Centre for Sustainable Communications</a> from <a  href="http://www.kth.se" target="_blank">KTH</a> in Stockholm, Sweden.</p>
		<p><a href="http://uwaterloo.ca/" target="_blank">The university of Waterloo</a> in Ontario, Canada.</p>
		<p><a href="http://media.mit.edu" target="_blank">MIT Media Lab</a> in Cambridge, MA.</a></p>
		<p><a href="http://sourcemap.com/" target="_blank">Sourcemap.com</a> in Cambridge, MA.</p>
		<p>Thanks to:</p><p> Leonardo Bonnani, Nils Brandt, Marko Turpeinen, Steve Young, Hannes Ebner, the Sourcemap team.</p>
	</div>
	</div>
	<div id="columnright">
		<?$this->load->view('/standard/aboutmenu_view.php')?>;
	</div>
	<?=$footerDisplay;?>
	</div>
	
	<?=$scripts;?>	
</body>
</html>