<html>
<head>
	<style>
		body {
			font-family:Helvetica, Arial, sans-serif;
			background:#ccc;
		}
		.good {
			border:1px solid green;
			background:#fff;
			padding:10px;
			margin:10px;
			color:green;
			font-size:12px;
		}
		.error {
			border:1px solid red;
			background:red;
			color:#fff;
			margin:10px;
			padding:10px;
			font-size:12px;						
		}
		.notice {
			border:1px solid #ccc;
			background:#fff;
			color:#ccc;
			margin:10px;
			padding:10px;
			font-size:12px;						
		}
		hr {
			border-bottom:none;
		}
		h1 {
			font-size:18px;
		}
		h2, h3 {
			font-size:14px;
			padding:0;
			margin:0 0 6px 0;
		}
		h3, strong {
			font-size:12px;
		}
		ul {
			padding:0 0 0 12px;
			margin:0 0 12px 12px;
		}
		li {
			font-size:11px;
		}
	</style>	

</head>

<body>

<h1>Sourcemap Install Checker</h1>
<?php
	define('PATH', dirname(__FILE__));
	require_once PATH . "/application/config/general.php";
?>
<h2>Checking PHP Version</h2>
<?php	phpversioncheck(); ?>

<h2>Checking PHP INI</h2>
<?php	checkini(); ?>
<h2>Checking PHP Extensions</h2>
<?php	checkextensions(); ?>
<h2>Checking Database Support</h2>
<?php checkdatabasesupport(); ?>
<h2>Checking Apache Modules</h2>
<?php	modrewritecheck(); ?>

<h2>Checking Sourcemap Configuration</h2>
<?php	readwritecheck($config, $min_cachePath, $db); ?>

<?php

	function checkdatabasesupport() {
			$extensions = get_loaded_extensions();
		    if(in_array("mysql", $extensions)) {
				print "<div class='good'>";		
				print "Mysql is enabled.";
				print "</div>";
			}
			else {
				print "<div class='error'>";		
				print "Mysql is not currently enabled.";
				print "</div>";
			}
			if(in_array("pgsql", $extensions)) {
				print "<div class='good'>";		
				print "Postgres is enabled.";
				print "</div>";
			}
			else {
				print "<div class='error'>";		
				print "Postgres is not currently enabled.";
				print "</div>";
			}
	}
	
	function checkextensions() {
			$extensions = get_loaded_extensions();
		    if(in_array("pcre", $extensions)) {
				print "<div class='good'>";		
				print "Pcre is enabled.";
				print "</div>";
			}
			else {
				print "<div class='error'>";		
				print "Pcre is not currently enabled.";
				print "</div>";
			}

		    if(in_array("xml", $extensions)) {
				print "<div class='good'>";		
				print "Xml is enabled.";
				print "</div>";
			}
			else {
				print "<div class='error'>";		
				print "Xml is not currently enabled.";
				print "</div>";
			}

		    if(in_array("zlib", $extensions)) {
				print "<div class='good'>";		
				print "Zglib is enabled.";
				print "</div>";
			}
			else {
				print "<div class='error'>";		
				print "Zglib is not currently enabled.";
				print "</div>";
			}

		    if(in_array("mbstring", $extensions)) {
				print "<div class='good'>";		
				print "Mbstring is enabled.";
				print "</div>";
			}
			else {
				print "<div class='error'>";		
				print "Mbstring is not currently enabled.";
				print "</div>";
			}

		    if(in_array("curl", $extensions)) {
				print "<div class='good'>";		
				print "Curl is enabled.";
				print "</div>";
			}
			else {
				print "<div class='notice'>";		
				print "Curl is not currently enabled.";
				print "</div>";
			}
	}
	function checkini() {
		if(ini_get('short_open_tag') == "1") {
			print "<div class='good'>";		
			print "Short tags are on.";
			print "</div>";
		}
		else {
			print "<div class='error'>";		
			print "Short tags are off in the php.ini";
			print "</div>";
		}
		if(ini_get('max_execution_time') >= 60) {
			print "<div class='good'>";		
			print "Execution time is great than 60 seconds.";
			print "</div>";
		}
		else {
			print "<div class='notice'>";		
			print "Execution time is less than 60 seconds.";
			print "</div>";
		}
		if(ini_get('max_input_time') >= 120) {
			print "<div class='good'>";		
			print "Max input time is greater than 120 seconds.";
			print "</div>";
		}
		else {
			print "<div class='notice'>";		
			print "Max input time is less than 120 seconds.";
			print "</div>";
		}
		if(ini_get('memory_limit') >= 128) {
			print "<div class='good'>";		
			print "Memory limit is greater than 128mb.";
			print "</div>";
		}
		else {
			print "<div class='error'>";		
			print "Memory limit is less than 128mb.";
			print "</div>";
		}		
		
		
	}
	
	function modrewritecheck() {
		$modules = apache_get_modules();
	    if(in_array("mod_rewrite", $modules)) {
			print "<div class='good'>";		
			print "ModRewrite is enabled.";
			print "</div>";
		}
		else {
			print "<div class='notice'>";		
			print "ModRewrite is not currently enabled.";
			print "</div>";
		}
	}

	function phpversioncheck() {
		if(!(phpversion() >= 5.2)) {
			print "<div class='error'>";		
			print "PHP Version not up to date (" . phpversion() . "). You should update to the latest version of PHP.";
			print "</div>";
		} else {
			print "<div class='good'>";
			print "PHP version up to date (" . phpversion() . ")";
			print "</div>";
		}
	}

	function readwritecheck($config, $min, $db) {
		if(!isset($config['cache_path']) || $config['cache_path'] == "") {
			print "<div class='error'>";		
			print "You have not yet entered a value for the cache_path in application/config/general.php.";
			print "</div>";
		} else{
			print "<div class='good'>";		
			print "Your cache_path is set.";
			print "</div>";
		}
		if(!isset($config['log_path']) || $config['log_path'] == "") {
			print "<div class='error'>";		
			print "You have not yet entered a value for the log_path in application/config/general.php";
			print "</div>";
		} else {
			print "<div class='good'>";		
			print "Your log_path is set.";
			print "</div>";
		}

		if(is_writable($config['cache_path'])) {
			print "<div class='good'>";
			print "Your cache_path is writable.";
			print "</div>";		
		} else {
			print "<div class='error'>";
			print "Your cache_path is not writable.";
			print "</div>";
		}		
	}
?>

</body>
</html>