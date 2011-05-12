<?php
class Opencycmodel extends FT_Model{
    function opencycmodel(){
        parent::__construct();
		$this->arc_config['store_name'] = "openCyc";
    }

	public function getOpenCycLabel($uri){
		$results =  $this->getLabel($uri);
		return $results;
	}
	
	public function getOpenCycSameAs($uri) {
		return $this->getSomeThings($uri);
	}
	
	public function getDbpedia($uri) {
		return $this->getAll($uri,'');
	}
}
