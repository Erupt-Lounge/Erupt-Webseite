<?php
/*
@package: Zipp
@version: 1.0 <2019-06-01>
*/

namespace Fields\Fields;

use Fields\{Field, Validator};
use \Error;

class Keywords extends Text {

	public $type = 'keywords';

	/*public function render( object $data ) {
		$req = $this->sett->req ?? false;
		$v = implode( ', ', $this->getD( $data, '' ) );
		return sprintf( '%s<input type="text" name="%s" value="%s"%s>', $this->renderInfo(), $this->slug, e( $v ), $req ? ' required' : '' );
	}*/

	/*public function export( object $data ) {
		return $this->eb( [ $this->sett ] );
	}*/

	/*public function validate( object $input ) {

		$d = $input->{$this->slug} ?? null;

		if ( isNil( $d ) || !is_string( $d ) )
			return false;

		return Validator::str( $d, $this->sett->req ?? false, $this->sett->min ?? -1, $this->sett->max ?? -1 );

	}*/

	public function out( object $in ) {
		$d = explode( ',', (string) $this->getValue( $in ) );
		return array_map( 'trim', $d );
	}

	/*public function view( object $data ) {
		return (array) $this->getValue( $data, [] );
	}*/

}