
<?php

# General framework config =================================================
$config['base_url']     = "http://sourcemap-dev.local/"; # Base url for sourcemap.
$config['index_page'] = ""; # Add index.php if not using mod_rewrite
$config['log_threshold'] = 4; # Codeigniter logging level (reccomend 4 for dev).
$config['log_path'] = '/var/www/system/logs/'; # Log path (usually in system/logs)
$config['cache_path'] = '/var/www/server/cache/'; # Cache path
$config['compress_output'] = TRUE;
$config['dbtype'] = "postgresql"; # Either mysql or postgresql.

date_default_timezone_set('America/New_York'); # e.g. 'America/Los_Angeles'

error_reporting(E_ALL); # PHP error reporting (recommend E_ALL for dev)

# Sourcemap specific config ================================================
$config['deploystatus'] = "local"; # Sets some system behaviors. (local, stage)
$config['version'] = "0.8"; # Current version string.
$config['statistics'] = false; # If statistics should be cached.

# GeoConfig ================================================
$config['tileprovider'] = "cloudmade"; # googlemaps or cloudmade
$config['geocodeprovider'] = "googlegeocode"; # google

# Minification config =====================================================
$min_cachePath = '/var/www/server/cache/min/'; # Path to minification cache
$min_documentRoot = ''; # The document root for min (often empty)

# Database config =========================================================
$db['default']['hostname'] = "localhost"; # Almost always local host, unless tunneling or commandline (use socket).
$db['default']['username'] = "sourcemap"; # DB Username
$db['default']['password'] = "suppl1ch41n"; # DB Password
$db['default']['database'] = "sourcemapdb"; # Sourcemap database name.
$db['default']['cachedir'] = "/var/www/server/cache/db/"; # Path to db cache


