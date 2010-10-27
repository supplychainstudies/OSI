<?php if(!defined('BASEPATH')) { exit('No direct script access allowed'); }
/**
 * Helper for managing google map api keys.
 *
 * @version 0.7.5
 * @author sourcemap@media.mit.edu
 * @package sourcemap
 * @subpackage helpers
 */

function getGoogleJsapi() {
	return "http://www.google.com/jsapi?autoload=%7B%22modules%22%3A%5B%7B%22name%22%3A%22maps%22%2C%22version%22%3A%222%22%7D%2C%7B%22name%22%3A%22earth%22%2C%22version%22%3A%221%22%7D%5D%7D&key=";
}

function getGoogleMapsKey($url) {
	switch ($url) {
	    case "http://localhost/":
	        return "ABQIAAAANbDE2mt4Y9esClUdgjjMRBT2yXp_ZAY8_ufC3CFXhHIE1NvwkxSq7Uo3wI9luZozqxPYnBsOpEgdRA"; 
		break;
	    case "http://localhost/sourcemap/":
		return "ABQIAAAANbDE2mt4Y9esClUdgjjMRBT2yXp_ZAY8_ufC3CFXhHIE1NvwkxSq7Uo3wI9luZozqxPYnBsOpEgdRA"; 
		break;
	    case "http://sourcemap/":
		return "ABQIAAAANbDE2mt4Y9esClUdgjjMRBTVkWr_J3cV1oBDYurpKTLxlEyddRTegjwQrUgL-3IUBOoyqqxJKiz-XQ";
	        break;
	    case "http://hobbes/sourcemap/":
		return "ABQIAAAA0Jmoc2A9szqV3MP7f6NTABSmo_4Z-C54vIfkPvdzAc8lZEHoHRTDilWYvczi_Hoa6oxhtkQRchs2JQ";
	        break;
	    case "http://18.85.45.170/sourcemap/":
		return "ABQIAAAA0Jmoc2A9szqV3MP7f6NTABTBmpX1GEafpwmmI88GGicDJKI26BR9BOVb6p2Fi0abj3bV4NQPlvg4Ig";
		break;
		  case "http://sourcemap.media.mit.edu/":
		return "ABQIAAAA0Jmoc2A9szqV3MP7f6NTABRPcANKEC1MGPTqnfh4Bpa4s8tIkhQWPBQW1OQVa6CvOK1cAya2B9cz4Q";
		break;
		  case "http://dev.sourcemap.org/":
		return "ABQIAAAANbDE2mt4Y9esClUdgjjMRBTGvwB2xfoNxWC7ANLJUZBiYsepqBQC7ugCizjjlvOD2xCnwZX3fexq1A";
		break;
 	    default:
		return "ABQIAAAANbDE2mt4Y9esClUdgjjMRBRBayFfekbyzc5Gccb_xCLTXjmmThSWIuMbqrl63tYmryaFKIverLG2tg";
		break;
	}
}
