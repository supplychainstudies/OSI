//$("input[name='comment_submit_']").click(function() {
	//var moo = $(this).attr('name');
//	alert('moo');
	//$.ajax({ 
	//	url: "/comments/post/", 
	//	type: "post",
	//	data: "uri="++"&title="++"&comment="++"&author="+,
	//	success: function(){
	//        $(this).addClass("done");
	//      }
	//});
//});

function submitComment() {
	var str = $(this).attr('name');
	
	var uri_value = $("input[name^='" + str.replace('submit','uri') + "']").val();
	var author_value = $("input[name^='user_id']").val();
	var comment_value = $("textarea[name^='" + str.replace('submit','comment') + "']").val();
	var title_value = $("input[name^='" + str.replace('submit','title') + "']").val();		
	$.ajax({ 
		url: "/comments/post/", 
		type: "post",
		data: "uri=" + uri_value + "&title=" + title_value + "&comment=" + comment_value + "&author=" + author_value,
		success: function(data){
			var returned_info = $.parseJSON(data);			
			var new_post = "<div class=\"comments_container\">\n";
			new_post += "<p class=\"comments_title\">" + title_value + "</p>\n";
			new_post += "<p class=\"comments_subtitle\">" + author_value + " - " + returned_info.date + "</p>";
			new_post += "<p>" + comment_value + "</p>\n";
			new_post += "<p class=\"comments_footer\"><a href=\"#\" name=\"comment_reply" + returned_info.post + "\" \>Reply</a></p>\n</div><hr />";
			$("div[id='comment" + str.replace('comment_submit','') + "']").html(new_post);				
	      }
	});
	
}


$(document).ready(function () {

	$("input[name^='comment_submit_']").click(submitComment);	
	
	$("a[name^='comment_reply']").click( function () {
		var node = $(this).attr('name');
		node = node.replace("comment_reply", "");
		var form_text = $("#comment").html();
		form_text = "<div id=\"comment" + node + "\">" + form_text + "</div>";
		form_text = form_text.replace("comment_submit_", "comment_submit" + node).replace("comment_title_", "comment_title" + node).replace("comment_comment_", "comment_comment" + node).replace("comment_uri_", "comment_uri" + node);

alert(form_text);
		$(this).after(form_text);
		$("input[name='comment_uri" + node + "']").val(node);		
		$("input[name^='comment_submit_']").click(submitComment);			
	});
});
