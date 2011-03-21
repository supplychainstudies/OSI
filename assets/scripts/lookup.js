function old_lookup(field, type) {
	var value_field = "#" + field + "_search";
	var value = $(value_field).val();
	$.ajax({ 
		url: "/" + type + "/lookup/" + value, 
		type: "get",
		success: function(data){
			var returned_info = $.parseJSON(data);			
			var results = "";
			for (var i=0; i< returned_info.length; i++) {
				results += "<a>" + returned_info[i].firstName + returned_info[i].lastName + " - " + returned_info[i].email + "</a>";
			}
			$("div[id='" + field + "_searchresults']").html(results);				
	      }
	});
}


function lookup(field, type) {
	if (type == "foaf") {
		var firstName = $("input[name='firstName_']").val();
		var lastName = $("input[name='lastName_']").val();
		var email = $("input[name='email_']").val();
		var data = "";
		if (firstName != "") {
			data += "firstName=" + firstName;
		}
		if (lastName != "") {
			if (data != "") {
				data +="&";
			} 
			data += "lastName=" + lastName;
		}		
		if (email != "") {
			if (data != "") {
				data +="&";
			}
			data += "email=" + email;
		}
	}
	alert(data);
	if (data != "") {
		$.ajax({ 
			url: "/" + type + "/lookup/", 
			type: "post",
			data: data,
			success: function(data){
				var returned_info = $.parseJSON(data);			
				var results = "";
				for (var i=0; i< returned_info.length; i++) {
					results += "<a>" + returned_info[i].firstName + returned_info[i].lastName + " - " + returned_info[i].email + "</a>";
				}
				$("div[id='" + field + "_searchresults']").html(results);				
		      }
		});
	}
}