$(".dialog").dialog({
			autoOpen: false,
			title: 'Basic Dialog'
		});
$('.hide').hide();	
$('.show').show();	
$(".popup").click(function() {
	var the_id = $(this).attr("id");
	var the_name = $(this).attr("name");
	$("#"+the_id+"_dialog").dialog('open');
	$("[name='" + the_id + "_field']").val(the_name);
	// prevent the default action, e.g., following a link
	return false;
});	

// Changes Unit Sub-menu 
$("[name='unit_main']").change(function() { 
$("[name^='unit'][class*='hide'][name!='unit_main']").hide(); 
$("[name='unit_"+$(this).val()+"']").show();
});

$("[name^='unit'][name!='unit_main']").change(function() {
	var the_value = $(this).val();
	var field = $("[name='unit_field']").val();
	alert(field);
	var the_label_field = field.replace("_button", "_label");
	var the_hidden_field = field.replace("_button" ,"");
	alert(the_label_field);
	alert(the_hidden_field);
	$("[name='"+the_label_field+"']").val(the_value);
	$("[name='"+the_hidden_field+"']").val(the_value);
}); 

function addField(name, path) {	
	var array_path = "[" + path.replace(/-/g, "][") + "]";
	var to_copy = "#div_" + name;
	var to_paste = "#div_multiple_" + name + "_" + path;	
	var to_duplicate = $(to_copy).html();
	var to_count = "#" + name + "_counter_" + path;	
	var increment = $(to_count).val();
	increment = parseInt(increment) + 1;
	if (path.length > 2) {
	var one_less = path.slice(0, path.length -2);

	var new_array_path = '_[' + path.slice(0, path.length -2).replace(/-/g, "][") + ']' + '[' + increment + ']'; 
	var new_path = '_' + path.slice(0, path.length -2) + '-' + increment;		
	} else {
		var new_array_path = '_[' + increment + ']"';
		var new_path = '_' + increment;
	}	
	array_path = "_" + array_path;
	path = "_" + path;	
	var duplicated = to_duplicate;
	while (duplicated.indexOf(path) != -1) {
		duplicated = duplicated.replace(path,new_path);
	}
	while (duplicated.indexOf(array_path) != -1) {
		duplicated = duplicated.replace(array_path,new_array_path);
	}
	$(to_paste).append(duplicated);
	$(to_count).val(increment);
}

function toggle_delete(field) {
	if ($("input[name='" + field + "']").is(':disabled') == false) {
		$("input[name='" + field + "']").attr('disabled', 'disabled');		
	} else {
		$("input[name='" + field + "']").removeAttr('disabled');
	}
}

$("form").submit(function() {
	var to_submit = true;
  $(".required").each(function() {
		if($(this).val() == "") {
			$(this).addClass('require');		
			to_submit = false;
		} else {
			$(this).addClass('require_ok');		
		}
	});
	return to_submit;
});