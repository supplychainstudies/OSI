<?
function printComments($comments, $id) {
	if (isset($comments) == true) {
		foreach ($comments as $comment) {
			echo "<div>";
			echo "<p><b>" . $comment['title'] . "</b></p>";
			echo "<p>".$comment['author'] . " - " . $comment['created'] . "</p>";
			echo "<p>" . $comment['comment'] . "</p>";
			if ($id != false) { 
				echo "<p class=\"comments_footer\"><a href=\"#comment_reply" . $comment['post'] . "\" name=\"comment_reply" . $comment['post'] . "\">Reply</a></p>\n";		
			}
			if (isset($comment['replies']) == true) {
				printComments($comment['replies'], $id);
			}
			echo "</div><hr />";
		}
	}
}

?>