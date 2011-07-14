<?php
class Geographymodel extends FT_Model{
    function Geographymodel(){
		parent::__construct();
		$this->load->library(Array('Simplexml'));
		$this->arc_config['store_name'] = "geonames";
    }

	public function getPointGeonames($uri) {
		// check if this uri is already loaded		
		$this->isLoaded($uri);
       $q = "select ?lat ?long ?name where { " .
           "OPTIONAL {<" . $uri . "> wgs84_pos:lat ?lat . } " .    
           "OPTIONAL { <" . $uri . "> wgs84_pos:long ?long . } " .
           " <" . $uri . "> gn:name ?name . " .
           "}";	

		$results = $this->executeQuery($q);
		if (count($results) != 0) {
			return $results[0];
		} else {
			return false;
		}
	}
	
	
	public function getName($uri) {
		// check if this uri is already loaded		
		$this->isLoaded($uri);
       $q = "select ?name where { " .
           " <" . $uri . "> gn:name ?name . " .
           "}";	

		$results = $this->executeQuery($q);
		if (count($results) != 0) {
			return $results[0]['name'];
		} else {
			return false;
		}
	}
	
	public function test($string) {
       $q = "select ?predicate ?subject where { " .
           " ?subject ?predicate '".$string."' . " .    
           "}";	

		$results = $this->executeQuery($q);
		if (count($results) != 0) {
			return $results[0];
		} else {
			return false;
		}
	}	
	
	public function geoEverything($uri) {
       $q = "select * where { " .
           "<" . $uri . "> ?p ?o . " .    
           "}";	
		$results = $this->executeQuery($q);
		var_dump($results);
	}
	
	public function bridge() {
		$xmlfile = "http://osi/assets/data/countries.xml";
		$xmlRaw = file_get_contents($xmlfile);  
		@$parsed = $this->simplexml->xml_parse($xmlRaw);
		$getcountries = array();
		foreach ($parsed['Country'] as $country) {
			$uri = $this->getURIbyName($country['name'][0]['@content']);
			if ($uri != false) {
			$triples = array( 
				array(
					'subject' => $uri[0]['s'],
					'predicate' => 'http://downlode.org/Code/RDF/ISO-3166/schema#alpha_2',
					'object' => $country['alpha_2']
				),
				array(
					'subject' => $uri[0]['s'],
					'predicate' => 'http://downlode.org/Code/RDF/ISO-3166/schema#alpha_3',
					'object' => $country['alpha_3']
				),
			);
			$this->addTriples($triples, $uri[0]['uri']);
			} else {
				$getcountries[] = $country['name'][0]['@content'];
			}
		}
	}
	
	
	public function eu() {
			$uri = $this->getURIbyName("Europe");
			if ($uri != false) {
			$triples = array( 
				array(
					'subject' => $uri[0]['s'],
					'predicate' => 'http://downlode.org/Code/RDF/ISO-3166/schema#alpha_2',
					'object' => "EU"
				),
				array(
					'subject' => $uri[0]['s'],
					'predicate' => 'http://downlode.org/Code/RDF/ISO-3166/schema#alpha_3',
					'object' => "RER"
				),
			);
			var_dump($triples);
			$this->addTriples($triples, $uri[0]['uri']);
		}
	}
	
	public function getURIbyAlpha2($alpha_2) {
       $q = "select * where { GRAPH ?uri {" .
           "?s ISO3166:alpha_2 '".$alpha_2."' . " .            
           "} }";
       $results = $this->executeQuery($q);
		if (count($results) > 0) {
			return $results[0]['s'];
		} else {      	
			return false;	
		}		
	}
	
	public function getURIbyAlpha3($alpha_3) {
       $q = "select * where { GRAPH ?uri {" .
           "?s ISO3166:alpha_3 '".$alpha_3."' . " .            
           "} }";
       $results = $this->executeQuery($q);
		if (count($results) > 0) {
			return $results[0]['s'];
		} else {      	
			return false;	
		}		
	}
	
	public function getURIbyName($string) {		
       $q = "select * where { GRAPH ?uri {" .
           "?s gn:name ?label . " .
           "FILTER regex(?label, '".$string."?', 'i' )" .              
           "} }";
       $results = $this->executeQuery($q);
		if (count($results) > 0) {
			return $results;
		} else {      	
			return false;	
		}
	}
	
