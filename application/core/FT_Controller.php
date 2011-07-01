<?php  

class FT_Controller extends CI_Controller {

	private $_title = "";
	private $_baseview = "";
	private $fullview = true;
	
	private $sidebar = Array();
	
	public $views = Array();
	public $data = Array();
	
	private $mobile;
	private $_styles = Array();
	private $_scripts = Array();
	private $_externalscripts = Array();
	private $_scriptvars = Array();
	
	private $_defaultdescription = 'Footprinted';
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	public function init() {
		$this->config->load('general');
	}
	
	public function data($name, $value) {
		$this->data[$name] = $value;		
	}	
	
	public function style($styles) {
		foreach($styles as $style) {	
			array_push($this->_styles, $style);
		}
	}

	public function script($scripts, $external=false) {
		if($external) { $this->_addexternalscripts($scripts);} 
		else { $this->_addscripts($scripts); }
	}
	
	public function scriptvar($scriptvars) {
		foreach($scriptvars as $scriptvar) {	
			array_push($this->_scriptvars, $scriptvar);
		}
	}
	
	public function contextual($modules) {
		foreach($modules as $module) {
			array_push($this->sidebar, $module);
		}
	}
	
	public function view($name, $reference, $data=null) {	
		$this->views[$name] = $reference;
		if($data != null) { $this->data[$name] = $data;	}
		else { $this->data[$name] = Array();}
	}
	
	public function set($type, $value) {
		$this->$type = $value;
	}
	
	public function paginate($per, $base, $total, $segment) {
		$config['per_page'] = $per; 		
		$config['base_url'] = $base;
		$config['total_rows'] = $total;
		$config['uri_segment'] = $segment;
		$this->pagination->initialize($config); 
		$this->data('pagination', $this->pagination->create_links());		
	}
	
	public function display($title, $baseview, $included=true, $return=false) { 
		$this->_baseview = $baseview; $this->_title = $title;
		if($return) { return $this->load->view($this->_baseview, $this->data, true);	}
		$this->data['title'] = $this->_title;
		if(!isset($this->data['pagedescription'])) { $this->data['pagedescription'] = $this->_defaultdescription; }

		if(!(isset($this->data['current']))) {
			$this->data['current'] = $this->uri->segment(1);
		}
		
		$this->data['mobile'] = $this->mobile;
		$this->data['styles'] = $this->generateStyles();
		$this->data['scripts'] = $this->generateScripts($included);
			
		if($this->fullview) {
			$this->data['metaDisplay'] = $this->load->view("standard/metaheader_view", $this->data, true);
			$this->data['headerDisplay'] = $this->load->view("standard/header_view", $this->data, true);
			$this->data['navigationDisplay'] = $this->load->view("standard/navigation_view", $this->data, true);
			$this->data['footerDisplay'] = $this->load->view("standard/footer_view", $this->data, true);
		
			$sidebar['modules'] = "";
			foreach($this->sidebar as $module) {
				$sidebar['modules'] .= $this->load->view('sidebars/'.$module.'.php', null, true);
			}
			$this->data['sidebarDisplay'] = $this->load->view('standard/sidebar_view', $sidebar, true);
		}
		foreach($this->views as $name => $reference) {
			$this->data[$name] = $this->load->view($reference, array_merge($this->data, $this->data[$name]), true);
		}		
		
		$this->load->view($this->_baseview, $this->data);	
	}	
	
	private function _addscripts($scripts) {
		foreach($scripts as $script) {	
			array_push($this->_scripts, $script);
		}
	}
	private function _addexternalscripts($scripts) {
		foreach($scripts as $script) {	
			array_push($this->_externalscripts, $script);
		}
	}
	
