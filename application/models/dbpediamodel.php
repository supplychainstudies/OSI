<?php
class dbpediamodel extends FT_Model{
    function dbpediamodel(){
        parent::__construct();
		$this->arc_config['store_name'] = "dbpedia";
    }

	public function loadDBpediaEntry($uri) {
		$this->arc_config['store_name'] = "dbpedia";
		$uri2 = $uri.".ntriples";
		$this->isLoaded($uri,$uri2);
	}
	
	public function getDBpediaLabel($uri){
		$this->arc_config['store_name'] = "dbpedia";
		return $this->getLabel($uri);
	}
	
	public function getDBpediaDescription($uri) {
		$this->arc_config['store_name'] = "dbpedia";
		$results = $this->getSomething($uri, "rdfs:comment" , 'en');
		return $results; 
	}
	
	public function getImageURL($uri) {
		return $this->getSomething($uri, "foaf:depiction");
	}
	
	
	
}
