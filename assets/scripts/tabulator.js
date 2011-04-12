function buildMenu() {
	var id = $(this).attr('id');
	$('div[class="tabulator_menu"]').after('<a href="#" id="' + id + '"></a>');
}

$(document).ready(function() { 	
 		$('div[class*="tabulate"]').hide(0, buildMenu);
		$('div[class*="show"][class*="tabulate"]').show();	
});