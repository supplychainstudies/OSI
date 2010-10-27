<?php
/**
 * A dynamic sitemap for sourcemap.
 *
 * @version 0.7.5
 * @author sourcemap@media.mit.edu
 * @package sourcemap
 * @subpackage controllers
 */
class Sitemaps extends SM_Controller {
    public function Sitemaps()
    {
        parent::SM_Controller();
        $this->load->library(Array('standard/sitemap', 'standard/documentate')); 
        $this->load->model(Array('ObjectsModel', 'PartsModel')); 
    }

    public function xmlmap()
    {
		$map = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$map .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
	  
		$map .= "<url>\n";
		$map .= "    <loc>http://www.sourcemap.org/</loc>\n";
		$map .= "    <lastmod>". date('Y-m-d')."</lastmod>\n";
		$map .= "    <changefreq>daily</changefreq>\n";
		$map .= "    <priority>1.0</priority>\n";
		$map .= "</url>\n";

		$map .= "<url>\n";
		$map .= "    <loc>http://www.sourcemap.org/objects</loc>\n";
		$map .= "    <lastmod>". date('Y-m-d')."</lastmod>\n";
		$map .= "    <changefreq>daily</changefreq>\n";
		$map .= "    <priority>0.9</priority>\n";
		$map .= "</url>\n";

		$map .= "<url>\n";
		$map .= "    <loc>http://www.sourcemap.org/parts</loc>\n";
		$map .= "    <lastmod>". date('Y-m-d')."</lastmod>\n";
		$map .= "    <changefreq>daily</changefreq>\n";
		$map .= "    <priority>0.9</priority>\n";
		$map .= "</url>\n";

		$map .= "<url>\n";
		$map .= "    <loc>http://www.sourcemap.org/collections</loc>\n";
		$map .= "    <lastmod>". date('Y-m-d')."</lastmod>\n";
		$map .= "    <changefreq>daily</changefreq>\n";
		$map .= "    <priority>0.8</priority>\n";
		$map .= "</url>\n";

		$map .= "<url>\n";
		$map .= "    <loc>http://www.sourcemap.org/users</loc>\n";
		$map .= "    <lastmod>". date('Y-m-d')."</lastmod>\n";
		$map .= "    <changefreq>daily</changefreq>\n";
		$map .= "    <priority>0.7</priority>\n";
		$map .= "</url>\n";

		$map .= "<url>\n";
		$map .= "    <loc>http://www.sourcemap.org/groups</loc>\n";
		$map .= "    <lastmod>". date('Y-m-d')."</lastmod>\n";
		$map .= "    <changefreq>daily</changefreq>\n";
		$map .= "    <priority>0.7</priority>\n";
		$map .= "</url>\n";
		
		$map .= "<url>\n";
		$map .= "    <loc>http://www.sourcemap.org/api</loc>\n";
		$map .= "    <lastmod>". date('Y-m-d')."</lastmod>\n";
		$map .= "    <changefreq>daily</changefreq>\n";
		$map .= "    <priority>0.9</priority>\n";
		$map .= "</url>\n";
		
		$map .= "<url>\n";
		$map .= "    <loc>http://www.sourcemap.org/auth/register</loc>\n";
		$map .= "    <lastmod>". date('Y-m-d')."</lastmod>\n";
		$map .= "    <changefreq>monthly</changefreq>\n";
		$map .= "    <priority>0.8</priority>\n";
		$map .= "</url>\n";
	
		$objects = 	$this->ObjectsModel->getObjects();			
		foreach($objects as $object) {
			$map .= "<url>\n";
			$map .= "    <loc>". site_url()."objects/".$object->type."-".$object->slug."</loc>\n";
			$map .= "    <lastmod>".date('Y-m-d',strtotime($object->timemodified))."</lastmod>\n";
		    $map .= "    <changefreq>weekly</changefreq>\n";
		    $map .= "    <priority>0.5</priority>\n";
			$map .= "</url>\n";		
		}
		$parts = 	$this->PartsModel->getParts();			
		foreach($parts as $part) {
			$map .= "<url>\n";
			$map .= "    <loc>".site_url() . "parts/" . $part->slug ."</loc>\n";
			$map .= "    <lastmod>".date('Y-m-d',strtotime($part->modified))."</lastmod>\n";
		    $map .= "    <changefreq>monthly</changefreq>\n";
		    $map .= "    <priority>0.6</priority>\n";
			$map .= "</url>\n";		
		}
		$map .= "</urlset>";
		$this->output->set_output($map);
    }

	/*public function reflected() {
		// Show the index page of each controller (default is FALSE)
        $this->sitemap->set_option('show_index', true);
        $this->sitemap->ignore('*', array('sm_controller','data','display','init','set'));
        echo @$this->sitemap->generate();
	}*/
	public function documentation() {
		// Show the index page of each controller (default is FALSE)
        $this->documentate->set_option('show_index', true);

        // Exclude a list of methods from any controller
        $this->documentate->ignore('*', array('sm_controller','data','display','init','set', 'addstyles', 'addscripts', 'addexternalscripts', 'addscriptvars', 'addsidebars', 'addview', 'setpagination', 'setcache', 'clearcache'));
		
        // Exclude this controller

        // Show the sitemap
        $doctext = '';
        $docs = @$this->documentate->generate();

		// Controllers
		foreach($docs['controllers'] as $doc) {
			$doctext .= '<h2><a name="'.$doc['section_index'].'">Controller '.$doc['section_text'].'</a></h2><ul>';			
			foreach($doc['links'] as $link) {
				if(isset($link['link_text'])) { $doctext .= '<li>Method <a href="'.$link['link_url'].'">'.$link['link_text'].'</a></li>'; } $doctext .= '<ul>';	
				foreach($link['comments'] as $comment) {
					if(is_array($comment)) {
						if(isset($comment['param'])) { foreach($comment['param'] as $param) { if($param != "") { $doctext .= '<li>'.$param.'</li>';}}
						}
					}
					else if($comment != "") { $doctext .= '<li>'.$comment.'</li>';}
				} $doctext .= '</ul>';								
			} $doctext .= '</ul>';
		}
	
		// Models
		foreach($docs['models'] as $doc) {
			$doctext .= '<h2><a name="'.$doc['section_index'].'">Model '.$doc['section_text'].'</a></h2><ul>';			
			foreach($doc['links'] as $link) {
				if(isset($link['link_text'])) { $doctext .= '<li>Method '.$link['link_text'].'</li>'; } $doctext .= '<ul>';	
				foreach($link['comments'] as $comment) {
					if(is_array($comment)) {
						if(isset($comment['param'])) { foreach($comment['param'] as $param) { if($param != "") { $doctext .= '<li>'.$param.'</li>';}}
						}
					}
					else if($comment != "") { $doctext .= '<li>'.$comment.'</li>';}
				} $doctext .= '</ul>';								
			} $doctext .= '</ul>';
		}
		

		
		$this->data('indexes', $docs['indexes']);		
		$this->data('docs', $doctext);
		$this->style(Array('docs.css'));
		$this->display("Developer Documentation", "standard/docs_view");
	}
}