jQuery(document).ready(function() {
	jQuery( "#imgup" ).click(function() {
		tb_show( "", "media-upload.php?type=image&amp;TB_iframe=true" );
		return false;
	});

	window.send_to_editor = function( html ) {
		imgurl = jQuery( "img", html ).attr( "src" );
		jQuery( "#image" ).val( imgurl );
		tb_remove();
	}
});