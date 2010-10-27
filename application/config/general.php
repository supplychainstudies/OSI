<?php

# General framework config =================================================
<<<<<<< .mine
$config['base_url']	= "http://localhost"; # Base url for sourcemap.
=======
$config['base_url']	= "http://localhost/~zapico/opensustainability/opensustainability-open/trunk/"; # Base url for sourcemap.
>>>>>>> .r21
$config['index_page'] = ""; # Will either be this or index.php/
$config['log_threshold'] = 4; # Codeigniter logging level (reccomend 4 for dev).
$config['log_path'] = '/Users/zapico/';
$config['cache_path'] = '/Users/zapico/'; # Cache path
$config['compress_output'] = FALSE;

date_default_timezone_set('America/New_York'); # e.g. 'America/Los_Angeles'
error_reporting(E_ALL); # PHP error reporting (recommend E_ALL for dev)

# Sourcemap specific config ================================================
$config['deploystatus'] = "local"; # Sets some system behaviors. (local, stage)
$config['version'] = "0.8.0"; # Current version string.
$config['statistics'] = false; # If statistics should be cached.

# Minification config =====================================================
$min_cachePath = '/Users/zapico/'; # Path to minification cache
$min_documentRoot = ''; # The document root for min (often empty)

# Database config =========================================================
$config['dbtype'] = "mysql"; # Either mysql or postgresql.
$db['default']['hostname'] = "rhodium.media.mit.edu"; # Almost always local host, unless tunneling.
$db['default']['username'] = "root"; # DB Username
$db['default']['password'] = "suppl1ch41n"; # DB Password
$db['default']['database'] = "opensustainability"; # Open sustainability info database name.
$db['default']['cachedir'] = "/Users/zapico/"; # Path to db cache