	private function generateStyles() {
		$styleDisplay = null;
		$minurl = base_url() . "server/min/?f=";
		if($this->mobile) {
			$csspath = 'sourcemap/assets/styles/';
			$styleDisplay = '<link rel="stylesheet" href="'.$minurl.$csspath.'style.css';				
		}
		else {
			$standard = Array('style.css','standard.css','form.css','jquery-ui-1.8.11.custom.css');
			$this->_styles = array_merge($standard, $this->_styles);
			 if($this->config->item("deploystatus") == "local") {
				$csspath = base_url() . 'assets/styles/';
				
				$styleDisplay = '<link rel="stylesheet" href="'.$csspath.'reset.css" type="text/css"/>';								
				foreach($this->_styles as $style) {
					$styleDisplay .= '<link rel="stylesheet" href="'.$csspath.$style.'" type="text/css"/>';				
				}
			} else {				
				$csspath = 'assets/styles/';
				$styleDisplay = '<link rel="stylesheet" href="'.$minurl.$csspath.'reset.css';				
				foreach($this->_styles as $style) {
					$styleDisplay .= ','.$csspath . $style . '';
				}		
				$styleDisplay .= '" type="text/css"/>';	
			}
		}
		return $styleDisplay;	
	} 

	private function generateScripts($included) {
		$scriptDisplay = '';
		if($included) {		
			foreach($this->_externalscripts as $script) {
				$scriptDisplay .= '<script type="text/javascript" src="'.$script.'"></script>';
			}
		}
		$minurl = base_url() . "/";
		
		if($included) {		
			$scriptDisplay .='<script type="text/javascript" 
		        src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>';		
			$standard = Array('jquery/jquery-ui-1.8.11.custom.min.js','janrain.js');
			
			
			
			// , 'utilities.js', 'jquery/jquery.template.js'
			$this->_scripts = array_merge($standard, $this->_scripts);
		}
		if($this->config->item("deploystatus") == "local") {			
			$jspath = base_url() . 'assets/scripts/';
			if($included) {
				$scriptDisplay .= '<script type="text/javascript" src="'.$jspath.'main.js"></script>';
			}
			foreach($this->_scripts as $script) {
				$scriptDisplay .= '<script type="text/javascript" src="'.$jspath.$script.'"></script>';
			}		
			if($included) {			
				$scriptDisplay .= '<script type="text/javascript" src="'.$jspath.'messagebar.js"></script>';	
			}		
		}
		else {
			$jspath = 'assets/scripts/';
				
			if($included) {	$scriptDisplay .= '<script type="text/javascript" src="'.$minurl.$jspath.'main.js';}
			else { $scriptDisplay .= '<script type="text/javascript" src="'.$minurl.$jspath.array_shift($this->_scripts); }			
			
			foreach($this->_scripts as $script) {
				$scriptDisplay .= ','.$jspath . $script . '';
			}		
			if($included) { $scriptDisplay .= ",".$jspath.'messagebar.js"></script>';} 
			else { $scriptDisplay .= '"></script>'; }		
			
			
		}
		if($included) {
			$scriptDisplay .= '<script type="text/javascript">'.$this->generateScriptVariables().'</script>';
		}
		return $scriptDisplay;
		
	}
	
	private function generateScriptVariables() {
		$scriptVars = 'Sourcemap.baseurl = "'.base_url().'";';
		$scriptVars .= 'Sourcemap.siteurl = "'.site_url().'";';
		foreach($this->_scriptvars as $scriptvar) {
			$scriptVars .= $scriptvar;			
		}
		return $scriptVars;
	}
	
	public function setCache($time) {
		$this->config->load('general');
		if($this->config->item('deploystatus') == "stage") {
			$this->output->cache($time);						
		}	
	}
	
	public function clearCache($uri_segment) {
		$CI =& get_instance();	
		$path = $CI->config->item('cache_path');
	
		$cache_path = ($path == '') ? BASEPATH.'cache/' : $path;
		
		$uri =	"CACHE".$uri_segment;		
		$cache_path .= str_replace("/","+",$uri);
		
		@unlink($cache_path);		
	}
	
	// For protecting pages
	function check_if_logged_in() {
     	if($this->session->userdata('id') == false) {
       		//redirect('/users/login');
		}
   	}
	// Protecting the site only for admins
	function check_if_admin() {
     	if($this->session->userdata('id') == false) {
       		redirect('/users/login');
   		}else{
			if($this->session->userdata('id') != ("bianca.sayan" || "zapico" || "leo"))  {redirect('/users/login'); }
		}
	}
}