	public function getAllCountries() {
		$mc = array(
			array ("Andorra","http://sws.geonames.org/3041565/about.rdf"
			),
			array ("Antigua and Barbuda", "http://sws.geonames.org/3576396/about.rdf"
			),
			array ("Anguilla","http://sws.geonames.org/3573511/about.rdf"
			),
			array ("Albania","http://sws.geonames.org/783754/about.rdf"
			),
			array ("Angola","http://sws.geonames.org/3351879/about.rdf"
			),
			array ("Argentina","http://sws.geonames.org/3865483/about.rdf"
			),
			array ("American Samoa","http://sws.geonames.org/5880801/about.rdf"
			),
			array ("Austria","http://sws.geonames.org/2782113/about.rdf"
			),
			array ("Australia","http://sws.geonames.org/2077456/about.rdf"
			),
			array ("Aruba","http://sws.geonames.org/3577279/about.rdf"
			),
			array ("Bosnia and Herzegovina","http://sws.geonames.org/3277605/about.rdf"
			),
			array ("Barbados","http://sws.geonames.org/3374084/about.rdf"
			),
			array ("Belgium","http://sws.geonames.org/2802361/about.rdf"
			),
			array ("Burkina Faso","http://sws.geonames.org/2361809/about.rdf"
			),
			array ("Bulgaria","http://sws.geonames.org/732800/about.rdf"
			),
			array ("Burundi","http://sws.geonames.org/433561/about.rdf"
			),
			array ("Benin","http://sws.geonames.org/2395170/about.rdf"
			),
			array ("Bermuda","http://sws.geonames.org/3573345/about.rdf"
			),
			array ("Brunei Darussalam","http://sws.geonames.org/1820814/about.rdf"
			),
			array ("Bolivia","http://sws.geonames.org/3923057/about.rdf"
			),
			array ("Brazil","http://sws.geonames.org/3469034/about.rdf"
			),
			array ("Bahamas","http://sws.geonames.org/3572887/about.rdf"
			),
			array ("Bouvet Island","http://sws.geonames.org/3371123/about.rdf"
			),
			array ("Botswana","http://sws.geonames.org/933860/about.rdf"
			),
			array ("Belarus","http://sws.geonames.org/630336/about.rdf"
			),
			array ("Belize","http://sws.geonames.org/3582678/about.rdf"
			),
			array ("Congo, the Democratic Republic of the","http://sws.geonames.org/203312/about.rdf"
			),
			array ("Central African Republic","http://sws.geonames.org/239880/about.rdf"
			),
			array ("Congo","http://sws.geonames.org/2260494/about.rdf"
			),
			array ("Switzerland","http://sws.geonames.org/2658434/about.rdf"
			),
			array ("Cote d\'Ivoire","http://sws.geonames.org/2287781/about.rdf"
			),
			array ("Cook Islands","http://sws.geonames.org/1899402/about.rdf"
			),
			array ("Chile","http://sws.geonames.org/3895114/about.rdf"
			),
			array ("Cameroon","http://sws.geonames.org/2233387/about.rdf"
			),
			array ("Colombia","http://sws.geonames.org/3686110/about.rdf"
			),
			array ("Costa Rica","http://sws.geonames.org/3624060/about.rdf"
			),
			array ("Serbia and Montenegro","http://sws.geonames.org/6290252/about.rdf"
			),
			array ("Serbia and Montenegro","http://sws.geonames.org/3194884/about.rdf"
			),
			array ("Cuba","http://sws.geonames.org/3562981/about.rdf"
			),
			array ("Cape Verde","http://sws.geonames.org/3374766/about.rdf"
			),
			array ("Cyprus","http://sws.geonames.org/146669/about.rdf"
			),
			array ("Czech Republic","http://sws.geonames.org/3077311/about.rdf"
			),
			array ("Germany","http://sws.geonames.org/2921044/about.rdf"
			),
			array ("Djibouti","http://sws.geonames.org/223816/about.rdf"
			),
			array ("Denmark","http://sws.geonames.org/2623032/about.rdf"
			),
			array ("Dominica","http://sws.geonames.org/3575830/about.rdf"
			),
			array ("Dominican Republic","http://sws.geonames.org/3508796/about.rdf"
			),
			array ("Algeria","http://sws.geonames.org/2589581/about.rdf"
			),
			array ("Ecuador","http://sws.geonames.org/3658394/about.rdf"
			),
			array ("Estonia","http://sws.geonames.org/453733/about.rdf"
			),
			array ("Egypt","http://sws.geonames.org/357994/about.rdf"
			),
			array ("Western Sahara","http://sws.geonames.org/2461445/about.rdf"
			),
			array ("Eritrea","http://sws.geonames.org/338010/about.rdf"
			),
			array ("Spain","http://sws.geonames.org/2510769/about.rdf"
			),
			array ("Ethiopia","http://sws.geonames.org/337996/about.rdf"
			),
			array ("Finland","http://sws.geonames.org/660013/about.rdf"
			),
			array ("Fiji","http://sws.geonames.org/2205218/about.rdf"
			),
			array ("Falkland Islands (Malvinas)","http://sws.geonames.org/3474414/about.rdf"
			),
			array ("Micronesia, Federated States of","http://sws.geonames.org/2081918/about.rdf"
			),
			array ("Faroe Islands","http://sws.geonames.org/2622320/about.rdf"
			),
			array ("France","http://sws.geonames.org/3017382/about.rdf"
			),
			array ("Gabon","http://sws.geonames.org/2400553/about.rdf"
			),
			array ("Grenada","http://sws.geonames.org/3580239/about.rdf"
			),
			array ("French Guiana","http://sws.geonames.org/3381670/about.rdf"
			),
			array ("Gibraltar","http://sws.geonames.org/2411586/about.rdf"
			),
			array ("Greenland","http://sws.geonames.org/3425505/about.rdf"
			),
			array ("Gambia","http://sws.geonames.org/2413451/about.rdf"
			),
			array ("Guinea","http://sws.geonames.org/2420477/about.rdf"
			),
			array ("Guadeloupe","http://sws.geonames.org/3579143/about.rdf"
			),
			array ("Equatorial Guinea","http://sws.geonames.org/2309096/about.rdf"
			),
			array ("Greece","http://sws.geonames.org/390903/about.rdf"
			),
			array ("South Georgia and the South Sandwich Islands","http://sws.geonames.org/3474415/about.rdf"
			),
			array ("Guatemala","http://sws.geonames.org/3595528/about.rdf"
			),
			array ("Guam","http://sws.geonames.org/4043988/about.rdf"
			),
			array ("Guinea-bissau","http://sws.geonames.org/2372248/about.rdf"
			),
			array ("Guyana","http://sws.geonames.org/3378535/about.rdf"
			),
			array ("Heard Island and Mcdonald Islands","http://sws.geonames.org/1547314/about.rdf"
			),
			array ("Honduras","http://sws.geonames.org/3608932/about.rdf"
			),
			array ("Croatia","http://sws.geonames.org/3202326/about.rdf"
			),
			array ("Haiti","http://sws.geonames.org/3723988/about.rdf"
			),
			array ("Hungary","http://sws.geonames.org/719819/about.rdf"
			),
			array ("Ireland","http://sws.geonames.org/2963597/about.rdf"
			),
			array ("Iran","http://sws.geonames.org/130758/about.rdf"
			),
			array ("Iceland","http://sws.geonames.org/2629691/about.rdf"
			),
			array ("Italy","http://sws.geonames.org/3175395/about.rdf"
			),
			array ("Jamaica","http://sws.geonames.org/3489940/about.rdf"
			),
			array ("Kenya","http://sws.geonames.org/192950/about.rdf"
			),
			array ("Kiribati","http://sws.geonames.org/4030945/about.rdf"
			),
			array ("Comoros","http://sws.geonames.org/921929/about.rdf"
			),
			array ("Saint Kitts and Nevis","http://sws.geonames.org/3575174/about.rdf"
			),
			array ("Democratic People's Republic of Korea","http://sws.geonames.org/1873107/about.rdf"
			),
			array ("Republic of Korea","http://sws.geonames.org/1835841/about.rdf"
			),
			array ("Cayman Islands","http://sws.geonames.org/3580718/about.rdf"
			),
			array ("Lao People's Democratic Republic","http://sws.geonames.org/1655842/about.rdf"
			),
			array ("Saint Lucia","http://sws.geonames.org/3576468/about.rdf"
			),
			array ("Liechtenstein","http://sws.geonames.org/3042058/about.rdf"
			),
			array ("Liberia","http://sws.geonames.org/2275384/about.rdf"
			),
			array ("Lesotho","http://sws.geonames.org/932692/about.rdf"
			),
			array ("Lithuania","http://sws.geonames.org/597427/about.rdf"
			),
			array ("Luxembourg","http://sws.geonames.org/2960313/about.rdf"
			),
			array ("Latvia","http://sws.geonames.org/458258/about.rdf"
			),
			array ("Libyan Arab Jamahiriya","http://sws.geonames.org/2215636/about.rdf"
			),
			array ("Morocco","http://sws.geonames.org/2542007/about.rdf"
			),
			array ("Monaco","http://sws.geonames.org/2993457/about.rdf"
			),
			array ("Moldova, Republic of","http://sws.geonames.org/617790/about.rdf"
			),
			array ("Madagascar","http://sws.geonames.org/1062947/about.rdf"
			),
			array ("Marshall Islands","http://sws.geonames.org/2080185/about.rdf"
			),
			array ("Macedonia, the Former Yugoslav Republic of","http://sws.geonames.org/718075/about.rdf"
			),
			array ("Northern Mariana Islands","http://sws.geonames.org/4041468/about.rdf"
			),
			array ("Martinique","http://sws.geonames.org/3570311/about.rdf"
			),
			array ("Mauritania","http://sws.geonames.org/2378080/about.rdf"
			),
			array ("Montserrat","http://sws.geonames.org/3578097/about.rdf"
			),
			array ("Malta","http://sws.geonames.org/2562770/about.rdf"
			),
			array ("Mauritius","http://sws.geonames.org/934292/about.rdf"
			),
			array ("Malawi","http://sws.geonames.org/927384/about.rdf"
			),
			array ("Mexico","http://sws.geonames.org/3996063/about.rdf"
			),
			array ("Mozambique","http://sws.geonames.org/1036973/about.rdf"
			),
			array ("Namibia","http://sws.geonames.org/3355338/about.rdf"
			),
			array ("New Caledonia","http://sws.geonames.org/2139685/about.rdf"
			),
			array ("Niger","http://sws.geonames.org/2440476/about.rdf"
			),
			array ("Norfolk Island","http://sws.geonames.org/2155115/about.rdf"
			),
			array ("Nigeria","http://sws.geonames.org/2328926/about.rdf"
			),
			array ("Nicaragua","http://sws.geonames.org/3617476/about.rdf"
			),
			array ("Netherlands","http://sws.geonames.org/2750405/about.rdf"
			),
			array ("Norway","http://sws.geonames.org/3144096/about.rdf"
			),
			array ("Nauru","http://sws.geonames.org/2110425/about.rdf"
			),
			array ("Niue","http://sws.geonames.org/4036232/about.rdf"
			),
			array ("New Zealand","http://sws.geonames.org/2186224/about.rdf"
			),
			array ("Panama","http://sws.geonames.org/3703430/about.rdf"
			),
			array ("Peru","http://sws.geonames.org/3932488/about.rdf"
			),
			array ("French Polynesia","http://sws.geonames.org/4030656/about.rdf"
			),
			array ("Papua New Guinea","http://sws.geonames.org/2088628/about.rdf"
			),
			array ("Poland","http://sws.geonames.org/798544/about.rdf"
			),
			array ("Saint Pierre and Miquelon","http://sws.geonames.org/3424932/about.rdf"
			),
			array ("Pitcairn","http://sws.geonames.org/4030699/about.rdf"
			),
			array ("Puerto Rico","http://sws.geonames.org/4566966/about.rdf"
			),
			array ("Palestinian Territory, Occupied", "http://sws.geonames.org/6254930/about.rdf"
			),
			array ("Portugal","http://sws.geonames.org/2264397/about.rdf"
			),
			array ("Palau","http://sws.geonames.org/1559582/about.rdf"
			),
			array ("Paraguay","http://sws.geonames.org/3437598/about.rdf"
			),
			array ("Reunion","http://sws.geonames.org/935317/about.rdf"
			),
			array ("Romania","http://sws.geonames.org/798549/about.rdf"
			),
			array ("Russian Federation","http://sws.geonames.org/2017370/about.rdf"
			),
			array ("Rwanda","http://sws.geonames.org/49518/about.rdf"
			),
			array ("Solomon Islands","http://sws.geonames.org/2103350/about.rdf"
			),
			array ("Seychelles","http://sws.geonames.org/241170/about.rdf"
			),
			array ("Sudan","http://sws.geonames.org/366755/about.rdf"
			),
			array ("Sweden","http://sws.geonames.org/2661886/about.rdf"
			),
			array ("Saint Helena","http://sws.geonames.org/3370751/about.rdf"
			),
			array ("Slovenia","http://sws.geonames.org/3190538/about.rdf"
			),
			array ("Svalbard and Jan Mayen","http://sws.geonames.org/607072/about.rdf"
			),
			array ("Slovakia","http://sws.geonames.org/3057568/about.rdf"
			),
			array ("Sierra Leone","http://sws.geonames.org/2403846/about.rdf"
			),
			array ("San Marino","http://sws.geonames.org/3168068/about.rdf"
			),
			array ("Senegal","http://sws.geonames.org/2245662/about.rdf"
			),
			array ("Somalia","http://sws.geonames.org/51537/about.rdf"
			),
			array ("Suriname","http://sws.geonames.org/3382998/about.rdf"
			),
			array ("Sao Tome and Principe","http://sws.geonames.org/2410758/about.rdf"
			),
			array ("El Salvador","http://sws.geonames.org/3585968/about.rdf"
			),
			array ("Syrian Arab Republic","http://sws.geonames.org/163843/about.rdf"
			),
			array ("Swaziland","http://sws.geonames.org/163843/about.rdf"
			),
			array ("Turks and Caicos Islands","http://sws.geonames.org/3576916/about.rdf"
			),
			array ("Chad","http://sws.geonames.org/2434508/about.rdf"
			),
			array ("French Southern Territories","http://sws.geonames.org/1546748/about.rdf"
			),
			array ("Togo","http://sws.geonames.org/2363686/about.rdf"
			),
			array ("Tokelau","http://sws.geonames.org/4031074/about.rdf"
			),
			array ("Timor-leste","http://sws.geonames.org/1966436/about.rdf"
			),
			array ("Tunisia","http://sws.geonames.org/2464461/about.rdf"
			),
			array ("Tonga","http://sws.geonames.org/4032283/about.rdf"
			),
			array ("Trinidad and Tobago","http://sws.geonames.org/3573591/about.rdf"
			),
			array ("Tuvalu","http://sws.geonames.org/2110297/about.rdf"
			),
			array ("Taiwan","http://sws.geonames.org/1668284/about.rdf"
			),
			array ("Tanzania","http://sws.geonames.org/149590/about.rdf"
			),
			array ("Ukraine","http://sws.geonames.org/690791/about.rdf"
			),
			array ("Uganda","http://sws.geonames.org/226074/about.rdf"
			),
			array ("United States Minor Outlying Islands","http://sws.geonames.org/5854968/about.rdf"
			),
			array ("United States","http://sws.geonames.org/6252001/about.rdf"
			),
			array ("Uruguay","http://sws.geonames.org/3439705/about.rdf"
			),
			array ("Holy See (Vatican City State)","http://sws.geonames.org/3164670/about.rdf"
			),
			array ("Saint Vincent and the Grenadines","http://sws.geonames.org/3577815/about.rdf"
			),
			array ("Venezuela","http://sws.geonames.org/3625428/about.rdf"
			),
			array ("Virgin Islands, British","http://sws.geonames.org/3577718/about.rdf"
			),
			array ("Virgin Islands, U.S.","http://sws.geonames.org/4796775/about.rdf"
			),
			array ("Viet Nam","http://sws.geonames.org/1562822/about.rdf"
			),
			array ("Vanuatu","http://sws.geonames.org/2134431/about.rdf"
			),
			array ("Wallis and Futuna","http://sws.geonames.org/4034749/about.rdf"
			),
			array ("Samoa","http://sws.geonames.org/4034894/about.rdf"
			),
			array ("Mayotte","http://sws.geonames.org/1024031/about.rdf"
			),
			array ("South Africa","http://sws.geonames.org/953987/about.rdf"
			),
			array ("Zambia","http://sws.geonames.org/895949/about.rdf"
			),
			array ("Zimbabwe","http://sws.geonames.org/878675/about.rdf"
			)
		);
		foreach ($mc as $c) {
			$this->isloaded(str_replace("about.rdf","",$c[1]));
		}
		
	}

