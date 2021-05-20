<?php
/*
@package: Zipp
@version: 0.1 <2019-06-01>
*/

namespace Fields\Fields;

use Fields\Field;
use \Error;

class CheckBox extends Field {

	public $type = 'checkbox';

	public $default = false;

	/*public function render( object $data ) {
		return sprintf( '%s<input type="checkbox" name="%s" %s>', $this->renderInfo(), $this->slug, $this->getD( $data, false ) ? 'checked' : '' );
	}*/

	public function validate( object $input ) { return true; }

	public function out( object $input ) {
		return (bool) $this->getValue( $input );
	}

}