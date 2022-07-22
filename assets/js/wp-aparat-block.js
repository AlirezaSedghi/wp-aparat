( function ( blocks, editor, i18n, element, components, _, blockEditor ) {
    let __ = i18n.__;
    let el = element.createElement;
    let RichText = blockEditor.RichText;
    let useBlockProps = blockEditor.useBlockProps;

    const aparatIcon = el('svg', { width: 24, height: 24, viewBox: "0 0 75 75" },
        el('path', { fill: "#231F20", d: "M32.8,5.3l-6.3-1.6c-5.6-1.5-11.4,1.9-12.9,7.5l0,0L12,17.1C17.2,10.7,24.6,6.5,32.8,5.3z" } ),
        el('path', { fill: "#231F20", d: "M5.2,42.7l-1.5,5.8c-1.5,5.7,1.8,11.5,7.5,13c0,0,0,0,0,0l6,1.6C10.8,58,6.5,50.7,5.2,42.7z" } ),
        el('path', { fill: "#231F20", d: "M63.8,13.6l-6.7-1.8c6.9,5.3,11.3,13.1,12.4,21.7l1.8-7C72.8,20.8,69.4,15.1,63.8,13.6z" } ),
        el('path', { fill: "#231F20", d: "M42,69.6l6.5,1.7c5.6,1.5,11.4-1.9,12.9-7.5c0,0,0,0,0,0l1.8-6.8C58.1,63.8,50.5,68.4,42,69.6z" } ),
        el('path', { fill: "#ED145B", d: "M37.5,7.9C21.1,7.9,7.9,21.1,7.9,37.5s13.3,29.6,29.6,29.6c16.4,0,29.6-13.3,29.6-29.6l0,0 C67.1,21.1,53.9,7.9,37.5,7.9z M20.7,22.6c0.9-4.6,5.3-7.6,9.9-6.7s7.6,5.3,6.7,9.9c-0.9,4.6-5.3,7.6-9.9,6.7 C22.8,31.7,19.8,27.2,20.7,22.6z M33,48.3c-0.9,4.6-5.3,7.6-9.9,6.7s-7.6-5.3-6.7-9.9c0.9-4.6,5.3-7.6,9.9-6.7 C30.9,39.2,33.9,43.7,33,48.3C33,48.2,33,48.2,33,48.3z M36.6,41.4c-2-0.4-3.4-2.4-3-4.4c0.4-2,2.4-3.4,4.4-3c2,0.4,3.4,2.4,3,4.4 C40.6,40.4,38.7,41.8,36.6,41.4C36.6,41.4,36.6,41.4,36.6,41.4z M54.3,52.3c-0.9,4.6-5.3,7.6-9.9,6.7s-7.6-5.3-6.7-9.9 c0.9-4.6,5.3-7.6,9.9-6.7C52.1,43.3,55.2,47.7,54.3,52.3C54.3,52.3,54.3,52.3,54.3,52.3z M48.7,36.7c-4.6-0.9-7.6-5.3-6.7-9.9 s5.3-7.6,9.9-6.7c4.6,0.9,7.6,5.3,6.7,9.9C57.7,34.5,53.3,37.5,48.7,36.7C48.7,36.7,48.7,36.7,48.7,36.7L48.7,36.7z" } )
    );

    blocks.registerBlockType( 'wp-aparat/aparat-block', {
        title: __( 'Aparat', 'wp-aparat' ),
        description: __( 'Embed Aparat video in the content', 'wp-aparat' ),
        icon: aparatIcon,
        category: 'media',
        keywords: [ "aparat", "embed", "آپارات", "video", "ویدیو" ],
        attributes: {
            iframeID: {
                type: 'string'
            },
            aparatID: {
                type: 'string',
                selector: '.aparat-id'
            },
            aparatSize: {
                type: 'string',
                default: "full",
                selector: '.aparat-size'
            }
        },

        example: {
            attributes: {
                iframeID: 1,
                aparatID: "13spN",
                aparatSize: "full"
            },
        },

        edit: function ( props ) {
            let attributes = props.attributes;

            return el(
                'div',
                useBlockProps( { className: props.className } ),
                el( 'div',
                    { className: "aparat-title" },
                    aparatIcon,
                    el( 'h4', {}, __( 'Aparat video', 'wp-aparat' ) ),
                ),
                el( 'h5', {}, __( 'Insert Aparat video ID:', 'wp-aparat' ) ),
                el( 'p', {}, __( 'for example, the ID of https://www.aparat.com/v/13spN is: 13spN', 'wp-aparat' ) ),
                el( 'input', {
                    placeholder: __( 'Aparat video ID...', 'wp-aparat' ),
                    value: attributes.aparatID,
                    onChange: function ( event ) {
                        let value = event.target.value;
                        let aparat_id = value.replace("https://www.aparat.com/v/", "").replace("http://www.aparat.com/v/", "").replace("www.aparat.com/v/", "").trim();
                        props.setAttributes( { aparatID: aparat_id, iframeID: "block-" + Date.now() } );
                    },
                    className: 'aparat-id'
                } ),
                el( 'h5', {}, __( 'Select the width of the video.', 'wp-aparat' ) ),
                el( 'p', {}, __( 'The default width is full size', 'wp-aparat' ) ),
                el( 'select', {
                        placeholder: __( 'Choose width', 'wp-aparat' ),
                        value: attributes.aparatSize,
                        onChange: function ( event ) {
                            let value = event.target.value;
                            props.setAttributes( { aparatSize: value } );
                        },
                        className: 'aparat-size'
                    },
                    el("option", { value: "full" }, __("Full", "wp-aparat")),
                    el("option", { value: "half" }, __("Half", "wp-aparat")),
                )
            );
        },
        save: function ( props ) {
            let attributes = props.attributes,
            iframe_id = attributes.iframeID,
            iframe_width_percent = (attributes.aparatSize === "full") ? "100%" : "50%";

            return el(
                'iframe',
                useBlockProps.save( {
                    id: 'wp-aparat-' + attributes.iframeID,
                    className: props.className + " aparat-frame aparat-" + attributes.aparatSize + "-frame",
                    src: "https://www.aparat.com/video/video/embed/videohash/" + attributes.aparatID + "/vt/frame",
                    width: iframe_width_percent,
                    allowfullscreen: "true",
                } )
            );
        },
    } );
} )(
    window.wp.blocks,
    window.wp.editor,
    window.wp.i18n,
    window.wp.element,
    window.wp.components,
    window._,
    window.wp.blockEditor
);