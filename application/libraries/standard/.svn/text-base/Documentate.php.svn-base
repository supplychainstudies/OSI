<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* A very simple Documentate link generator
* 
* This class uses the PHP5 Reflection class to find all the public methods
* in a CodeIgniter controller class and generate a list of links.
* 
* @author         Jonathon Hill <jonathon@compwright.com>
* @license        CodeIgniter license
* @requires    MY_Parser extended Parser class [added sparse() for parsing templates stored in a string var]
* @requires    CodeIgniter 1.6 and PHP5
* @version        1.1
* 
*/
class Documentate {

    
    /**
     * CodeIgniter base object reference
     *
     * @var object
     */
    private $CI;
    
    /**
     * Hide the index page by default
     *
     * @var boolean
     */
    private $show_index = false;
    
    
    /**
     * Method names to ignore
     *
     * @var array
     */
    private $ignore = array(
        '*' => array( 
            'get_instance',
            'controller',
            'ci_base'
        )
    );
    
    
    /**
     * Documentate object initialization
     *
     */
    function __construct() {
        $this->CI =& get_instance();
    }
    
    
    /**
     * Set configuration option(s).
     * 
     * Usage:
     *     $this->Documentate->set_option($option, $value);
     *     $this->Documentate->set_option(array(
     *         'option' => 'value',
     *         ...
     *     ));
     *
     * Options:
     *     template           (string) template stored in string
     *     template_file   (string) template stored in file
     *     show_index      (bool) show or hide the index page
     * 
     * @param mixed  $option
     * @param mixed  $value
     * @return boolean
     */
    function set_option($option, $value)
    {
        if(is_array($option))
        {
            foreach($option as $opt => $val)
            {
                if($opt == 'ignore') continue;
                if(isset($this->$opt)) $this->$opt = $val;
            }
            return true;
        }
        elseif(isset($this->$option) && $option != 'ignore')
        {
            $this->$option = $value;
            return true;
        }
        else
        {
            return false;
        }
    }
    
    
    /**
     * Return a configuration option
     *
     * @param string $option
     * @return mixed
     */
    function get_option($option)
    {
        return ($this->$option)? $this->option : false;
    }
    
    
    /**
     * Ignore a controller or specific pages in a controller
     * Usage:
     *     $this->Documentate->ignore('controller', '*');    // completely ignore a controller
     *     $this->Documentate->ignore('controller', array('page1', 'page2'));    // ignore certain pages
     * 
     * 
     * @param string $controller
     * @param mixed $pages
     */
    function ignore($controller, $pages)
    {
        $controller = strtolower($controller);
        if(is_array($pages)) {
            array_walk_recursive($pages, 'Documentate::stl_callback');
        }
        else {
            $pages = strtolower($pages);
        }
        
        if(is_array($this->ignore[$controller])) $pages = array_merge($this->ignore[$controller], (array) $pages);
        $this->ignore[$controller] = $pages;
    }
    
    
    /**
     * Build a list of pages in a controller
     *
     * @param string $page        (optional) Build all the links for a specific controller
     * @return string
     */
    function get_links($class = null)
    {
        // Use the PHP5 Reflection class to introspect the controller
        $controller = new ReflectionClass($class);


        $data['links'] = array();
        $data['section_index'] = strtolower($class);
        $data['section_text'] = ucwords(strtr($class, array('_'=>' ')));
        
        foreach($controller->getMethods(ReflectionMethod::IS_PUBLIC) as $method)
        {
		    
            // skip methods that begin with '_'
            if(substr($method->name, 0, 1) == '_') continue;
            
            // skip globally ignored names
            if(in_array(strtolower($method->name), $this->ignore['*'])) continue;

            // skip ignored controller methods
            if(in_array(strtolower($method->name), (array) $this->ignore[strtolower($class)])) continue;
            
            // skip index page
            if($method->name == 'index' && !$this->show_index) continue;
            
            // skip old-style constructor
            if(strtolower($method->name) == strtolower($class)) continue;
            		
			$docBlock = $method->getDocComment();			
			
            // build link data for parser class
            $data['links'][] = array(
                'link_url' => strtolower(site_url("$class/$method->name")),
                'link_text'=> ucwords(strtr($method->name, array('_'=>' '))),
				'comments'=> $this->commentparse($docBlock)
            );
		
			

        }
        
        return $data;
    }
    
    
    /**
     * Build a complete Documentate from your CI application controllers
     *
     * @return string
     */
    function generate()
    {
        $this->CI->load->helper('file');
        $documentate = array();
        $documentate['controllers'] = array();
      	$documentate['models'] = array();
        $documentate['libraries'] = array();
		$documentate['indexes'] = array();
		
        $controllers_path = APPPATH.'controllers/';
        foreach(get_filenames($controllers_path, true) as $controller) {
            list($class, $ext) = explode('.', ucfirst(basename($controller)));
            if($ext != 'php') continue;     // skip anything other than PHP files
            if($this->ignore[strtolower($class)] == '*') continue;    // skip controllers marked as 'ignore'
            if(!class_exists($class)) include($controller);  // include the class for access
			array_push($documentate['indexes'], $class);
            array_push($documentate['controllers'], $this->get_links($class));
        }

        $controllers_path = APPPATH.'models/';
        foreach(get_filenames($controllers_path, true) as $controller) {
            list($class, $ext) = explode('.', ucfirst(basename($controller)));
            if($ext != 'php') continue;     // skip anything other than PHP files
            if($this->ignore[strtolower($class)] == '*') continue;    // skip controllers marked as 'ignore'
            if(!class_exists($class)) include($controller);  // include the class for access
			array_push($documentate['indexes'], $class);
            array_push($documentate['models'], $this->get_links($class));
        }

        $controllers_path = APPPATH.'libraries/';
        foreach(get_filenames($controllers_path, true) as $controller) {
			if(strpos($controller, "standard") == false) {
            list($class, $ext) = explode('.', ucfirst(basename($controller)));
            if($ext != 'php') continue;     // skip anything other than PHP files
            if($this->ignore[strtolower($class)] == '*') continue;    // skip controllers marked as 'ignore'
            if(!class_exists($class)) include($controller);  // include the class for access
			array_push($documentate['indexes'], $class);
            array_push($documentate['libraries'], $this->get_links($class));
			}
        }
        return $documentate;
    }
    
