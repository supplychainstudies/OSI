<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/***
* Name Conversion for CodeIgniter
*
*    author: Bianca Sayan
* copyright: (c) 2010
*   license: http://creativecommons.org/licenses/by-sa/2.5/
*      file: libraries/name_conversion.php
*/

class Name_conversion {
	
	
    public function Name_conversion () {
		
	} /*** END ***/
	
	
	/***
    * @private
    * serves as lcfirst, because it doesnt work in some versions of php
    */	
	private function lcfirst( $str ) {
        $before=substr($str, 0, 1);
        $after=substr($str, 1, strlen($str)-1);
       
        return strtolower($before).$after;
    }


	/***
    * @public
    * converts a name to bnode. submit something like "Impact Assessment" and it will return "_:impactAssessment23409863267"
    */
	public function toBNode($name) {
		return "_:" . $this->toLinkedType($name) . rand(1000000000, 10000000000);		
	}


	/***
    * @public
    * converts a name to to a linked type. Just means, no spaces and first letter lower case. submit something like "Impact Assessment" and it will return "impactAssessment"
    */
	public function toLinkedType($name) {
		return $this->lcfirst(str_replace(" ", "", $name));
	}


	/***
    * @public
    * converts a name to to a linked type. Just means, no spaces and first letter lower case. submit something like "Impact Assessment" and it will return "impactAssessment"
    */
	public function toLinkedType2($name) {
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
	public function toLink($name) {
		return (strtolower($this->toLinkedType($name)));
	}


	/***
    * @public
    * converts a name to a field name. submit something like "Impact Assessment" and it will return "impact_assessment"
    */	
	public function toFieldName($name) {
		return strtolower(str_replace(" ", "_", $name));
	}
	
	// found http://snipplr.com/view/2809/convert-string-to-slug/
	public function slug($str)  {
   		$str = strtolower(trim($str));
   		$str = preg_replace('/[^a-z0-9-]/', '-', $str);
   		$str = preg_replace('/-+/', "-", $str);
   		return $str;
   }

	public function toURI($type, $name) {
		return "http://db.opensustaianbility.info/rdfspace/" . $type . "/" . $this->slug($name) . rand(999999,10000000);
	}
}
?>