<?php
/**
 * Controller for environmental information structures
 * 
 * @version 0.8.0
 * @author info@footprinted.org
 * @package opensustainability
 * @subpackage controllers
 * @uses 
 */

class Feeds extends FT_Controller {
	public function feeds() {
		parent::__construct();
		$this->load->model(Array('lcamodel'));	
		$this->load->library(Array('form_extended','SimpleLoginSecure'));
	}
	
	public function newmaterials() {
		$latest_submissions = $this->lcamodel->simpleSearch(null, 10, 0);
		echo "<?xml version=\"1.0\"?>\n" . 
				"<rss version=\"2.0\">\n" . 
				"<channel>\n";
				$b = 0;
		foreach ($latest_submissions as $latest) {			
				echo "<item>\n" . 
				 	"<title>".$latest['label']."</title>\n" . 
				 	"<link>".$latest['uri']."</link>\n" . 
				 	"<guid>".$latest['uri']."</guid>\n" . 
				 	"<pubDate>".$latest['created']."</pubDate>\n" . 
				 	"<description>New data for ".$latest['label']." can be found at <a href=\"".$latest['uri']."\">".$latest['uri']."</a></description>\n" .
					"</item>\n\n";
					$b++;
					if ($b>10) exit;	
		}
				echo "</channel>";
	}
}