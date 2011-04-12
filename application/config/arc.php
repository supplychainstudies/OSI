<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	http://www.your-site.com/
|
*/

/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've renamed it to
| something else. If you are using mod_rewrite to remove the page set this
| variable so that it is blank.
|
*/

$config['arc_info'] = array(
  /* db */
  'db_host' => 'opensustainability.info', /* default: localhost */
  'db_name' => 'opensustainability',
  'db_user' => 'db_osi_admin',
  'db_pwd' => 'rJD6wSKnE83LzYPq',
  /* store */
'store_name' => 'arc_os',
'ns' => array(
'foaf' => 'http://xmls.com/foaf/0.1/',
'dcterms' => 'http://purl.org/dc/terms/',
'dc' => 'http://purl.org/dc/',
'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
'sioc' => 'http://rdfs.org/sioc/ns',
'bibo' => 'http://purl.org/ontology/bibo/',
'eco' => 'http://ontology.earthster.org/eco/core#',
'ecoUD' => 'http://ontology.earthster.org/eco/uncertaintydistribution#',
'fasc' => 'http://ontology.earthster.org/eco/fasc#',
'oselemflow' => 'http://opensustainability.info/vocab/elementaryFlows',
'ossia' => 'http://opensustainability.info/vocab/simpleImpactAssessment',
'qudt' => 'http://data.nasa.gov/qudt/owl/unit#',
'event' => 'http://purl.org/rss/1.0/modules/event/'
),		    
  'endpoint_features' => array(
    'select', 'construct', 'ask', 'describe', // allow read
    'load', 'insert', 'delete',               // allow update
    'dump'                                    // allow backup
  )

);



$config['arc_lr_info'] = array(
  /* db */
  'db_host' => 'localhost', /* default: localhost */
  'db_name' => 'remote',
  'db_user' => 'root',
  'db_pwd' => 'root',
  /* store */
'store_name' => 'remote_os',
'ns' => array(
'foaf' => 'http://xmls.com/foaf/0.1/',
'dcterms' => 'http://purl.org/dc/terms/',
'dc' => 'http://purl.org/dc/',
'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
'sioc' => 'http://rdfs.org/sioc/ns',
'bibo' => 'http://purl.org/ontology/bibo/',
'eco' => 'http://ontology.earthster.org/eco/core#',
'fasc' => 'http://ontology.earthster.org/eco/fasc#',
'oselemflow' => 'http://opensustainability.info/vocab/elementaryFlows',
'ossia' => 'http://opensustainability.info/vocab/simpleImpactAssessment',
'qudt' => 'http://data.nasa.gov/qudt/owl/qudt#',
'qudtu' => 'http://data.nasa.gov/qudt/owl/unit#',
'qudtq' => 'http://data.nasa.gov/qudt/owl/quantity#',
'qudtd' => 'http://data.nasa.gov/qudt/owl/dimension#',
'nist' => 'http://physics.nist.gov/cuu/',
'event' => 'http://purl.org/rss/1.0/modules/event/'
),		    
  'endpoint_features' => array(
    'select', 'construct', 'ask', 'describe', // allow read
    'load', 'insert', 'delete',               // allow update
    'dump'                                    // allow backup
  )
);