    function commentparse($comment)
	{
		// Normalize all new lines to \n
		$comment = str_replace(array("\r\n", "\n"), "\n", $comment);

		// Remove the phpdoc open/close tags and split
		$comment = array_slice(explode("\n", $comment), 1, -1);

		// Tag content
		$tags = array();

		foreach ($comment as $i => $line)
		{
			// Remove all leading whitespace
			$line = preg_replace('/^\s*\* ?/m', '', $line);

			// Search this line for a tag
			if (preg_match('/^@(\S+)(?:\s*(.+))?$/', $line, $matches))
			{
				// This is a tag line
				unset($comment[$i]);

				$name = $matches[1];
				$text = isset($matches[2]) ? $matches[2] : '';

				switch ($name)
				{
					case 'license':
						if (strpos($text, '://') !== FALSE)
						{
							// Convert the lincense into a link
							$text = HTML::anchor($text);
						}
					break;
					case 'copyright':
						if (strpos($text, '(c)') !== FALSE)
						{
							// Convert the copyright sign
							$text = str_replace('(c)', '©', $text);
						}
					break;
					case 'throws':
						$text = HTML::anchor(Route::get('docs/api')->uri(array('class' => $text)), $text);
					break;
					case 'uses':
						if (preg_match('/^([a-z_]+)::([a-z_]+)$/i', $text, $matches))
						{
							// Make a class#method API link
							$text = HTML::anchor(Route::get('docs/api')->uri(array('class' => $matches[1])).'#'.$matches[2], $text);
						}
					break;
				}

				// Add the tag
				$tags[$name][] = '<span class="'.$name.' tag"><strong>'.$name.'</strong> - '. $text.'</span>';
			}
			else
			{
				// Overwrite the comment line
				$comment[$i] = (string) $line;
			}
		}

		// Concat the comment lines back to a block of text
		if ($comment = trim(implode("\n", $comment)))
		{
			// Parse the comment with Markdown
		//	$comment = Markdown($comment);
		}

		return array($comment, $tags);
	}
    
/*
     * Callback wrapper function for strtolower
     * Has 2 args to prevent warnings from the strtolower() function
     */
    static function stl_callback($a, $b) { return strtolower($a); }


}

?>