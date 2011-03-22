function toggle(idname) {
	$('#div_' + idname).slideToggle('slow', function() {

	// Animation complete.

	});		
}

	$('.toggler').click(function() {

		$('.toggle').slideToggle('slow', function() {

		// Animation complete.

		});

	});

	$('#openid_toggler').click(function() {

		$('#osi_toggle').slideToggle('slow', function() {

		// Animation complete.

		});

	});

	$('#osi_toggler').click(function() {

		$('#openid_toggle').slideToggle('slow', function() {

		// Animation complete.

		});

	});
