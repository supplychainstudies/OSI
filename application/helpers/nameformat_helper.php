<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/***
* Name Conversion for CodeIgniter
*
*    author: Bianca Sayan
* copyright: (c) 2010
*   license: http://creativecommons.org/licenses/by-sa/2.5/
*      file: libraries/name_conversion.php
*/
	
	/***
    * @private
    * serves as lcfirst, because it doesnt work in some versions of php
    */	
/*
	function lcfirst( $str ) {
        $before=substr($str, 0, 1);
        $after=substr($str, 1, strlen($str)-1);
       
        return strtolower($before).$after;
    }
*/

	/***
    * @public
    * converts a name to bnode. submit something like "Impact Assessment" and it will return "_:impactAssessment23409863267"
    */
	function toBNode($name) {
		$name = explode(":",$name);
		if(count($name)==2)
			$name = $name[1];
		elseif(count($name)==1)
			$name = $name[0];
		return "_:" . toLinkedType($name) . rand(1000000000, 10000000000);		
	}


	/***
    * @public
    * converts a name to to a linked type. Just means, no spaces and first letter lower case. submit something like "Impact Assessment" and it will return "impactAssessment"
    */
	function toLinkedType($name) {
		return lcfirst(str_replace(" ", "", $name));
	}


	/***
    * @public
    * converts a name to to a linked type. Just means, no spaces and first letter lower case. submit something like "Impact Assessment" and it will return "impactAssessment"
    */
	function toLinkedType2($name) {
		$new_name = "";
		foreach(explode("_", $name) as $word) {
			if ($new_name == "") {
				$new_name = $new_name . strtolower($word);
			} else {
				$new_name = $new_name . ucfirst(strtolower($word));
			}
		}
		return $new_name;
	}
	

	/***
    * @public
    * converts a name to a link-friendly string. submit something like "Impact Assessment" and it will return "impactassessment"
    */	
	function toLink($name) {
		return (strtolower(toLinkedType($name)));
	}


	/***
    * @public
    * converts a name to a field name. submit something like "Impact Assessment" and it will return "impact_assessment"
    */	
	function toFieldName($name) {
		return strtolower(str_replace(" ", "_", $name));
	}
	
	// found http://snipplr.com/view/2809/convert-string-to-slug/
	function slug($str)  {
   		$str = strtolower(trim($str));
   		$str = preg_replace('/[^a-z0-9-]/', '-', $str);
   		$str = preg_replace('/-+/', "-", $str);
   		return $str;
   }

	function toURI($type, $name) {
		return "http://footprinted.org/rdfspace/" . $type . "/" . slug($name) . rand(999999,10000000);
	}
	
	function isURI($str) {
		if (strstr($str, "http://") != false) {
			return true;					
		} else {
			return false;
		}
	}

?>