( function ( wp ) {
	'use strict';

	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var ServerSideRender = wp.serverSideRender.ServerSideRender || wp.serverSideRender.default || wp.serverSideRender;

	function edit( props ) {
		return el(
			'div',
			wp.blockEditor.useBlockProps(),
			el(
				wp.blockEditor.InspectorControls,
				null,
				el(
					wp.components.PanelBody,
					{ title: __( 'Gallery settings', 'gallery-random' ) },
					el( wp.components.SelectControl, {
						label: __( 'Heading level', 'gallery-random' ),
						value: props.attributes.headingLevel,
						options: [ 1, 2, 3, 4, 5, 6 ].map( function ( level ) {
							return { label: 'H' + level, value: level };
						} ),
						onChange: function ( value ) {
							props.setAttributes( { headingLevel: Number( value ) } );
						},
					} )
				)
			),
			el( wp.components.Disabled, null, el( ServerSideRender, {
				block: props.name,
				attributes: props.attributes,
			} ) )
		);
	}

	[ 'gallery-random/random-hero', 'gallery-rendom/random-hero' ].forEach( function ( name ) {
		wp.blocks.registerBlockType( name, {
			apiVersion: 2,
			title: __( 'Gallery Random', 'gallery-random' ),
			description: __( 'Display a random gallery image with its title, description, and buttons.', 'gallery-random' ),
			icon: 'format-gallery',
			category: 'widgets',
			attributes: { headingLevel: { type: 'number', default: 2 } },
			supports: { html: false, inserter: name === 'gallery-random/random-hero' },
			edit: edit,
			save: function () {
				return null;
			},
		} );
	} );
}( window.wp ) );
