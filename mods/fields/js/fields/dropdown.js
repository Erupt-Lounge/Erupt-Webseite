/*
@package: Zipp
@version: 0.1 <2019-08-09>
*/

'use strict';

class DropDown extends Field {

	// GETTER
	get value() {
		return this.el.value;
	}

	/*processTemplate( data ) {
		this.processBasicData( data );
		this.data = null;
		this.isTemplate = true;
	}*/

	/*fill( data ) { // gets only run in template environment
		return this.clone( [ this.options, data ] );
	}*/

	get html() {

		if ( this.options.length <= 5 )
			return this.renderSmall();

		return this.renderBig();

	}


	// METHODS
	

	renderSmall() {

		console.log( 'dropdown sett not defined' );

		const h = this.options.map( ([key, name]) =>
			`<option value="${ esc( key ) }"${ this.initValue === key ? ' selected' : '' }>${ esc( name ) }</option>`
		).join('');

		return this.htmlInfo + `<div class="select-cont"><select ${ this.htmlId } ${ this.htmlSlug }>${ h }</select></div>`;

	}

	renderBig() {
	
		console.log( 'big Dropdown not implemented' );

		return this.renderSmall();

	}

	listen() {
		this.el = c(i(this.id));

		this.el.o( 'change', e => this.triggerChanges() );
	}

	// INIT
	processData( data ) {
		this.options = data.shift();
	}

	exportData() {
		return [ this.options ];
	}

}
Fields.register( 'dropdown', DropDown );