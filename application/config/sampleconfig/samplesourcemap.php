<?php

# General framework config =================================================
$config['base_url']	= "http://sourcemap/"; # Base url for sourcemap.
$config['index_page'] = ""; # Add index.php if not using mod_rewrite
$config['log_threshold'] = 4; # Codeigniter logging level (reccomend 4 for dev).
$config['log_path'] = '/path/to/logs/'; # Log path (usually in system/logs)
$config['cache_path'] = '/path/to/cache/'; # Cache path
$config['compress_output'] = TRUE;
$config['dbtype'] = "postgresql"; # Either mysql or postgresql.

date_default_timezone_set('timezonestringhere'); # e.g. 'America/Los_Angeles'

error_reporting(E_ALL); # PHP error reporting (recommend E_ALL for dev)

# Sourcemap specific config ================================================
$config['deploystatus'] = "local"; # Sets some system behaviors. (local, stage)
$config['version'] = "0.7.5"; # Current version string.
$config['statistics'] = true; # If statistics should be cached.

# GeoConfig ================================================
$config['tileprovider'] = "googlemaps"; # googlemaps or cloudmade
$config['geocodeprovider'] = "googlegeocode"; # google

# Minification config =====================================================
$min_cachePath = '/path/to/cache/min/'; # Path to minification cache
$min_documentRoot = ''; # The document root for min (often empty)

# Database config =========================================================
$db['default']['hostname'] = "localhost"; # Almost always local host, unless tunneling or commandline (use socket).
$db['default']['username'] = ""; # DB Username
$db['default']['password'] = ""; # DB Password
$db['default']['database'] = ""; # Sourcemap database name.
$db['default']['cachedir'] = "/path/to/cache/db/"; # Path to db cache