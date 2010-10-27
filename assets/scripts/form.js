
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
