<?// Show the comments ?>
<div id="comments">
<?
if (isset($comments) == true) {
	foreach ($comments as $comment) {
		echo "<div>";
		echo "<p><b>" . $comment['title'] . "</b></p>";
		echo "<p>".$comment['author'] . " - " . $comment['created'] . "</p>";
		echo "<p>" . $comment['comment'] . "</p>";
		if ($this->session->userdata('id') == true) { 
			echo "<p class=\"comments_footer\"><a href=\"#\" name=\"comment_reply" . $comment['post'] . "\">Reply</a></p>\n";		
		}
		if (isset($comment['replies']) == true) {
			printComments($comment['replies']);
		}
		echo "</div><hr />";
	}
}
?>
</div>

<?// If a user is logged in then show the comments form ?>
<? if ($this->session->userdata('id') == true) { ?>
<div id="comment_form">
	<p><input type="hidden" name="comment_uri_" value="http://footprinted.org/osi/rdfspace/lca/<? echo $URI; ?>" /></p>
	<p><input type="hidden" name="user_id" value="<? if(isset($id) == true) { echo $id; } else { echo "Anonymous"; } ?>" /></p>
	<? echo $comment; ?>
</div>
<? }else{?>
	<p><i>Register or login to comment</i></p>
<? } ?>