	public function getAllCountries2() {
		$mc = array(
			array ("Anguilla","http://sws.geonames.org/3573511/about.rdf"
			),
			array ("Austria","http://sws.geonames.org/2782113/about.rdf"
			),
			array ("Brazil","http://sws.geonames.org/3469034/about.rdf"
			),
			array ("Bahamas","http://sws.geonames.org/3572887/about.rdf"
			),
			array ("Bouvet Island","http://sws.geonames.org/3371123/about.rdf"
			),
			array ("Congo, the Democratic Republic of the","http://sws.geonames.org/203312/about.rdf"
			),
			array ("Chile","http://sws.geonames.org/3895114/about.rdf"
			),
			array ("Serbia and Montenegro","http://sws.geonames.org/3194884/about.rdf"
			),
			array ("Cuba","http://sws.geonames.org/3562981/about.rdf"
			),
			array ("Cyprus","http://sws.geonames.org/146669/about.rdf"
			),
			array ("Djibouti","http://sws.geonames.org/223816/about.rdf"
			),
			array ("Denmark","http://sws.geonames.org/2623032/about.rdf"
			),
			array ("Ecuador","http://sws.geonames.org/3658394/about.rdf"
			),
			array ("Estonia","http://sws.geonames.org/453733/about.rdf"
			),
			array ("Egypt","http://sws.geonames.org/357994/about.rdf"
			),
			array ("Eritrea","http://sws.geonames.org/338010/about.rdf"
			),
			array ("Finland","http://sws.geonames.org/660013/about.rdf"
			),
			array ("Fiji","http://sws.geonames.org/2205218/about.rdf"
			),
			array ("Falkland Islands (Malvinas)","http://sws.geonames.org/3474414/about.rdf"
			),
			array ("Micronesia, Federated States of","http://sws.geonames.org/2081918/about.rdf"
			),
			array ("France","http://sws.geonames.org/3017382/about.rdf"
			),
			array ("French Guiana","http://sws.geonames.org/3381670/about.rdf"
			),
			array ("South Georgia and the South Sandwich Islands","http://sws.geonames.org/3474415/about.rdf"
			),
			array ("Guinea-bissau","http://sws.geonames.org/2372248/about.rdf"
			),
			array ("Guyana","http://sws.geonames.org/3378535/about.rdf"
			),
			array ("Heard Island and Mcdonald Islands","http://sws.geonames.org/1547314/about.rdf"
			),
			array ("Honduras","http://sws.geonames.org/3608932/about.rdf"
			),
			array ("Croatia","http://sws.geonames.org/3202326/about.rdf"
			),
			array ("Italy","http://sws.geonames.org/3175395/about.rdf"
			),
			array ("Jamaica","http://sws.geonames.org/3489940/about.rdf"
			),
			array ("Democratic People's Republic of Korea","http://sws.geonames.org/1873107/about.rdf"
			),
			array ("Cayman Islands","http://sws.geonames.org/3580718/about.rdf"
			),
			array ("Lao People's Democratic Republic","http://sws.geonames.org/1655842/about.rdf"
			),
			array ("Saint Lucia","http://sws.geonames.org/3576468/about.rdf"
			),
			array ("Liechtenstein","http://sws.geonames.org/3042058/about.rdf"
			),
			array ("Libyan Arab Jamahiriya","http://sws.geonames.org/2215636/about.rdf"
			),
			array ("Morocco","http://sws.geonames.org/2542007/about.rdf"
			),
			array ("Monaco","http://sws.geonames.org/2993457/about.rdf"
			),
			array ("Moldova, Republic of","http://sws.geonames.org/617790/about.rdf"
			),
			array ("Mauritania","http://sws.geonames.org/2378080/about.rdf"
			),
			array ("Malta","http://sws.geonames.org/2562770/about.rdf"
			),
			array ("Norfolk Island","http://sws.geonames.org/2155115/about.rdf"
			),
			array ("Puerto Rico","http://sws.geonames.org/4566966/about.rdf"
			),
			array ("Palau","http://sws.geonames.org/1559582/about.rdf"
			),
			array ("Paraguay","http://sws.geonames.org/3437598/about.rdf"
			),
			array ("Reunion","http://sws.geonames.org/935317/about.rdf"
			),
			array ("Romania","http://sws.geonames.org/798549/about.rdf"
			),
			array ("Russian Federation","http://sws.geonames.org/2017370/about.rdf"
			),
			array ("Solomon Islands","http://sws.geonames.org/2103350/about.rdf"
			),
			array ("Seychelles","http://sws.geonames.org/241170/about.rdf"
			),
			array ("Sudan","http://sws.geonames.org/366755/about.rdf"
			),
			array ("Saint Helena","http://sws.geonames.org/3370751/about.rdf"
			),
			array ("Slovenia","http://sws.geonames.org/3190538/about.rdf"
			),
			array ("Svalbard and Jan Mayen","http://sws.geonames.org/607072/about.rdf"
			),
			array ("Slovakia","http://sws.geonames.org/3057568/about.rdf"
			),
			array ("Sierra Leone","http://sws.geonames.org/2403846/about.rdf"
			),
			array ("San Marino","http://sws.geonames.org/3168068/about.rdf"
			),
			array ("Senegal","http://sws.geonames.org/2245662/about.rdf"
			),
			array ("Somalia","http://sws.geonames.org/51537/about.rdf"
			),
			array ("Suriname","http://sws.geonames.org/3382998/about.rdf"
			),
			array ("Sao Tome and Principe","http://sws.geonames.org/2410758/about.rdf"
			),
			array ("El Salvador","http://sws.geonames.org/3585968/about.rdf"
			),
			array ("Syrian Arab Republic","http://sws.geonames.org/163843/about.rdf"
			),
			array ("Swaziland","http://sws.geonames.org/163843/about.rdf"
			),
			array ("Turks and Caicos Islands","http://sws.geonames.org/3576916/about.rdf"
			),
			array ("Chad","http://sws.geonames.org/2434508/about.rdf"
			),
			array ("French Southern Territories","http://sws.geonames.org/1546748/about.rdf"
			),
			array ("Togo","http://sws.geonames.org/2363686/about.rdf"
			),
			array ("Tokelau","http://sws.geonames.org/4031074/about.rdf"
			),
			array ("Timor-leste","http://sws.geonames.org/1966436/about.rdf"
			),
			array ("Tunisia","http://sws.geonames.org/2464461/about.rdf"
			),
			array ("Tonga","http://sws.geonames.org/4032283/about.rdf"
			),
			array ("Trinidad and Tobago","http://sws.geonames.org/3573591/about.rdf"
			),
			array ("Tuvalu","http://sws.geonames.org/2110297/about.rdf"
			),
			array ("Taiwan","http://sws.geonames.org/1668284/about.rdf"
			),
			array ("Tanzania","http://sws.geonames.org/149590/about.rdf"
			),
			array ("Ukraine","http://sws.geonames.org/690791/about.rdf"
			),
			array ("Uganda","http://sws.geonames.org/226074/about.rdf"
			),
			array ("United States Minor Outlying Islands","http://sws.geonames.org/5854968/about.rdf"
			),
			array ("United States","http://sws.geonames.org/6252001/about.rdf"
			),
			array ("Uruguay","http://sws.geonames.org/3439705/about.rdf"
			),
			array ("Holy See (Vatican City State)","http://sws.geonames.org/3164670/about.rdf"
			),
			array ("Saint Vincent and the Grenadines","http://sws.geonames.org/3577815/about.rdf"
			),
			array ("Venezuela","http://sws.geonames.org/3625428/about.rdf"
			),
			array ("Virgin Islands, British","http://sws.geonames.org/3577718/about.rdf"
			),
			array ("Virgin Islands, U.S.","http://sws.geonames.org/4796775/about.rdf"
			),
			array ("Viet Nam","http://sws.geonames.org/1562822/about.rdf"
			),
			array ("Vanuatu","http://sws.geonames.org/2134431/about.rdf"
			),
			array ("Wallis and Futuna","http://sws.geonames.org/4034749/about.rdf"
			),
			array ("Samoa","http://sws.geonames.org/4034894/about.rdf"
			),
			array ("Mayotte","http://sws.geonames.org/1024031/about.rdf"
			),
			array ("South Africa","http://sws.geonames.org/953987/about.rdf"
			),
			array ("Zambia","http://sws.geonames.org/895949/about.rdf"
			),
			array ("Zimbabwe","http://sws.geonames.org/878675/about.rdf"
			)
		);
		foreach ($mc as $c) {
			$this->isloaded(str_replace("about.rdf","",$c[1]));
		}
		
	}




	
} // End Class