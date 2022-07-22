( function() {
    tinymce.PluginManager.add( 'aparat_shortcode', function( editor, url ) {

        // Add a button that opens a window
        editor.addButton( 'aparat_shortcode', {
            title: aparat_video_add,
            image: aparat_plugin_url + "/assets/images/aparat-logo.svg",
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
							html: "<span>" + aparat_video_id_insert + "</span><br><span style='font-size: 12px;'>" + aparat_video_id_desc + "</span>"
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
							html: "<br><span>" + aparat_video_width_desc + "</span><br><span style='font-size: 12px;'>" + aparat_video_width_dft + "</span>"
						},
						{
							type: 'listbox',
							name: 'width',
							label: aparat_video_width,
							values: [
								{text: aparat_video_full, value: 'full'},
								{text: aparat_video_half, value: 'half'}
							]
						}
					],
                    onsubmit: function( e ) {
						if ( e.data.aparat ) {
							let aparat_id = e.data.aparat.replace("https://www.aparat.com/v/", "").replace("http://www.aparat.com/v/", "").replace("www.aparat.com/v/", "").trim();
							if ( e.data.width ) {
								editor.insertContent( '[aparat id="' + aparat_id + '" width="' + e.data.width + '"]' );
							}
							else {
								editor.insertContent( '[aparat id="' + aparat_id + '"]' );
							}
						}
                    }

                } );
            }
        } );

    } );
} )();