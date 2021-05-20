<?php
/*
@package: Zipp
@version: 0.1 <2019-06-01>
*/

namespace Time\Fields;

use \Error;

use Fields\Fields\Text;

class Time extends Text {

	public $type = 'time';

	/*public function __construct( string $slug, string $name, string $desc = null, array $settings = [] ) {
		$this->slug = $slug;
		$this->name = $name;
		$this->desc = $desc;
		$this->sett = (object) $settings;
	}*/

	// at 
	/*public function render( object $data ) {
		return sprintf( '%s<input type="text" name="%s" value="%s">', $this->renderInfo(), $this->slug, $this->getRd( $data ) );
	}*/

	/*public function validate( object $input ) {

		$d = $input->{$this->slug} ?? null;

		if ( isNil( $d ) || !is_string( $d ) )
			return false;

		return Validator::str( $d, $this->sett->req ?? false, $this->sett->min ?? -1, $this->sett->max ?? -1 );

	}*/

	/*public function out( object $in ) {

		$d = explode( ',', $input->{$this->slug} );
		return array_map( 'trim', $d );

	}*/

}