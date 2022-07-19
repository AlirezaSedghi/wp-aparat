jQuery(document).ready(function(jQuery) {
	jQuery('#upload_image_button').click(function(e) {
		
		e.preventDefault();
		
		var custom_uploader = wp.media({
			title: wpaparat.title,
			button: {
				text: wpaparat.buttontext
			},
			multiple: false  // Set this to true to allow multiple files to be selected
		})
		.on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			jQuery('.wpaparat_thumbnail').attr('src', attachment.url);
			jQuery('.thumnail_pic_url').val(attachment.url);

		})
		.open();
		
	});
	
	jQuery('#default_image_button').click(function(e) {
		
		jQuery('.wpaparat_thumbnail').attr('src', jQuery( this ).data( "defaultimg" ));
		//jQuery('.thumnail_pic_url').val(jQuery( this ).data( "defaultimg" ));
		jQuery('.thumnail_pic_url').val("");

	});
});