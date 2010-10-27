/**
 * Sourcemap JS API. All methods are contained inside of the Sourcemap.*
 * namespace.
 *
 * @version 0.7.5
 * @author sourcemap@media.mit.edu
 * @package sourcemap
 * @subpackage js
 * @namespace
 */
Sourcemap = {
  /**
   * Print a debug message to the console.
   * @param {String} str The message to display on the console.
   */
  init: function() {
	//$("#search_button").click( function() { Sourcemap.search(); });
	//$("#search_field").bind('keypress', function(e) {var code = (e.keyCode ? e.keyCode : e.which); if(code == 13) { Sourcemap.search();}});
  	this.pageTracker = _gat._getTracker("UA-101941-5");
	this.pageTracker._setDomainName(".sourcemap.org"); 
  },
  debug: function(str) { 
	  if(typeof(console) != 'undefined') { 
		  console.log(str); 
	  } 
  }
}

$(document).ready(function() {
	Sourcemap.init();
	Sourcemap.debug("Welcome to Sourcemap");
	Sourcemap.pageTracker._trackPageview();	
	
	// Uservoice
	Sourcemap.uservoiceOptions = {
	    key: 'sourcemap',
	    host: 'sourcemap.uservoice.com', 
	    forum: '40263',
	    lang: 'en',
	    showTab: false
  	};
	$("#user_name").focus(function() { if($(this).val() == "Username") { $(this).val("");}});
	$("#password").focus(function() { $(this).val("");});
	
  	function _loadUserVoice() {
 	    var s = document.createElement('script');
	    s.src = ("https:" == document.location.protocol ? "https://" : "http://") + "cdn.uservoice.com/javascripts/widgets/tab.js";
	    document.getElementsByTagName('head')[0].appendChild(s);
  	}
  	_loadSuper = window.onload;
  	window.onload = (typeof window.onload != 'function') ? _loadUserVoice : function() { _loadSuper(); _loadUserVoice(); };
});
