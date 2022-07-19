( function() {
    tinymce.PluginManager.add( 'aparat_shortcode', function( editor, url ) {

        // Add a button that opens a window
        editor.addButton( 'aparat_shortcode', {

            title: aparat_video_add,
            image: url + "/aparat-logo.png",
			size: "large",
            onclick: function() {
                // Open window
                editor.windowManager.open( {
                    title: aparat_video_add,
                    body: [
						{
							type: 'container',
							name: 'container',
							label: '',
							html: "<span style='font-size: 12px;'>" + aparat_video_id_insert + "<br>" + aparat_video_id_desc + "</span>"
						},
						{
							type: 'textbox',
							name: 'aparat',
							label: aparat_video_id
						},
						{
							type: 'container',
							name: 'container',
							label: '',
							html: "<br><span style='font-size: 12px;'>" + aparat_video_width_desc + "</span><br><span style='font-size: 12px;'>" + aparat_video_width_dft + "</span>"
						},
						{
							type: 'textbox',
							name: 'width',
							label: aparat_video_width
						}
					],
                    onsubmit: function( e ) {
						if ( e.data.aparat ) {
							if ( e.data.width && jQuery.isNumeric(e.data.width) ) {
								editor.insertContent( '[aparat id="' + e.data.aparat + '" width="' + e.data.width + '"]' );
							}
							else {
								editor.insertContent( '[aparat id="' + e.data.aparat + '"]' );
							}
						}
                    }

                } );
            }

        } );

    } );

} )();