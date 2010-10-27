/**
 * Sourcemap.Util.*: Collection of global utility methods.
 *
 * @version 0.7.5
 * @author sourcemap@media.mit.edu
 * @package sourcemap
 * @subpackage js
 */

if ( typeof(Sourcemap) == 'undefined' ) { Sourcemap = {}; }

Sourcemap.Util = {
	/*
	 * Calculate the distance between 2 GLatLng objects.
	 */
	getDistance: function(point1, point2){
	    var distance = point1.distanceFrom(point2); //in meters
	    return Sourcemap.Util.roundIt(Number(distance/1000),2); //in km
	},
	
	replaceURLWithHTMLLinks: function(text) {
	  var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
	  return text.replace(exp,"<a href='$1'>$1</a>"); 
	},
	
	/* I don't think this function is ever used. - z */
	htmlspecialchars: function(str) {
		debug( 'htmlspecialchars' );
		if (typeof(str) == "string") {
			str = str.replace(/&/g, "&amp;"); /* must do &amp; first */
			str = str.replace(/"/g, "&quot;");
			str = str.replace(/'/g, "&#039;");
			str = str.replace(/</g, "&lt;");
			str = str.replace(/>/g, "&gt;");
		}
		return str;
	},

	/*
	 * Used in object.js
	 */
	rhtmlspecialchars: function(str) {
		if (typeof(str) == "string") {
			str = str.replace(/&gt;/ig, ">");
			str = str.replace(/&lt;/ig, "<");
			str = str.replace(/&#039;/g, "'");
			str = str.replace(/&quot;/ig, '"');
			str = str.replace(/&amp;/ig, '&'); /* must do &amp; last */
		}
		return str;
	},
	
	/* I don't think this function is ever used. - z */
	addslashes: function( str ) {
		debug( 'addslashes' );
	  return (str+'').replace(/([\\"'])/g, "\\$1").replace(/\0/g, "\\0");
	},
	
	/* I don't think this function is ever used. - z */
	stripslashes: function( str ) {
		debug( 'stripslashes' );
    return (str+'').replace('/\0/g', '0').replace('/\(.)/g', '$1');
	},

	/* 
	 * Used in objects.js and parts.js.
	 */
	roundIt: function(Num, Places) {
		if (Places > 0) {
			if ((Num.toString().length - Num.toString().lastIndexOf('.')) > (Places + 1)) {
				var Rounder = Math.pow(10, Places);
				return Math.round(Num * Rounder) / Rounder;
			}
			else return Num;
		}
		else return Math.round(Num);
	},
	
	/*
	 * Used in openlayers_map.js 
	 */
	arrayContains: function( array, val ) {
		for ( var i = 0; i < array.length; i++ )
		  if ( array[i] == val )
		    return true;
	 
		return false;     
	},
		// public method for url encoding
		utf8encode : function (string) {
			if(typeof(string) != "string") { return ""; }
			string = string.replace(/\r\n/g,"\n");
			var utftext = "";

			for (var n = 0; n < string.length; n++) {

				var c = string.charCodeAt(n);
				// Fix for ampersands
				if(c == 38) { 
					utftext += "%26";					
				} 
				else if (c < 128) {
					utftext += String.fromCharCode(c);
				}
				else if((c > 127) && (c < 2048)) {					
					utftext += String.fromCharCode((c >> 6) | 192);
					utftext += String.fromCharCode((c & 63) | 128);
				}
				else {					
					utftext += String.fromCharCode((c >> 12) | 224);
					utftext += String.fromCharCode(((c >> 6) & 63) | 128);
					utftext += String.fromCharCode((c & 63) | 128);
				}
			}

			return utftext;
		},

		// public method for url decoding
		utf8decode : function (utftext) {
			var string = "";
			var i = 0;
			var c = c1 = c2 = 0;

			while ( i < utftext.length ) {

				c = utftext.charCodeAt(i);

				if (c < 128) {
					string += String.fromCharCode(c);
					i++;
				}
				else if((c > 191) && (c < 224)) {
					c2 = utftext.charCodeAt(i+1);
					string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
					i += 2;
				}
				else {
					c2 = utftext.charCodeAt(i+1);
					c3 = utftext.charCodeAt(i+2);
					string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
					i += 3;
				}

			}

			return string;
		}

	
};

/*
 * jQuery utility functions.
 *
 */
$(function() {
 jQuery.highlight = document.body.createTextRange ? 

/*
Version for IE using TextRanges.
*/
  function(node, te) {
   var r = document.body.createTextRange();
   r.moveToElementText(node);
   for (var i = 0; r.findText(te); i++) {
    r.pasteHTML('<span class="highlight">' +  r.text + '<\/span>');
    r.collapse(false);
   }
  }

 :

/*
 (Complicated) version for Mozilla and Opera using span tags.
*/
  function(node, te) {
   var pos, skip, spannode, middlebit, endbit, middleclone;
   skip = 0;
   if (node.nodeType == 3) {
    pos = node.data.toUpperCase().indexOf(te);
    if (pos >= 0) {
     spannode = document.createElement('span');
     spannode.className = 'highlight';
     middlebit = node.splitText(pos);
     endbit = middlebit.splitText(te.length);
     middleclone = middlebit.cloneNode(true);
     spannode.appendChild(middleclone);
     middlebit.parentNode.replaceChild(spannode, middlebit);
     skip = 1;
    }
   }
   else if (node.nodeType == 1 && node.childNodes && !/(script|style)/i.test(node.tagName)) {
    for (var i = 0; i < node.childNodes.length; ++i) {
     i += $.highlight(node.childNodes[i], te);
    }
   }
   return skip;
  }

 ;
});

jQuery.fn.removeHighlight = function() {
 this.find("span.highlight").each(function() {
  with (this.parentNode) {
   replaceChild(this.firstChild, this);
   normalize();
  }
 });
 return this;
};



;(function( $ ){
	
	var $scrollTo = $.scrollTo = function( target, duration, settings ){
		$(window).scrollTo( target, duration, settings );
	};

	$scrollTo.defaults = {
		axis:'xy',
		duration: parseFloat($.fn.jquery) >= 1.3 ? 0 : 1
	};

	// Returns the element that needs to be animated to scroll the window.
	// Kept for backwards compatibility (specially for localScroll & serialScroll)
	$scrollTo.window = function( scope ){
		return $(window)._scrollable();
	};

	// Hack, hack, hack :)
	// Returns the real elements to scroll (supports window/iframes, documents and regular nodes)
	$.fn._scrollable = function(){
		return this.map(function(){
			var elem = this,
				isWin = !elem.nodeName || $.inArray( elem.nodeName.toLowerCase(), ['iframe','#document','html','body'] ) != -1;

				if( !isWin )
					return elem;

			var doc = (elem.contentWindow || elem).document || elem.ownerDocument || elem;
			
			return $.browser.safari || doc.compatMode == 'BackCompat' ?
				doc.body : 
				doc.documentElement;
		});
	};

	$.fn.scrollTo = function( target, duration, settings ){
		if( typeof duration == 'object' ){
			settings = duration;
			duration = 0;
		}
		if( typeof settings == 'function' )
			settings = { onAfter:settings };
			
		if( target == 'max' )
			target = 9e9;
			
		settings = $.extend( {}, $scrollTo.defaults, settings );
		// Speed is still recognized for backwards compatibility
		duration = duration || settings.speed || settings.duration;
		// Make sure the settings are given right
		settings.queue = settings.queue && settings.axis.length > 1;
		
		if( settings.queue )
			// Let's keep the overall duration
			duration /= 2;
		settings.offset = both( settings.offset );
		settings.over = both( settings.over );

		return this._scrollable().each(function(){
			var elem = this,
				$elem = $(elem),
				targ = target, toff, attr = {},
				win = $elem.is('html,body');

			switch( typeof targ ){
				// A number will pass the regex
				case 'number':
				case 'string':
					if( /^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(targ) ){
						targ = both( targ );
						// We are done
						break;
					}
					// Relative selector, no break!
					targ = $(targ,this);
				case 'object':
					// DOMElement / jQuery
					if( targ.is || targ.style )
						// Get the real position of the target 
						toff = (targ = $(targ)).offset();
			}
			$.each( settings.axis.split(''), function( i, axis ){
				var Pos	= axis == 'x' ? 'Left' : 'Top',
					pos = Pos.toLowerCase(),
					key = 'scroll' + Pos,
					old = elem[key],
					max = $scrollTo.max(elem, axis);

				if( toff ){// jQuery / DOMElement
					attr[key] = toff[pos] + ( win ? 0 : old - $elem.offset()[pos] );

					// If it's a dom element, reduce the margin
					if( settings.margin ){
						attr[key] -= parseInt(targ.css('margin'+Pos)) || 0;
						attr[key] -= parseInt(targ.css('border'+Pos+'Width')) || 0;
					}
					
					attr[key] += settings.offset[pos] || 0;
					
					if( settings.over[pos] )
						// Scroll to a fraction of its width/height
						attr[key] += targ[axis=='x'?'width':'height']() * settings.over[pos];
				}else{ 
					var val = targ[pos];
					// Handle percentage values
					attr[key] = val.slice && val.slice(-1) == '%' ? 
						parseFloat(val) / 100 * max
						: val;
				}

				// Number or 'number'
				if( /^\d+$/.test(attr[key]) )
					// Check the limits
					attr[key] = attr[key] <= 0 ? 0 : Math.min( attr[key], max );

				// Queueing axes
				if( !i && settings.queue ){
					// Don't waste time animating, if there's no need.
					if( old != attr[key] )
						// Intermediate animation
						animate( settings.onAfterFirst );
					// Don't animate this axis again in the next iteration.
					delete attr[key];
				}
			});

			animate( settings.onAfter );			

			function animate( callback ){
				$elem.animate( attr, duration, settings.easing, callback && function(){
					callback.call(this, target, settings);
				});
			};

		}).end();
	};
	
	// Max scrolling position, works on quirks mode
	// It only fails (not too badly) on IE, quirks mode.
	$scrollTo.max = function( elem, axis ){
		var Dim = axis == 'x' ? 'Width' : 'Height',
			scroll = 'scroll'+Dim;
		
		if( !$(elem).is('html,body') )
			return elem[scroll] - $(elem)[Dim.toLowerCase()]();
		
		var size = 'client' + Dim,
			html = elem.ownerDocument.documentElement,
			body = elem.ownerDocument.body;

		return Math.max( html[scroll], body[scroll] ) 
			 - Math.min( html[size]  , body[size]   );
			
	};

	function both( val ){
		return typeof val == 'object' ? val : { top:val, left:val };
	};

})( jQuery );


