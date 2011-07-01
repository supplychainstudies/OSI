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
$config['arc_info'] = array();
$config['arc_info']['store_name'] = 'footprinted';
$config['arc_info']['ns'] = array(
'foaf' => 'http://xmlns.com/foaf/0.1/',
'dcterms' => 'http://purl.org/dc/terms/',
'dc' => 'http://purl.org/dc/',
'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
'rdf' => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
'sioc' => 'http://rdfs.org/sioc/ns',
'bibo' => 'http://purl.org/ontology/bibo/',
'eco' => 'http://ontology.earthster.org/eco/core#',
'ecoUD' => 'http://ontology.earthster.org/eco/uncertaintydistribution#',
'fasc' => 'http://ontology.earthster.org/eco/fasc#',
'oselemflow' => 'http://footprinted.org/vocab/elementaryFlows',
'ossia' => 'http://footprinted.org/vocab/simpleImpactAssessment',
'qudt' => 'http://data.nasa.gov/qudt/owl/qudt#',
'qudtu' => 'http://data.nasa.gov/qudt/owl/unit#',
'qudtq' => 'http://data.nasa.gov/qudt/owl/quantity#',
'qudtd' => 'http://data.nasa.gov/qudt/owl/dimension#',
'nist' => 'http://physics.nist.gov/cuu/',
'event' => 'http://purl.org/rss/1.0/modules/event/',
'ecoalloc' => 'http://ontology.earthster.org/eco/alloc#',
'ecoattr' => 'http://ontology.earthster.org/eco/attribute#',
'ecob' => 'http://ontology.earthster.org/eco/bridges#',
'cml2001' => 'http://ontology.earthster.org/eco/cml2001#',
'eco' => 'http://ontology.earthster.org/eco/core#',
'ecodl' =>'http://ontology.earthster.org/eco/ecodl#',
'ecofull' =>'http://ontology.earthster.org/eco/ecofull#',
'ecoinvent' =>'http://ontology.earthster.org/eco/ecoinvent#',
'ecosp' =>'http://ontology.earthster.org/eco/ecospold#',
'fasc' =>'http://ontology.earthster.org/eco/fasc#',
'ecofa' =>'http://ontology.earthster.org/eco/fullAxioms#',
'ecoilcd' =>'http://ontology.earthster.org/eco/ilcd#',
'impact' =>'http://ontology.earthster.org/eco/impact#',
'impact2002' =>'http://ontology.earthster.org/eco/impact2002Plus#',
'ecoud' =>'http://ontology.earthster.org/eco/uncertaintyDistribution#',
'ecounit' =>'http://ontology.earthster.org/eco/unit#',
'wgs84_pos'=>'http://www.w3.org/2003/01/geo/wgs84_pos#',
'gn'=>'http://www.geonames.org/ontology#',
'owl' => 'http://www.w3.org/2002/07/owl#',
'opencyc' => 'http://sw.opencyc.org/concept/',
'ISO3166' => 'http://downlode.org/Code/RDF/ISO-3166/schema#',
'time' => 'http://www.w3.org/TR/owl-time/',
'nace2' => 'http://ec.europa.eu/eurostat/ramon/rdfdata/nace_r2/',
'nace' => 'http://ec.europa.eu/eurostat/ramon/ontologies/nace.rdf#'
);		    
$config['arc_info']['endpoint_features'] = array(
    'select', 'construct', 'ask', 'describe', // allow read
    'load', 'insert', 'delete',               // allow update
    'dump'                                    // allow backup
  );

