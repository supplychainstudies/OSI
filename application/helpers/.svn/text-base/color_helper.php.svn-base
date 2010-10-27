<?php if(!defined('BASEPATH')) { exit('No direct script access allowed'); }

 function findcolor($curval, $mn, $mx, $style, $lightness) {
		if($curval > $mn) { return "#ff0000"; }
		// value between 1 and 0
		$position = ($curval - $mn) / ($mx - $mn); 
		
		// this adds 0.5 at the top to get red, and limits the bottom at x= 1.7 to get purple
		$shft = ($style == 'roygbiv') ? 0.5*$position + 1.7*(1-$position) : $position + 0.2 + 5.5*(1-$position);
		
		// scale will be multiplied by the cos(x) + 1 
		// (value from 0 to 2) so it comes up to a max of 255
		$scale = 128;
		
		// period is 2Pi
		$period = 2*pi();
		
		// x is place along x axis of cosine wave
		$x = $shft + $position * $period;
		
		// shift to negative if greentored
		$x = ($style != 'roygbiv') ? -$x : $x;
			
		$r = processColor( floor((cos($x) + 1) * $scale), $lightness );
		$g = processColor( floor((cos($x+pi()/2) + 1) * $scale), $lightness );
		$b = processColor( floor((cos($x+pi()) + 1) * $scale), $lightness );
		
		return '#' . $r . $g . $b;
	
	}

	function processColor( $num, $lightness) {
		
		// adjust lightness
		$n = floor( $num + $lightness * (256 - $num));
		
		// turn to hex
		$s = dechex($n);
		
		// if no first char, prepend 0
		$s = (strlen($s) == 1) ? '0' . $s : $s;
		
		return $s;		
	}
	
?>