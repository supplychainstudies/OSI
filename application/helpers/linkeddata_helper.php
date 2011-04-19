<?php if(!defined('BASEPATH')) { exit('No direct script access allowed'); }

	function linkThis($uri, $tooltips) {
		if (isset($tooltips[$uri]) == true) {
			return "<a class=\"tooltip\" id=\"" . $uri . "\">" . $tooltips[$uri]['l'] . "</a>";
		} elseif (strpos($uri, ":") !== false) {
			$xarray = explode($uri, ":");
			return $xarray[1];
		} else {
			return $uri;
		}
	}

?>