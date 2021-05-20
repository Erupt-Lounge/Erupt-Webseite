<?php
/*
@package: Zipp
@version: 1.0 <2019-06-01>
*/

namespace Fields;

use \Error;

class Field {

	public $type = 'field';

	public $slug = '';

	public $name = '';

	public $desc = '';

	public $sett = [];

	public $default = null;

	/*public function rendDesc() {
		if ( isNil( $this->desc ) || !cLen( $this->desc ) )
			return '';
		return sprintf( '<p>%s</p>', $this->desc );
	}

	public function rendName() {
		return sprintf( '<label>%s</label>', $this->name );
	}

	// this looks weird
	public function renderInfo() {

		if ( $this->dontRenderInfo )
			return '';
		
		$d = $this->rendDesc();
		$n = $this->rendName();

		if ( cLen( $d ) && cLen( $n ) )
			return sprintf( '<div class="info">%s%s</div>', $n, $d );

		return $n. $d;

	}

	public function render( object $data ) {}*/

	/*public function eb( array $d ) {
		return array_merge( [ $this->type, $this->slug, $this->name, $this->desc, $this->dontRenderInfo ], $d );
	}*/

	// gets to user (javascript)
	public function export( object $data ) {
		return array_merge( [ $this->type, $this->slug, $this->name, $this->desc, $this->sett, $this->exportValue( $data ) ], $this->exportData( $data ) );
	}

	// validate user data
	public function validate( object $input ) { return false; }

	// to database
	public function out( object $input ) {
		return $this->getValue( $input );
	}

	// to template or component
	public function view( object $data ) {
		return $this->out( $data );
	}

	public function exportValue( object $data ) {
		return $this->getValue( $data );
	}

	// INIT
	// if you pass a user variable you need to escape it!!!!
	// cfg needs to be and object or an associative array
	public function __construct( string $slug, $cfg ) {
		$cfg = (object) $cfg;
		$this->slug = $slug;
		$this->name = $cfg->name ?? 'undefined';
		$this->desc = $cfg->desc ?? null;
		$this->sett = (object) ( $cfg->sett ?? [] );
		$this->default = $cfg->default ?? null;
		$this->parseCfg( $cfg );
	}

	// PROTECTED
	protected function parseCfg( object $cfg ) {}

	protected function exportData( object $data ) { return []; }

	protected function getValue( object $data, $def = null ) {
		return $data->{ $this->slug } ?? ( $def ?? $this->default );
	}

}