<?php
/**
 * Helper for loading localizations.
 * 
 * @package sourcemap
 * @subpackage helpers
 */
if(!defined('BASEPATH')) exit('No direct script access allowed');
	

	function getTemplateTerm($type, $term) {
			
		$TermMap['smobject']['object'] = "Object";
		$TermMap['smtrace']['object'] = "Object";		
		$TermMap['smfood']['object'] = "Recipe";
		$TermMap['smtravel']['object'] = "Trip";
		
		$TermMap['smobject']['part'] = "Part";
		$TermMap['smtrace']['part'] = "Part";		
		$TermMap['smfood']['part'] = "Ingredient";
		$TermMap['smtravel']['part'] = "Passenger";

		$TermMap['smobject']['partlistlabel'] = "Made Of";
		$TermMap['smtrace']['partlistlabel'] = "Made Of";
		$TermMap['smfood']['partlistlabel'] = "Menu";
		$TermMap['smtravel']['partlistlabel'] = "Passenger List";

		$TermMap['smobject']['madein'] = "Made in";
		$TermMap['smtrace']['madein'] = "Made in";
		$TermMap['smfood']['madein'] = "Made in";
		$TermMap['smtravel']['madein'] = "Traveling to";
		
		$TermMap['smobject']['weightconvert'] = 1;
		$TermMap['smtrace']['weightconvert'] = 1;		
		$TermMap['smfood']['weightconvert'] = 1;
		$TermMap['smtravel']['weightconvert'] = 97;
		
		return $TermMap['sm'.$type][$term];		
	}
	
	function ifTemplateUses($type, $term) {
		
		$TermMap['smobject']['partlink'] = true;
		$TermMap['smtrace']['partlink'] = false;		
		$TermMap['smfood']['partlink'] = true;
		$TermMap['smtravel']['partlink'] = false;
		
		return $TermMap['sm'.$type][$term];
	}
	

?>