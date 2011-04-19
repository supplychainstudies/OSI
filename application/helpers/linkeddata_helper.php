<?php if(!defined('BASEPATH')) { exit('No direct script access allowed'); }

	function linkThis($uri, $tooltips, $label = "l") {
		if (isset($tooltips[$uri]) == true) {
			if (isset($tooltips[$uri][$label]) != true) {
				$label = "l";
			}
			return "<a class=\"tooltip\" id=\"" . $uri . "\">" . $tooltips[$uri][$label] . "</a>";
		} elseif (strpos($uri, ":") !== false) {
			$xarray = explode($uri, ":");
			return $xarray[1];
		} else {
			return $uri;
		}
	}

?>