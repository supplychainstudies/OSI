<?// Show the comments ?>
<? $this->load->helper("comments"); ?>
<div id="comments">
<? if (isset($comments) == true) { printComments($comments,$this->session->userdata('id')); } ?>
</div>
<?// If a user is logged in then show the comments form ?>
<? if ($this->session->userdata('id') == true) { ?>
<div id="comment_form">
	<p><input type="hidden" name="comment_uri_" value="http://footprinted.org/osi/rdfspace/lca/<? echo $URI; ?>" /></p>
	<p><input type="hidden" name="user_foaf" value="<? if($this->session->userdata('foaf') == true) { echo $this->session->userdata('foaf'); } else { echo $this->session->userdata('id'); } ?>" /></p>
	<p><input type="hidden" name="user_id" value="<? if($this->session->userdata('id') == true) { echo $this->session->userdata('id'); } ?>" /></p>
	<? echo $comment_form; ?>
</div>
<? }else{?>
	<p><i>Register or login to comment</i></p>
<? } ?>
