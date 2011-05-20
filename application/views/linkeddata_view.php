<!DOCTYPE html>
<?=$metaDisplay;?>
<html>
<head>
	<head>
		<title><?=$title?></title>

		<?=$styles;?>
		<?=$headerDisplay;?>
	</head>
</head>
<body id="home">
	<div id ="contentwrap">	
	<?= $navigationDisplay;?>

	<div id="columnleft">
		<div id="about">
<p>What is linked data and why are we promoting it?</p>
<p>Linked data is a method of publishing structured data, so that it can be interlinked and become more useful, building upon standard Web technologies. The basic principles are:</p>
<p>1. All kind of conceptual things have unique addresses so that they can be referred to and looked up by people and agents (such as other web services).<i>For instance the LCA of aluminum has this unique permanent address: http://footprinted.org/PrimaryAluminumIngot84706627</i></p>
<p>2. Provide useful information about the thing when its URI is looked up, using standard formats such as RDF/XML.<i></i></p>
<p>3. Include links to other, related URIs in the exposed data to improve discovery of other related information on the Web.<i></i></p>



<p>Watch this video from Tim Berners Lee for more information: </p>
	<object width="446" height="326"><param name="movie" value="http://video.ted.com/assets/player/swf/EmbedPlayer.swf"></param><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always"/><param name="wmode" value="transparent"></param><param name="bgColor" value="#ffffff"></param> <param name="flashvars" value="vu=http://video.ted.com/talks/dynamic/TimBerners-Lee_2009-medium.flv&su=http://images.ted.com/images/ted/tedindex/embed-posters/TimBerners-Lee-2009.embed_thumbnail.jpg&vw=432&vh=240&ap=0&ti=484&lang=eng&introDuration=15330&adDuration=4000&postAdDuration=830&adKeys=talk=tim_berners_lee_on_the_next_web;year=2009;theme=what_s_next_in_tech;event=TED2009;tag=Business;tag=Design;tag=Technology;tag=communication;tag=invention;tag=web;&preAdTag=tconf.ted/embed;tile=1;sz=512x288;" /><embed src="http://video.ted.com/assets/player/swf/EmbedPlayer.swf" pluginspace="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent" bgColor="#ffffff" width="446" height="326" allowFullScreen="true" allowScriptAccess="always" flashvars="vu=http://video.ted.com/talks/dynamic/TimBerners-Lee_2009-medium.flv&su=http://images.ted.com/images/ted/tedindex/embed-posters/TimBerners-Lee-2009.embed_thumbnail.jpg&vw=432&vh=240&ap=0&ti=484&lang=eng&introDuration=15330&adDuration=4000&postAdDuration=830&adKeys=talk=tim_berners_lee_on_the_next_web;year=2009;theme=what_s_next_in_tech;event=TED2009;tag=Business;tag=Design;tag=Technology;tag=communication;tag=invention;tag=web;"></embed></object>
	</div>
	</div>
	
	<div id="columnright">
		<?$this->load->view('/standard/aboutmenu_view.php');?>
	</div>
	
</p>
<?=$footerDisplay;?>
</body>
</html>
