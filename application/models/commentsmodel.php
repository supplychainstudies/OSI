<?php
class Commentsmodel extends FT_Model{
    function Commentsmodel(){
        parent::__construct();
    }

	public function getComments($uri) {
		$q = "select ?post ?title ?comment ?created ?author where { " . 
			"<" . $uri . "> sioc:post ?post . " . 
			"?post dcterms:title ?title . " . 
			"?post dcterms:created ?created . " .
			"?post sioc:content ?comment . " .
			"?post sioc:hasCreator ?account . " .			
			"?account sioc:userAccount ?author . " . 
			"}";
			//, ?comment, ?title, ?author, ?created	
		$records = $this->executeQuery($q);	
		$comments = $records;
			if(count($records) > 0) {
				$count = 0;
				foreach ($records as $record) {
					$replies = $this->getComments($record['post']);
					if(count($replies) > 0) {
						$comments[$count]['replies'] = $replies;
					}
					$count++;
				}
				return $comments;		
			}
	}
	
} // End Class