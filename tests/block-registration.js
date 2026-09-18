'use strict';

const assert = require( 'node:assert/strict' );
const fs = require( 'node:fs' );
const vm = require( 'node:vm' );

// Exercise historical globals/default exports and the current named export.
for ( const exportType of [ 'global', 'default', 'ServerSideRender' ] ) {
	const blocks = {};
	const preview = function () {};
	const wp = {
		element: { createElement: ( type, props, ...children ) => ( { type, props, children } ) },
		i18n: { __: ( text ) => text },
		serverSideRender: exportType === 'global' ? preview : { [ exportType ]: preview },
		blockEditor: { useBlockProps: () => ( {} ), InspectorControls: 'InspectorControls' },
		components: { PanelBody: 'PanelBody', SelectControl: 'SelectControl', Disabled: 'Disabled' },
		blocks: { registerBlockType: ( name, settings ) => { blocks[ name ] = settings; } },
	};
	vm.runInNewContext( fs.readFileSync( require.resolve( '../assets/gallery-random-block.js' ), 'utf8' ), { window: { wp } } );
	assert.equal( Object.keys( blocks ).length, 2 );
	for ( const [ name, block ] of Object.entries( blocks ) ) {
		assert.equal( block.save(), null );
		assert.equal( block.attributes.headingLevel.default, 2 );
		assert.equal( block.supports.inserter, name === 'gallery-random/random-hero' );
		let updated;
		const tree = block.edit( { name, attributes: { headingLevel: 2 }, setAttributes: ( value ) => { updated = value; } } );
		const control = tree.children[ 0 ].children[ 0 ].children[ 0 ];
		assert.equal( control.props.options.length, 6 );
		control.props.onChange( '3' );
		assert.equal( updated.headingLevel, 3 );
		assert.equal( tree.children[ 1 ].children[ 0 ].type, preview );
		assert.equal( tree.children[ 1 ].children[ 0 ].props.block, name );
	}
}
console.log( 'PASS: Current and legacy blocks, dynamic saving, preview exports, and heading control.' );
