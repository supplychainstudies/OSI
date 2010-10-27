<?php

class SM_Exceptions extends CI_Exceptions {
   
   function show_404($page = '')
   {
      $code = '404';
      $text = 'Page not found';
      
      $server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;
   
      if (substr(php_sapi_name(), 0, 3) == 'cgi')
      {
         header("Status: {$code} {$text}", TRUE);
      }
      elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0')
      {
         header($server_protocol." {$code} {$text}", TRUE, $code);
      }
      else
      {
         header("HTTP/1.1 {$code} {$text}", TRUE, $code);
      }
      $ch = curl_init();

	// add this line to get the base URL
	$this->config =& get_config();
	$base_url = $this->config['base_url'] . "index.php/";
	// change this line in the curl code
	curl_setopt($ch, CURLOPT_URL, $base_url . "home/page_not_found/");      
	curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, 'originalURL=' . urlencode($_SERVER['REQUEST_URI']));
      curl_exec($ch); 
      curl_close($ch);
   }
}
?>