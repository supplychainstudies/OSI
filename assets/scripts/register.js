$("input[name^='pre_']").each(function() {
	var value = $(this).attr('value');
	var name = $(this).attr('name');
	name = name.replace("pre_", "") + "_";
	$("input[name^='" + name + "']").val(value);
	//$("input[name^='" + name + "']").attr("disabled","disabled");	
});

