/*
@package: Zipp
@version: 0.1 <2019-08-09>
*/

'use strict';

function addSrBtnCls( id ) {
	return `add-sr-${ id }`;
}

// NEED TO Refactor this or rewrite (maybe the entire field thing it looks like a mess)
class SingleRepeat extends Field {

	// GETTERS
	get value() {
		return this.input.value;
	}

	get htmlField() {

		// initValue

		// ${ this.htmlSlug }

		const cls = addSrBtnCls( this.id );

		return `
		<div class="repeat-cont" ${ this.htmlId }>
			<input type="hidden" ${ this.htmlSlug } value="">
			<div class="single-repeat-fields">${ this.fields.map( f => this.rendField( f ) ).join('') }</div>
			<a href="" class="add ${ cls }"><span class="only-icon o-icon-add"></span>${ esc( this.add ) }</a>
		</div>`;

		/*
		<div class="repeat-cont" id="${ this.id }">
			<input type="hidden" name="${ this.slug }" value="${ esc( JSON.stringify( this.data ) ) }">
			<div class="single-repeat-fields">${ this.fields.map( f => this.rendField( f ) ).join( '' ) }</div>
			<a href="" class="add"><span class="only-icon o-icon-add"></span>${ esc( this.add ) }</a>
		</div>
		*/

	}

	// METHODS
	listen() {

		this.cont = c(i( this.id ));
		this.input = this.cont.c('input');
		this.addBtn = this.cont.c( '.' + addSrBtnCls( this.id ) );
		this.repFields = this.cont.c( '.single-repeat-fields' );

		this.fields.forEach( f => this.listenForField( f ) );


		this.addBtn.o( 'click', e => {
			e.preventDefault();

			const nField = this.templField.clone();

			this.repFields.be( this.rendField( nField ) );
			this.listenForField( nField );

			this.fields.push( nField );

			this.saveData();

		} );

		this.input.value = JSON.stringify( this.fields.map( f => f.value ) );

	}

	saveData() {
		const d = this.fields.map( f => f.value );
		this.input.value = JSON.stringify( d );
		this.triggerChanges();
	}

	listenForField( f ) {

		f.listen();
		f.onChanged( () => this.saveData() );

		const removeBtn = this.cont.c(`.sr-remove[data-id="${ f.id }"]`);
		removeBtn.o( 'click', e => {
			e.preventDefault();

			this.fields = this.fields.filter( nf => nf.id !== f.id );

			removeBtn.previousElementSibling.remove();
			removeBtn.remove();

			this.saveData();

		} );

		

	}

	/*listen() {

		const cont = c(i( this.id )),
			fields = cont.c( '.single-repeat-fields' );

		this.fields.forEach( f => f.listen() );

		// this doenst look good :)
		cont.c( '.add' ).o( 'click', e => {
			e.preventDefault();

			const nField = this.templ.fill( null );
			nField.onChanged( () => this.saveData() );

			this.fields.push( nField );

			fields.be( this.rendField( nField ) );

			this.listenOnClose( c(`.sr-remove[data-id="${ nField.id }"]`) );
			nField.listen();

			this.saveData();

		} );

		fields.ca( '.sr-remove' ).c( el => this.listenOnClose( el ) );

	}*/

	// INIT
	processData( data ) {
		this.add = data.shift();
		this.remove = data.shift();

		if ( isNil( this.initValue ) )
			this.initValue = [];

		console.log( 'initValue', this.initValue );

		this.templField = this.getField( data.shift() );

		this.fields = this.initValue.map( v => this.templField.clone( v ) );

	}

	exportData() {
		return [this.add, this.remove, this.templField.export()];// field
	}

	// PROTECTED
	getField( f ) {
		const type = f.shift(),
			slug = f.shift();
		const field = Fields.newField( type, slug, this.prefixedSlug );
		field.init( f );
		return field;
	}

	
	rendField( field ) {
		field.dontRenderInfo = true;
		return field.html + `<a href="" class="sr-remove only-icon o-icon-delete" title="${ esc( this.remove ) }" data-id="${ field.id }"></a>`;
	}

	/*render() {

		this.fields = this.data.map( d => this.templ.fill( d ) );

		this.fields.forEach( f => f.onChanged( () => this.saveData() ) );

		return this.rendInfo() + `
		<div class="repeat-cont" id="${ this.id }">
			<input type="hidden" name="${ this.slug }" value="${ esc( JSON.stringify( this.data ) ) }">
			<div class="single-repeat-fields">${ this.fields.map( f => this.rendField( f ) ).join( '' ) }</div>
			<a href="" class="add"><span class="only-icon o-icon-add"></span>${ esc( this.add ) }</a>
		</div>`;

	}*/

	/*saveData() {

		const cont = c(i( this.id ));
		const inp = cont.s(`[name="${ this.slug }"]`);

		inp.value = JSON.stringify( this.fields.map( f => f.getValue() ) );

	}*/

	/*listen() {

		const cont = c(i( this.id )),
			fields = cont.c( '.single-repeat-fields' );

		this.fields.forEach( f => f.listen() );

		// this doenst look good :)
		cont.c( '.add' ).o( 'click', e => {
			e.preventDefault();

			const nField = this.templ.fill( null );
			nField.onChanged( () => this.saveData() );

			this.fields.push( nField );

			fields.be( this.rendField( nField ) );

			this.listenOnClose( c(`.sr-remove[data-id="${ nField.id }"]`) );
			nField.listen();

			this.saveData();

		} );

		fields.ca( '.sr-remove' ).c( el => this.listenOnClose( el ) );

	}*/

	/*listenOnClose( el ) {
		el.o( 'click', e => {
			e.preventDefault();

			const id = el.dataset.id;

			el.previousElementSibling.remove();
			el.remove();

			// remove field from list
			this.fields = this.fields.filter( f => id !== f.id );

			// update the data reprensentation
			this.saveData();

		} );
	}*/

	/*getValue() {
		return this.fields.map( f => f.getValue() );
	}*/

}
Fields.register( 'singlerepeat', SingleRepeat );