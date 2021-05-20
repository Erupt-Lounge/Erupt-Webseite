/*
@package: Zipp
@version: 0.1 <2019-08-09>
*/

'use strict';

class Fields {

	// METHODS
	static convert( fields ) {

		return fields.map( f => {

			const type = f.shift();
			const slug = f.shift();

			const field = this.newField( type, slug );
			field.init( f );

			return field;

		} );

	}

	static newField( type, slug, prefix = null ) {

		if ( isNil( this.fields, type ) )
			throw new Error( `could not find field ${ type }` );

		return new this.fields[type]( type, slug, prefix );

	}

	/*static convertTemplate( templ ) {

		const type = templ.shift();
		const slug = templ.shift();

		if ( isNil( this.fields, type ) )
			throw new Error( `could not find field ${ type } for a template` );

		const field = new this.fields[type]( type, slug );
		field.processTemplate( templ );

		return field;

	}*/

	static register( type, fn ) {
		this.fields[type] = fn;
	}

	// INIT
	static init() {
		this.fields = {};
	}

}
Fields.init();