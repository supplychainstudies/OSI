<?php
/**
 * Controller for environmental information structures
 * 
 * @version 0.8.0
 * @author info@opensustainability.info
 * @package opensustainability
 * @subpackage controllers
 * @uses 
 */

class Feeds extends SM_Controller {
	public function News() {
		parent::SM_Controller();			
		$this->load->model(Array('arcmodel', 'arcremotemodel', 'mysqlmodel'));	
		$this->load->library(Array('form_extended', 'name_conversion','SimpleLoginSecure'));
	}
	
	public function newmaterials() {
		@$latest_submissions = $this->arcmodel->latest(10);
		echo "<?xml version=\"1.0\"?>\n" . 
				"<rss version=\"2.0\">\n" . 
				"<channel>\n";
				$b = 0;
		foreach ($latest_submissions as $latest) {			
				echo "<item>\n" . 
				 	"<title>".$latest['name']."</title>\n" . 
				 	"<link>".$latest['uri']."</link>\n" . 
				 	"<guid>".$latest['uri']."</guid>\n" . 
				 	"<pubDate>".$latest['created']."</pubDate>\n" . 
				 	"<description>New data for ".$latest['name']." can be found at <a href=\"".$latest['uri']."\">".$latest['uri']."</a></description>\n" .
					"</item>\n\n";
					$b++;
					if ($b>10) exit;	
		}
				echo "</channel>";
	}
}