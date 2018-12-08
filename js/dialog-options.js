jQuery(document).ready(function() {
	jQuery("#dialog-confirm").dialog({
		dialogClass: "no-close",
		resizable: false,
		height: 190,
		autoOpen: false,
		width: 330,
		modal: true,
		buttons: {
			"Borrar": function() {
				$('#borrar-actividad').submit();
			},
			"Cancelar": function() {
				$(this).dialog("close");
			}
		}
	});
	jQuery('#moveOn').on('click', function(e) {
		jQuery("#dialog-confirm").dialog('open');
	});
});